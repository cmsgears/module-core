<?php
namespace cmsgears\core\common\actions\meta;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Create add meta for given parent model.
 */
class Create extends \cmsgears\core\common\base\Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelService;

	protected $metaService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->modelService		= $this->controller->modelService;

		$this->metaService		= $this->controller->metaService;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Create --------------------------------

	/**
	 * Create Meta for given parent slug and parent type.
	 */
	public function run( $id = null, $slug = null, $type = null ) {

		// Find Meta parent
		$parent	= null;

		if( isset( $id ) ) {

			$parent	= $this->modelService->getById( $id );
		}
		else if( isset( $type ) ) {

			$parent	= $this->modelService->getBySlugType( $slug, $type );
		}
		else {

			$parent	= $this->modelService->getBySlug( $slug );
		}

		// Delete meta
		if( isset( $parent ) ) {

			$metaClass		= $this->metaService->getModelClass();
			$meta			= new $metaClass;

			if( $meta->hasAttribute( 'modelId' ) ) {

				$meta->modelId	= $parent->id;
			}
			else {

				$meta->parentId		= $parent->id;
				$meta->parentType	= $this->modelService->getParentType();
			}

			if( $meta->load( Yii::$app->request->post(), $meta->getClassName() ) && $meta->validate() ) {

				$this->metaService->create( $meta );

				$data	= [ 'id' => $meta->id, 'name' => $meta->name, 'value' => $meta->value ];

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
			}

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $meta );

			// Trigger Ajax Failure
			return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}