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

	protected $galleryService;
	protected $fileService;
	protected $modelFileService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->galleryService	= Yii::$app->factory->get( 'galleryService' );
		$this->fileService		= Yii::$app->factory->get( 'fileService' );
		$this->modelFileService = Yii::$app->factory->get( 'modelFileService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// DeleteItem ----------------------------

	public function run( $id, $cid, $fid ) {

		$gallery = $this->galleryService->getById( $cid );

		if( isset( $gallery ) ) {

			$modelFile = $this->modelFileService->getFirstByParentModelId( $gallery->id, CoreGlobal::TYPE_GALLERY, $fid );

			if( isset( $modelFile ) && $modelFile->isParentValid( $gallery->id, CoreGlobal::TYPE_GALLERY ) ) {

				$file = $modelFile->model;

				if( isset( $file ) && $file->load( Yii::$app->request->post(), $this->fileName ) ) {

					$this->fileService->saveImage( $file );

					$data	= $file->getAttributeArray( [ 'title', 'altText', 'link', 'description' ] );

					$data[ 'id' ]	= $modelFile->id;
					$data[ 'fid' ]	= $file->id;
					$data[ 'thumbUrl' ] = $file->getThumbUrl();

					// Trigger Ajax Success
					return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
				}
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
