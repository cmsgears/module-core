<?php
namespace cmsgears\core\common\actions\category;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * AssignCategory maps existing category to model in action using ModelCategory mapper.
 *
 * The controller must provide appropriate model service having model class, model table and parent type defined for the base model.
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

	protected $typed 	= true;

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

			$modelCategoryService	= Yii::$app->factory->get( 'modelCategoryService' );

			$modelCategoryService->activateByModelId( $this->model->id, $this->modelService->getParentType(), $post[ 'categoryId' ] );

			$categories		= $this->model->activeCategories;
			$data			= [];

			foreach ( $categories as $category ) {

				$data[]	= [ 'name' => $category->name, 'id' => $category->id ];
			}

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
