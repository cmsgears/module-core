<?php
namespace cmsgears\core\frontend\actions\common;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\CmgFile;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * UpdateAvatar can be used to update avatar for models.
 * 
 * The controller must provide modelService variable using approprite service class.
 */
class UpdateAvatar extends ModelAction {

	// Variables ---------------------------------------------------

	// Constants/Statics --

	// Public -------------

	// Private/Protected --

	// Constructor and Initialisation ------------------------------

	// Instance Methods --------------------------------------------

	// UpdateAvatar ----------------------

	public function run() {

		if( isset( $this->model ) ) {

			$avatar = CmgFile::loadFile( $this->model->avatar, 'Avatar' );

			if( Yii::$app->controller->modelService->updateAvatar( $this->model, $avatar ) ) {

				$avatar		= $this->model->avatar;
				$response	= [ 'fileUrl' => $avatar->getFileUrl() ];

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $response );
			}

			// Trigger Ajax Failure
	    	return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
		}

		// Trigger Ajax Failure
    	return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>
