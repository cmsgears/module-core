<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\gallery\item;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Delete delete the gallery item.
 *
 * @since 1.0.0
 */
class Delete extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $direct = false;

	// It allows unlimited items by default.
	public $minItems = 0;

	// Protected --------------

	protected $galleryService;
	protected $modelFileService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->galleryService	= Yii::$app->factory->get( 'galleryService' );
		$this->modelFileService = Yii::$app->factory->get( 'modelFileService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Delete --------------------------------

	/**
	 * It deletes the gallery item using given $cid and $fid.
	 *
	 * @param type $cid Gallery Id
	 * @param type $fid File Id
	 * @return array
	 */
	public function run( $cid, $fid ) {

		$model		= $this->model;
		$gallery	= $this->galleryService->getById( $cid );

		if( isset( $gallery ) && ( $this->direct || $gallery->belongsTo( $model ) ) ) {

			if( $this->minItems > 0 ) {

				$items = $gallery->files;

				if( count( $items ) <= $this->minItems ) {

					// Trigger Ajax Failure
					return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), [ 'limit' => "Minimum $this->minItems items are required for a gallery." ] );
				}
			}

			$modelFile = $this->modelFileService->getFirstByParentModelId( $gallery->id, CoreGlobal::TYPE_GALLERY, $fid );

			if( isset( $modelFile ) ) {

				$data = [ 'mid' => $modelFile->id ];

				$this->modelFileService->delete( $modelFile );

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
