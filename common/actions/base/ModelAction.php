<?php
namespace cmsgears\core\common\actions\base;

// Yii Imports
use Yii;

// CMG Improts
use cmsgears\core\common\config\CoreGlobal;

/**
 * ModelAction is the base action for model centric actions. It find the model in action using
 * id or slug parameter to perform mapper or resource specific actions.
 *
 * The controllers using children of this action class must define model service having model class
 * and table. The model service must also provide type to be used as parent type in case mapper or
 * resource supports parentId and parentType columns.
 *
 * It primarily uses slug to discover the model, but it can also use id as fallback option.
 */
class ModelAction extends \cmsgears\core\common\base\Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// ==== Parent Discovery ============= //

	/**
	 * The id parameter identifies model in action using model service provided by controller.
	 * It works in all scenarios since we support primary id for all tables.
	 */
	public $idParam		= 'id';

	/**	 The slug parameter identifies model in action using model service provided by controller.
	 * It works in cases where slug is supported by model in action and given preference over id.
	 */
	public $slugParam	= 'slug';

	/**	 The type parameter identifies model in action using model service provided by controller.
	 * It works in cases where type column is used by model in action.
	 */
	public $typeParam	= 'type';

	// Flag to identify whether parent model supports type column.
	public $typed		= false;

	// The type to be used to discover parent model.
	public $type;

	// ==== Mapper/Resource ============== //

	public $cidParam	= 'cid';

	public $cslugParam	= 'cslug';

	public $ctypeParam	= 'ctype';

	/**
	 * Flag to identify whether model supports parentId and parentType columns. In all other cases
	 * modelId column will be used to map mapper or resource.
	 */
	public $parent = false;

	/**
	 * Parent type to be used by mapper or resource having parentId and parentType columns.
	 */
	public $parentType;

	// Type of Mapper or Resource. It's different from $parentType and $type.
	public $modelType;

	/**
	 * The model discovered either by controller(filters - discover or owner) or ModelAction's init method.
	 * It can also be directly set by controller.
	 */
	public $model;

	// Protected --------------

	// Model service to identify model in action. The controller must define model service.
	protected $modelService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	/**
	 * The method init do the below listed tasks:
	 * 1. Configure $modelService of parent model to be discovered
	 * 2. Configure $parentType
	 * 3. Configure $typed and $type of parent model to be discovered
	 * 4. Configure $modelType
	 */
	public function init() {

		parent::init();

		// Model service provided by controller
		$this->modelService	= $this->controller->modelService;

		// Configure parentType
		if( $this->parent ) {

			$this->parentType = $this->modelService->getParentType();
		}

		$this->typed = $this->modelService->isTyped();

		// Configure type
		if( $this->typed ) {

			$type		= Yii::$app->request->get( $this->typeParam, null );
			$this->type	= isset( $type ) ? $type : $this->type;
		}

		// Mapper or Resource Type
		$modelType			= Yii::$app->request->get( 'model-type', null );
		$modelType			= isset( $modelType ) ? $modelType : Yii::$app->request->post( 'model-type', null );
		$modelType			= isset( $modelType ) ? $modelType : $this->modelType;
		$this->modelType	= isset( $modelType ) ? $modelType : CoreGlobal::TYPE_DEFAULT;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Action --------------------------------

    protected function beforeRun() {

		// Get model from controller
		$this->model = $this->model ?? $this->controller->model;

		// Proceed only if model is not discovered yet
		if( !isset( $this->model ) ) {

			// Read slug from url
			$slug = Yii::$app->request->get( $this->slugParam, null );

			// Try to find model using slug
			if( isset( $slug ) ) {

				if( $this->typed ) {

					// Search model using slug and type ... It works well for tables having unique slug and type
					if( isset( $this->type ) ) {

						$this->model = $this->modelService->getBySlugType( $slug, $this->type );
					}
					// Get first model of matched slug ... It works well for tables having unique slug
					else {

						$this->model = $this->modelService->getBySlug( $slug, true );
					}
				}
				else {

					$this->model = $this->modelService->getBySlug( $slug );
				}
			}
			// Try to find model using id
			else {

				$id	= Yii::$app->request->get( $this->idParam, null );

				if( isset( $id ) ) {

					$this->model = $this->modelService->getById( $id );
				}
			}
		}

		if( isset( $this->model ) ) {

			$this->controller->model = $this->model;

			return true;
		}
		else {

			return false;
		}
    }
}
