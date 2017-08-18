<?php
namespace cmsgears\core\common\actions\tag;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Remove action disable the tag mapping for model by de-activating it.
 */
class Remove extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $parent 	= true;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// RemoveTag -----------------------------

	public function run( $cid ) {

		if( isset( $this->model ) && isset( $cid ) ) {

			$modelTagService	= Yii::$app->factory->get( 'modelTagService' );

			$modelTag	= $modelTagService->getById( $cid );

			if( isset( $modelTag ) && $modelTag->checkParent( $this->model->id, $this->parentType ) ) {

				$modelTagService->disable( $modelTag );

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}