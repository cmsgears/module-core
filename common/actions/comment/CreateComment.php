<?php
namespace cmsgears\core\common\actions\comment;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\ModelComment;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * CreateComment adds a comment for a model using ModelComment resource.
 *
 * The controller must provide appropriate model service having model class, table and type defined for the base model.
 */
class CreateComment extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------


	public $status	= ModelComment::STATUS_NEW;
	public $type	= ModelComment::TYPE_COMMENT;

	public $setUser	= true;

	public $captcha	= true;

	public $scenario;

	// Protected --------------

	protected $typed = true;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		if( $this->captcha ) {

			$this->scenario	= 'captcha';
		}
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CreateComment -------------------------

    public function run() {

		$modelCommentService	= Yii::$app->factory->get( 'modelCommentService' );

		$user				= Yii::$app->user->getIdentity();
		$parent				= $this->model;

		$modelClass			= $modelCommentService->getModelClass();
		$model				= new $modelClass;
        $model->status		= $this->status;
		$model->type		= $this->type;

		$model->parentId	= $this->model->id;
		$model->parentType	= $this->modelService->getParentType();

		// To set name and email in case user is logged in. The same details can be fetched from user table using createdBy column.
		if( isset( $user ) && $this->setUser ) {

			$model->name	= $user->getName();
			$model->email	= $user->getEmail();
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
}
