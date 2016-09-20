<?php
namespace cmsgears\core\common\actions\gallery;

// Yii Imports
use \Yii;
use yii\base\InvalidConfigException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

class UpdateItem extends \cmsgears\core\common\base\Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $fileName	= 'File';

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// DeleteItem ----------------------------

	public function run( $slug, $id, $type = null ) {

		$galleryService		= Yii::$app->factory->get( 'galleryService' );
		$modelFileService	= Yii::$app->factory->get( 'modelFileService' );
		$fileService		= Yii::$app->factory->get( 'fileService' );
		$type				= isset( $type ) ? $type : CoreGlobal::TYPE_SITE;

		$gallery			= $galleryService->getBySlugType( $slug, $type );

		if( isset( $gallery ) ) {

			$modelFile	= $modelFileService->getByModelId( $gallery->id, CoreGlobal::TYPE_GALLERY, $id );
			$file		= $modelFile->file;

			if( isset( $file ) && $file->load( Yii::$app->request->post(), $this->fileName ) ) {

				$fileService->saveImage( $file );

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $id );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
