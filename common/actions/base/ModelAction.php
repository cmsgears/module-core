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

	public $idParam		= 'id';
	public $slugParam	= 'slug';

	public $model;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Flag to identify whether type is required to perform action.
	protected $type = false;

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

		// Model is not provided by controller
		if( !isset( $this->model ) ) {

			// Try to find model using slug
			$slug	= Yii::$app->request->get( $this->slugParam, null );

			if( isset( $slug ) ) {

				if( $this->type ) {

					$this->model	= $this->modelService->getBySlugType( $slug, $this->modelService->getParentType() );
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
