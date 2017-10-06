<?php
namespace cmsgears\core\common\actions\content;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * UpdateBanner can be used to update banner of discovered model.
 */
class UpdateBanner extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $fileName	= 'Banner';

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UpdateBanner --------------------------

	public function run() {

		if( isset( $this->model ) ) {

			$banner = $this->model->banner;

			if( !isset( $banner ) ) {

				$banner	= new File();
			}

			if( $banner->load( Yii::$app->request->post(), $this->fileName ) ) {

				$this->modelService->updateBanner( $this->model, $banner );

				$this->model->refresh();

				$banner		= $this->model->banner;
				$response	= [ 'fileUrl' => $banner->getFileUrl() ];

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $response );
			}

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $banner );

			// Trigger Ajax Failure
			return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
