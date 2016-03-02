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

    protected $parentId;
    protected $parentType;
    protected $commentType;

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->commentType	= ModelComment::TYPE_COMMENT;
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'approve' => [ 'permission' => CoreGlobal::PERM_USER ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_USER ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => [ 'post' ],
                    'approve' => [ 'post' ],
                    'delete' => [ 'post' ] 
                ]
            ]
        ];
    }

	// CommentController

 	public function actionCreate() {

		$model                = new ModelComment();

		$model->parentId      = $this->parentId;
		$model->parentType    = $this->parentType;
        $model->type          = $this->commentType;
		$model->status        = ModelComment::STATUS_NEW;

		if( $model->load( Yii::$app->request->post(), 'ModelComment' ) && $model->validate() ) {

			ModelCommentService::create( $model );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
		}
		else {

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $model );
			
			// Trigger Ajax Failure
        	return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}
 	}

	public function actionApprove( $id ) {

		$model	= ModelCommentService::findById( $id );

		if( isset( $model ) ) {

			ModelCommentService::updateStatus( $model, ModelComment::STATUS_APPROVED );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
		}

		// Trigger Ajax Failure
    	return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}

	public function actionDelete( $id ) {

		$model	= ModelCommentService::findById( $id );

		if( isset( $model ) ) {

			ModelCommentService::delete( $model );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
		}

		// Trigger Ajax Failure
    	return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}
}

?>