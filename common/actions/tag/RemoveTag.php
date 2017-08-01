<?php
namespace cmsgears\core\common\actions\tag;

// Yii Imports
use Yii;

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

		$post	= yii::$app->request->post();

		if( isset( $this->model ) && isset( $post[ 'tagId' ] ) ) {

			$modelTagService	= Yii::$app->factory->get( 'modelTagService' );
			$parentId			= $this->model->id;
			$parentType			= $this->parentType;
			$modelId			= $post[ 'tagId' ];

			$mapping			= $modelTagService->getByModelId( $parentId, $parentType, $modelId );

			if( isset( $mapping ) ) {

				$modelTagService->disable( $mapping );

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
