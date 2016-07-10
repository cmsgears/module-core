<?php
namespace cmsgears\core\common\actions\comment;

// Yii Imports
use \Yii;
use yii\base\InvalidConfigException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * CreateComment adds a comment for a model using ModelComment resource.
 *
 * The controller must provide appropriate model service having model class, table and type defined for the base model.
 */
class CreateComment extends \cmsgears\core\common\base\Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $captcha	= true;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// BindCategories ------------------------

    public function run() {

		$modelCommentService	= Yii::$app->factory->get( 'modelCommentService' );

		$user				= Yii::$app->user->getIdentity();

		$modelClass			= $modelCommentService->getModelClass();
		$model				= new $modelClass;
        $model->status		= ModelComment::STATUS_NEW;
		$model->parentType	= $this->modelService->getParentType();

		if( $captcha ) {

			$model->scenario	= 'captcha';
		}

        if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

            $modelCommentService->create( $model );

            // Trigger Ajax Success
            return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
        }

        // Generate Validation Errors
        $errors = AjaxUtil::generateErrorMessage( $model );

        // Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
    }
}
