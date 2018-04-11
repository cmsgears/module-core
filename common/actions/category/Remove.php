<?php
namespace cmsgears\core\common\actions\category;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Remove action disable the category mapping for model by de-activating it.
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

	// Remove --------------------------------

	public function run( $cid ) {

		if( isset( $this->model ) && isset( $cid ) ) {

			$modelCategoryService	= Yii::$app->factory->get( 'modelCategoryService' );

			$modelCategory	= $modelCategoryService->getById( $cid );

			if( isset( $modelCategory ) && $modelCategory->isParentValid( $this->model->id, $this->parentType ) ) {

				$modelCategoryService->disable( $modelCategory );

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
