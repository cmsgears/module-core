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
 * DeleteItem delete the gallery item.
 *
 * @since 1.0.0
 */
class DeleteItem extends Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// It allows unlimited items by default.
	public $minItems = 0;

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

	/**
	 * It deletes the gallery item using given $cid and $iid.
	 *
	 * @param type $id Parent Id
	 * @param type $cid Gallery Id
	 * @param type $iid Item Id
	 * @return string
	 */
	public function run( $id, $cid, $fid ) {

		$galleryService		= Yii::$app->factory->get( 'galleryService' );
		$modelFileService	= Yii::$app->factory->get( 'modelFileService' );
		$gallery			= $galleryService->getById( $cid );

		if( isset( $gallery ) ) {

			if( $this->minItems > 0 ) {

				$items = $gallery->files;

				if( count( $items ) <= $this->minItems ) {

					// Trigger Ajax Failure
					return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), [ 'limit' => "Minimum $this->minItems items are required for a gallery." ] );
				}
			}

			$modelFile = $modelFileService->getFirstByParentModelId( $gallery->id, CoreGlobal::TYPE_GALLERY, $iid );

			if( isset( $modelFile ) ) {

				$modelFileService->delete( $modelFile );

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $iid );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
