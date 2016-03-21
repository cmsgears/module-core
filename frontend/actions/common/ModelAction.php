<?php
namespace cmsgears\core\frontend\actions\common;

// Yii Imports
use \Yii;

class ModelAction extends \yii\base\Action {

	// Variables ---------------------------------------------------

	// Constants/Statics --

	// Public -------------

	public $idParam		= 'id';
	public $slugParam	= 'slug';

	public $model;
	public $modelType;
	public $modelService;

	// Private/Protected --

	// Constructor and Initialisation ------------------------------

    public function init() {

		// Model is not provided by controller
		if( !isset( $this->model ) ) {

			// Try to find model using slug
			$slug	= Yii::$app->request->get( $this->slugParam, null );

			if( isset( $slug ) ) {

				if( isset( $this->modelService ) ) {

					$this->model	= $this->modelService->findBySlug( $slug );
				}
				else if( isset( Yii::$app->controller->modelService ) ) {

					$this->model	= Yii::$app->controller->modelService->findBySlug( $slug );
				}
			}
			else {

				// Try to find model using id
				$id	= Yii::$app->request->get( $this->idParam, null );

				if( isset( $id ) ) {

					if( isset( $this->modelService ) ) {

						$this->model	= $this->modelService->findById( $id );
					}
					else if( isset( Yii::$app->controller->modelService ) ) {

						$this->model	= Yii::$app->controller->modelService->findById( $id );
					}
				}
			}
		}

		if( !isset( $this->modelType ) && isset( Yii::$app->controller->modelType ) ) {

			$this->modelType = Yii::$app->controller->modelType;
		}
    }

	// Instance Methods --------------------------------------------

	// ModelAction -----------------------
}

?>
