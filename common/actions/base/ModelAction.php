<?php
namespace cmsgears\core\common\actions\base;

// Yii Imports
use \Yii;

/**
 * ModelAction is the base action for model centric actions. It find the model in action using id or slug param.
 *
 * The controllers using children of this action class must provide appropriate model service having model class and table.
 * The model service can optionally provide type.
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

	public $idParam		= 'id';
	public $slugParam	= 'slug';
	public $typeParam	= 'type';

	public $model;

	// Protected --------------

	// Flag to identify whether type is required to perform action.
	protected $typed	= false;

	// Model service for active model
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

		$this->modelService		= $this->controller->modelService;

		// Model is not provided by controller
		if( !isset( $this->model ) ) {

			// Try to find model using slug
			$slug	= Yii::$app->request->get( $this->slugParam, null );

			if( isset( $slug ) ) {

				$typed	= Yii::$app->request->get( 'typed', null );

				if( !isset( $typed ) ) {

					$typed = true;
				}

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
			else {

				// Try to find model using id
				$id	= Yii::$app->request->get( $this->idParam, null );

				if( isset( $id ) ) {

					$this->model	= $this->modelService->getById( $id );
				}
			}
		}
	}
}
