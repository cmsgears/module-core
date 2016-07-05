<?php
namespace cmsgears\core\frontend\actions\common;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\CmgFile;

use cmsgears\cms\common\services\mappers\ModelContentService;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * UpdateContentBanner can be used to update banner for models using ModelContent trait.
 *
 * The controller must provide modelService variable using approprite service class.
 */
class UpdateContentBanner extends ModelAction {

	// Variables ---------------------------------------------------

	// Constants/Statics --

	// Public -------------

	// Private/Protected --

	// Constructor and Initialisation ------------------------------

	// Instance Methods --------------------------------------------

	// UpdateContentBanner ---------------

	public function run() {

		if( isset( $this->model ) ) {

			$content	= $this->model->content;
			$banner	 	= CmgFile::loadFile( $content->banner, 'File' );

			if( ModelContentService::updateBanner( $content, $banner ) ) {

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

?>