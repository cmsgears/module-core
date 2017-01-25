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
 * The controller must provide appropriate model service having model class and model table defined for the base model. The service might provide parent type.
 */
class RemoveTag extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $typed	= true;

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

	public function run() {

		if( isset( $this->model ) ) {

			$tagSlug			= Yii::$app->request->get( 'tag-slug' );

			$modelTagService	= Yii::$app->factory->get( 'modelTagService' );
			$parentId			= $this->model->id;
			$parentType			= $this->parentType;

			// Disable tag mapping
			$modelTagService->deleteByTagSlug( $parentId, $parentType, $tagSlug );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $tagSlug );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
