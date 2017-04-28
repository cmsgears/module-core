<?php
namespace cmsgears\core\common\actions\gallery;

// Yii Imports
use \Yii;
use yii\base\InvalidConfigException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

class DeleteItem extends \cmsgears\core\common\base\Action {

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

	public function run( $id, $iid ) {

		$galleryService		= Yii::$app->factory->get( 'galleryService' );
		$modelFileService	= Yii::$app->factory->get( 'modelFileService' );
		$gallery			= $galleryService->getById( $id );

		if( isset( $gallery ) ) {

			if( $this->minItems > 0 ) {

				$items	= $gallery->files;

				if( count( $items ) <= $this->minItems ) {

					// Trigger Ajax Failure
					return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), [ 'limit' => "Minimum $this->minItems items are required for a gallery." ] );
				}
			}

			$modelFile	= $modelFileService->getByModelId( $gallery->id, CoreGlobal::TYPE_GALLERY, $iid );

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
