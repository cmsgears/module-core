<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\gallery;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\base\Action;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * UpdateItem update the gallery item.
 *
 * @since 1.0.0
 */
class UpdateItem extends Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $fileName = 'File';

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

	public function run( $id, $iid ) {

		$galleryService		= Yii::$app->factory->get( 'galleryService' );
		$fileService		= Yii::$app->factory->get( 'fileService' );
		$modelFileService	= Yii::$app->factory->get( 'modelFileService' );

		$gallery = $galleryService->getById( $id );

		if( isset( $gallery ) ) {

			$modelFile	= $modelFileService->getByModelId( $gallery->id, CoreGlobal::TYPE_GALLERY, $iid );
			$file		= $modelFile->model;

			if( isset( $file ) && $file->load( Yii::$app->request->post(), $this->fileName ) ) {

				$fileService->saveImage( $file );

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $iid );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
