<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\mappers\ModelComment;

use cmsgears\core\admin\services\mappers\ModelCommentService;

abstract class CommentController extends Controller {

    public $returnUrl;

    public $commentType;
    public $parentType;

    public $modelService;
    public $rememberUrl;

	// Constructor and Initialisation ------------------------------

    public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->setViewPath( '@cmsgears/module-core/admin/views/comment' );
    }

    // Instance Methods --------------------------------------------

    // yii\base\Component ----------------

    // Default RBAC and Verbs
    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
                    'all' => [ 'permission' => CoreGlobal::PERM_CORE ],
                    'create' => [ 'permission' => CoreGlobal::PERM_CORE ],
                    'update' => [ 'permission' => CoreGlobal::PERM_CORE ],
                    'delete' => [ 'permission' => CoreGlobal::PERM_CORE ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'all' => [ 'get' ],
                    'create' => [ 'get', 'post' ],
                    'update' => [ 'get', 'post' ],
                    'delete' => [ 'get', 'post' ]
                ]
            ]
        ];
    }

    // CommentController ---------------------

    public function actionAll( $slug = null ) {

        Url::remember( [ "$this->rememberUrl/all" ], $this->rememberUrl );

        $dataProvider   = null;
        $model          = null;

        if( isset( $slug ) ) {

            Url::remember( [ "$this->rememberUrl/all?slug=$slug" ], $this->rememberUrl );

            $model    = $this->modelService->findBySlug( $slug );

            if( isset( $model ) ) {

                $dataProvider = ModelCommentService::getPaginationByType( $this->commentType, [ 'conditions' => [ 'parentType' => $this->parentType, 'parentId' => $model->id ] ] );
            }
        }
        else {

            $dataProvider = ModelCommentService::getPaginationByType( $this->commentType, [ 'conditions' => [ 'parentType' => $this->parentType ] ] );
        }

        return $this->render( 'all', [
             'dataProvider' => $dataProvider,
             'model' => $model
        ]);
    }

    public function actionCreate( $slug = null ) {

        // Find Model
        $model  = new ModelComment();

        $model->parentId    = Yii::$app->cmgCore->siteId;
        $model->parentType  = $this->parentType;
        $model->type        = $this->commentType;

        if( isset( $slug ) ) {

            $parentModel    = $this->modelService->findBySlug( $slug );

            if( isset( $parentModel ) ) {

                $model->parentId    = $parentModel->id;
            }
        }

        if( $model->load( Yii::$app->request->post(), 'ModelComment' )  && $model->validate() ) {

            ModelCommentService::create( $model );

            return $this->redirect( $this->returnUrl );
        }

        return $this->render( 'create', [
            'model' => $model,
            'statusMap' => ModelComment::$statusMap
        ]);
    }

    public function actionUpdate( $id ) {

        // Find Model
        $model  = ModelCommentService::getById( $id );

        // Update/Render if exist
        if( isset( $model ) ) {

            if( $model->load( Yii::$app->request->post(), 'ModelComment' )  && $model->validate() ) {

                ModelCommentService::update( $model );

                return $this->redirect( $this->returnUrl );
            }

            return $this->render( 'update', [
                'model' => $model,
                'statusMap' => ModelComment::$statusMap
            ]);
        }

        // Model not found
        throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
    }

    public function actionDelete( $id ) {

        // Find Model
        $model  = ModelCommentService::getById( $id );

        // Update/Render if exist
        if( isset( $model ) ) {

            if( $model->load( Yii::$app->request->post(), 'ModelComment' )  && $model->validate() ) {

                ModelCommentService::delete( $model );

                return $this->redirect( $this->returnUrl );
            }

            return $this->render( 'delete', [
                'model' => $model,
                'statusMap' => ModelComment::$statusMap
            ]);
        }

        // Model not found
        throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
    }
}

?>