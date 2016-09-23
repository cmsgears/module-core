<?php
namespace cmsgears\core\common\actions\base;

// Yii Imports
use \Yii;

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

	// Primary Model

	/** The id param to identify model in action using model service provided by controller. It works in all scenarios since we support primary id for all tables. */
	public $idParam		= 'id';

	/**  The slug param to identify model in action using model service provided by controller. It works in cases where slug is supported by model in action and given preference over id. */
	public $slugParam	= 'slug';

	/**  The model in action. */
	public $model;

	// Typed Parent and Mapped Model

	/** Type param could be different in sutuations where mapper itself support type else it will default to parent type. */
	public $typeParam	= 'type';

	/** Type can be either type supported by model service or generic type for similar models. */
	public $type;

	// Protected --------------

	// Flag to identify whether parent type is required to perform action.
	protected $typed	= false;

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

	public function init() {

		parent::init();

		// In certain situations controller can provided the model directly. In case it's not provided by controller, we discover it using model service and url parameters.
		if( !isset( $this->model ) ) {

			// Model service provided by controller
			$this->modelService	= $this->controller->modelService;

			// Read slug from url
			$slug				= Yii::$app->request->get( $this->slugParam, null );

			// Try to find model using slug
			if( isset( $slug ) ) {

				$typed	= Yii::$app->request->get( 'typed', null );
				$typed	= isset( $typed ) ? $typed : true;

				// Type control to both child and url. It allows to configure it either way.
				if( $this->typed && $typed ) {

					$type	= Yii::$app->request->get( $this->typeParam, null );

					// Override model service parent type to search appropriate models
					if( isset( $type ) ) {

						$this->model	= $this->modelService->getBySlugType( $slug, $type );
					}
					else {

						$this->model	= $this->modelService->getBySlugType( $slug, $this->modelService->getParentType() );
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
