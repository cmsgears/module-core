<?php
namespace cmsgears\core\frontend\actions\comment;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\mappers\ModelComment;

use cmsgears\core\common\services\mappers\ModelCommentService;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * CreateComment can be used to create a comment for a model.
 */
class CreateComment extends \yii\base\Action {

	// Variables ---------------------------------------------------

	// Constants/Statics --

	// Public -------------

	public $scenario;

	// Private/Protected --

	// Constructor and Initialisation ------------------------------

    public function init() {

		// Do Init
    }

	// Instance Methods --------------------------------------------

	// CreateComment ---------------------

    public function run() {

        $model            	= new ModelComment();
        $model->status    	= ModelComment::STATUS_NEW;
		$model->scenario	= $this->scenario;

        if( $model->load( Yii::$app->request->post(), 'ModelComment' ) && $model->validate() ) {

            ModelCommentService::create( $model );

            // Trigger Ajax Success
            return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
        }

        // Generate Errors
        $errors = AjaxUtil::generateErrorMessage( $model );

        // Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
    }
}

?>