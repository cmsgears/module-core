<?php
namespace cmsgears\core\common\actions\base;

// Yii Imports
use Yii;

/**
 * ModelAction is the base action for model centric actions. It find the model in action using id or slug param.
 *
 * The controllers using children of this action class must provide appropriate model service having model class and table.
 * The model service can optionally provide type. If mappers are required in child clases, the type forms the parent type for them.
 *
 * It primarily uses slug to find the model, but it can also use id as fallback option based on the url params.
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

	/** The id param to identify model in action using model service provided by controller. It works in all scenarios since we support primary id for all tables. */
	public $idParam		= 'id';

	/**	 The slug param to identify model in action using model service provided by controller. It works in cases where slug is supported by model in action and given preference over id. */
	public $slugParam	= 'slug';

	/**	 The type param to identify model in action using model service provided by controller. It works in cases where type column is used by model in action. */
	public $typeParam	= 'type';

	// Flag to identify whether parent model supports type column. It can be configured in controller or via url params.
	public $typed		= false;

	// The type to be used to discover parent model. It can be configured in controller or via url params.
	public $type;

	// Turn it off if model discovery will be taken care by controller using filters.
	public $discover	= true;

	// ==== Mapper/Resource ============== //

	public $cidParam	= 'cid';

	public $cslugParam	= 'cslug';

	public $ctypeParam	= 'ctype';

	// Flag to identify whether model supports parent type column. It can be configured in controller or via url params.
	public $parent		= false;

	/** Parent type will be used by mappers or resources to identiy parent. */
	public $parentType;

	/** Model type provided by model service. */
	public $modelType;

	/**	 The model in action. */
	public $model;

	// Protected --------------

	// Model service to identify model in action. The controller implementing this action must specify model service.
	protected $modelService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Action --------------------------------

	/**
	 * The method init do the below listed tasks:
	 * 1. Configure modelService from controller
	 * 2. Detect whether action is for typed models
	 * 2. Configure parentType for model mappers
	 * 3. Configure model from controller or discover it based on either slug or id
	 */
	public function init() {

		parent::init();

		// Model service provided by controller
		$this->modelService	= $this->controller->modelService;

		// Detect whether type is required for model discovery
		$typed			= Yii::$app->request->get( 'typed', null );
		$this->typed	= isset( $typed ) ? $typed : $this->typed;

		// Configure model type for typed models
		if( $this->typed ) {

			$modelType			= Yii::$app->request->get( 'model-type', null );
			$this->modelType	= isset( $modelType ) ? $modelType : $this->modelType;
			$this->modelType	= isset( $this->modelType ) ? $this->modelType : $this->modelService->getParentType();
		}

		// Detect whether parent is required for model mapping
		$parent			= Yii::$app->request->get( 'parent', null );
		$this->parent	= isset( $parent ) ? $parent : $this->parent;

		// Configure parent type for mapping
		if( $this->parent ) {

			$parentType			= Yii::$app->request->get( 'parent-type', null );
			$this->parentType	= isset( $parentType ) ? $parentType : $this->parentType;
			$this->parentType	= isset( $this->parentType ) ? $this->parentType : $this->modelService->getParentType();
		}

		// In certain situations controller can provide the model directly. In case it's not provided by controller, we discover it using model service and url parameters.
		if( !isset( $this->model ) ) {

			// Read slug from url
			$slug	= Yii::$app->request->get( $this->slugParam, null );

			// Try to find model using slug
			if( isset( $slug ) ) {

				if( $this->typed ) {

					// Search model using model service and parent type
					if( $this->parent && isset( $this->parentType ) ) {

						$this->model	= $this->modelService->getBySlugType( $slug, $this->parentType );
					}
					// Search model using model service and model type
					else if( isset( $this->modelType ) ) {

						$this->model	= $this->modelService->getBySlugType( $slug, $this->modelType );
					}
				}
				else {

					$this->model	= $this->modelService->getBySlug( $slug );
				}
			}
			// Try to find model using id
			else {

				$id	= Yii::$app->request->get( $this->idParam, null );

				if( isset( $id ) ) {

					$this->model	= $this->modelService->getById( $id );
				}
			}
		}
	}
}
