<?php
namespace cmsgears\core\common\actions\category;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * AssignCategory maps existing category to model in action using ModelCategory mapper.
 *
 * The controller must provide appropriate model service having model class and model table defined for the base model. The service might provide parent type.
 */
class AssignCategory extends \cmsgears\core\common\actions\base\ModelAction {

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

	// AssignCategory ------------------------

	public function run() {

		$post	= yii::$app->request->post();

		if( isset( $this->model ) && isset( $post[ 'categoryId' ] ) ) {

			$categoryService		= Yii::$app->factory->get( 'categoryService' );
			$modelCategoryService	= Yii::$app->factory->get( 'modelCategoryService' );
			$parentId				= $this->model->id;
			$parentType				= $this->parentType;
			$modelId				= $post[ 'categoryId' ];

			$modelCategoryService->activateByModelId( $parentId, $parentType, $modelId );

			$category	= $categoryService->getById( $modelId );

			$data		= [ 'id' => $category->id, 'name' => $category->name ];

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
