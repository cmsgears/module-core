<?php
namespace cmsgears\core\common\actions\comment;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\ModelComment;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Create adds a comment, review or testimonial for a model using ModelComment resource.
 *
 * The controller must provide appropriate model service having model class, table and type defined for the base model.
 */
class Create extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $typed 		= true;

	public $parent 		= true;

	public $status		= ModelComment::STATUS_NEW;
	public $modelType	= null;

	public $setUser		= true;

	/**
	 * A comment can be created with or without scenario. The possible scenarios are - comment, review and testimonial.
	 * Controller must specify the scenario based on the type of comment.
	 */
	public $scenario;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CreateComment -------------------------

	public function run() {

		if( isset( $this->model ) ) {

			$modelCommentService	= Yii::$app->factory->get( 'modelCommentService' );

			$user				= Yii::$app->user->getIdentity();
			$parent				= $this->model;

			$modelClass			= $modelCommentService->getModelClass();
			$model				= new $modelClass;
			$model->status		= $this->status;
			$model->type		= $this->modelType;

			$model->parentId	= $this->model->id;
			$model->parentType	= $this->parentType;

			// To set name and email in case user is logged in. The same details can be fetched from user table using createdBy column.
			if( isset( $user ) && $this->setUser ) {

				$model->name	= $user->getName();
				$model->email	= $user->email;
			}

			if( isset( $this->scenario ) ) {

				$model->scenario	= $this->scenario;
			}

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				if( $modelCommentService->create( $model ) ) {

					// Trigger Ajax Success
					return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
				}
			}

			// Generate Validation Errors
			$errors = AjaxUtil::generateErrorMessage( $model );

			// Trigger Ajax Failure
			return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}

		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
