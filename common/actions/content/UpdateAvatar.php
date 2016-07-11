<?php
namespace cmsgears\core\common\actions\content;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * UpdateAvatar can be used to update avatar for models.
 *
 * The controller must provide modelService variable using approprite service class.
 */
class UpdateAvatar extends ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UpdateAvatar --------------------------

	public function run() {

		if( isset( $this->model ) ) {

			$avatar = $this->model->avatar;

			if( !isset( $avatar ) ) {

				$avatar	= new File();
			}

			if( $avatar->load( Yii::$app->request->post(), 'Avatar' ) ) {

				$this->modelService->updateAvatar( $this->model, $avatar );

				$avatar		= $this->model->avatar;
				$response	= [ 'fileUrl' => $avatar->getFileUrl() ];

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $response );
			}

			// Trigger Ajax Failure
	    	return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
		}

		// Trigger Ajax Failure
    	return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}