<?php
namespace cmsgears\core\common\actions\tag;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Assign action map tags for models using ModelTag mapper.
 *
 * In case a tag does not exist for model type, it will be created and than mapping will be done.
 */
class Assign extends \cmsgears\core\common\actions\base\ModelAction {

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

	// Assign --------------------------------

	public function run() {

		$post	= yii::$app->request->post();

		if( isset( $this->model ) && isset( $post[ 'tags' ] ) ) {

			$modelTagService	= Yii::$app->factory->get( 'modelTagService' );
			$parentId			= $this->model->id;
			$parentType			= $this->parentType;
			$tags				= $post[ 'tags' ];

			$modelTagService->createFromCsv( $parentId, $parentType, $tags );

			$modelTags	= $this->model->activeModelTags;
			$data		= [];

			foreach ( $modelTags as $modelTag ) {

				$data[]	= [ 'cid' => $modelTag->id, 'name' => $modelTag->model->name ];
			}

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
