<?php
namespace cmsgears\core\common\actions\tag;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * AssignTags map tags for models using ModelTag mapper.
 *
 * In case a tag does not exist for model type, it will be created and than mapping will be done.
 *
 * The controller must provide appropriate model service having model class and model table defined for the base model. The service might provide parent type.
 */
class AssignTags extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public $typed	= true;

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

	// AssignTags ----------------------------

	public function run() {

		$post	= yii::$app->request->post();

		if( isset( $this->model ) && isset( $post[ 'tags' ] ) ) {

			$modelTagService	= Yii::$app->factory->get( 'modelTagService' );
			$parentId			= $this->model->id;
			$parentType			= $this->parentType;
			$tags				= $post[ 'tags' ];

			$modelTagService->createFromCsv( $parentId, $parentType, $tags );

			$tags		= $this->model->activeTags;
			$data		= [];

			foreach ( $tags as $tag ) {

				$data[]	= [ 'name' => $tag->name, 'slug' => $tag->slug, 'modelSlug' => isset( $this->model->slug ) ? $this->model->slug : null ];
			}

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
