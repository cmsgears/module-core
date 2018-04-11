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

use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\base\Action;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * CreateItem creates the gallery item.
 *
 * @since 1.0.0
 */
class CreateItem extends Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// It allows unlimited items by default.
	public $maxItems = 0;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CreateItem ----------------------------

	public function run( $id ) {

		$galleryService	= Yii::$app->factory->get( 'galleryService' );
		$fileService	= Yii::$app->factory->get( 'fileService' );
		$gallery		= $galleryService->getById( $id );

		if( isset( $gallery ) ) {

			if( $this->maxItems > 0 ) {

				$items	= $gallery->files;

				if( count( $items ) >= $this->maxItems ) {

					// Trigger Ajax Failure
					return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), [ 'limit' => "You are not allowed to add more than $this->maxItems items." ] );
				}
			}

			$item = new File();

			$item->siteId = $gallery->siteId;

			if( $item->load( Yii::$app->request->post(), 'File' ) && $item->validate() ) {

				$item	= $galleryService->createItem( $gallery, $item );
				$item	= $fileService->getById( $item->id );
				$data	= [ 'id' => $item->id, 'thumbUrl' => $item->getThumbUrl(), 'title' => $item->title, 'description' => $item->description, 'alt' => $item->altText, 'url' => $item->url ];

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
			}

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $item );

			// Trigger Ajax Failure
			return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
