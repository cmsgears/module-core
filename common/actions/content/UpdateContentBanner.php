<?php
namespace cmsgears\core\common\actions\content;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * UpdateContentBanner can be used to update banner for models using ModelContent trait.
 *
 * The controller must provide modelService variable using approprite service class.
 */
class UpdateContentBanner extends \cmsgears\core\common\actions\base\ModelAction {

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

	// AssignTags ----------------------------

	public function run() {

		if( isset( $this->model ) ) {

			$modelContentService = Yii::$app->factory->get( 'modelContentService' );

			$content	= $this->model->modelContent;
			$banner	 	= File::loadFile( $content->banner, $this->fileName );

			if( $modelContentService->updateBanner( $content, $banner ) ) {

				$response	= [ 'fileUrl' => $banner->getFileUrl() ];

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
