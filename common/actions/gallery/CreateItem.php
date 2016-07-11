<?php
namespace cmsgears\core\common\actions\gallery;

// Yii Imports
use \Yii;
use yii\base\InvalidConfigException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\utilities\AjaxUtil;

class CreateItem extends \cmsgears\core\common\base\Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

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

	public function run( $slug ) {

		$galleryService	= Yii::$app->factory->get( 'galleryService' );
		$fileService	= Yii::$app->factory->get( 'fileService' );

		$gallery 		= $galleryService->getBySlug( $slug );

		if( isset( $gallery ) ) {

			$item 	= new File();

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