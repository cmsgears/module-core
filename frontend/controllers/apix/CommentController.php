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
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'delete' => [ 'permission' => CoreGlobal::PERM_USER ],
	                'approve' => [ 'permission' => CoreGlobal::PERM_USER ], 
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => [ 'post' ],
                    'approve' => [ 'post' ],
                    'delete' => [ 'post' ], 
                ]
            ]
        ];
    }

	// ModelCommentController
 	public function actionCreate() {

		$model                = new ModelComment();
		$parentType           = $this->parentType;		
		$model->parentId      = $this->parentId;
		$model->parentType    = $this->parentType;
        $model->type          = $this->commentType;
		$model->ip            = Yii::$app->request->getUserIP();	
		$model->status        = ModelComment::STATUS_NEW;
		
		if( $model->load( Yii::$app->request->post(), 'ModelComment' ) && $model->validate()  ) {
			
			ModelCommentService::create( $model );
			
			return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
		}
		else {
			
			// Trigger Ajax Failure
			$errors = AjaxUtil::generateErrorMessage( $model );
        	return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}
 	}
	
	public function actionApprove( $id ) {
		
		$model	= ModelCommentService::findById( $id );
		
		if( isset( $model ) ) {
			
			ModelCommentService::updateStatus( $model, ModelComment::STATUS_APPROVED );
			return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
		}
	}
	
	public function actionDelete( $id ) {
		
		$model	= ModelCommentService::findById( $id );
		
		if( isset( $model ) ) {
			
			ModelCommentService::delete( $model );
			return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
		}
	}
}

?>