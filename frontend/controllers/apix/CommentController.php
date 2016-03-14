<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\ModelComment;

use cmsgears\core\frontend\services\ModelCommentService;

use cmsgears\core\common\utilities\AjaxUtil;

class CommentController extends \cmsgears\core\admin\controllers\base\Controller {

    // Constructor and Initialisation ------------------------------

    public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
    }

    // Instance Methods --------------------------------------------

    // yii\base\Component

    public function behaviors() {

        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => [ 'post' ]
                ]
            ]
        ];
    }

    // CommentController

    public function actionCreate() {

        $model            = new ModelComment();
        $model->status    = ModelComment::STATUS_NEW;

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