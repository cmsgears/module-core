<?php
namespace cmsgears\core\frontend\actions\comment;

// Yii Imports
use \yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\ModelComment;

use cmsgears\core\frontend\services\ModelCommentService;

use cmsgears\core\common\utilities\AjaxUtil;

trait CreateTrait {

    public function actionCreate() {

        $model            = new ModelComment();
        $model->status    = ModelComment::STATUS_NEW;

        if( $this->scenario != null ) {

            $model->scenario    = $this->scenario;
        }

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