<?php
namespace cmsgears\core\common\actions\tag;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * RemoveTag disable a tag for model by de-activating it.
 *
 * The controller must provide appropriate model service having model class, model table and parent type defined for the base model.
 */
class RemoveTag extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $typed = true;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// RemoveTag -----------------------------

	public function run( $tslug, $mtype ) {

		if( isset( $this->model ) ) {

			$modelTagService	= Yii::$app->factory->get( 'modelTagService' );

			$modelTagService->deleteByTagSlug( $this->model->id, $this->modelService->getParentType(), $tslug, $mtype );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $tslug );
		}

		// Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
