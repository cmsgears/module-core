<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\ModelComment;

abstract class CommentController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $commentUrl;
	protected $parentType;
	protected $commentType;

	protected $parentService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		$this->setViewPath( '@cmsgears/module-core/admin/views/comment' );

		$this->crudPermission 	= CoreGlobal::PERM_CORE;
		$this->modelService		= Yii::$app->factory->get( 'modelCommentService' );

		// Notes: Configure sidebar, commentUrl, parentType, commentType, parentService and returnUrl exclusively in child classes
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->core->getRbacFilterClass(),
                'actions' => [
                    'all' => [ 'permission' => $this->crudPermission ],
                    'create' => [ 'permission' => $this->crudPermission ],
                    'update' => [ 'permission' => $this->crudPermission ],
                    'delete' => [ 'permission' => $this->crudPermission ]
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

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

    // CommentController ---------------------

    public function actionAll( $slug = null ) {

        Url::remember( [ "$this->commentUrl/all" ], $this->commentUrl );

        $dataProvider   = null;
        $model          = null;

        if( isset( $slug ) ) {

            Url::remember( [ "$this->commentUrl/all?slug=$slug" ], $this->commentUrl );

            $model    = $this->parentService->findBySlug( $slug );

            if( isset( $model ) ) {

                $dataProvider = $this->modelService->getPageByParent( $this->parentType, $model->id, [ 'conditions' => [ 'type' => $this->commentType ] ] );
            }
        }
        else {

            $dataProvider = $this->modelService->getPageByParentType( $this->parentType, [ 'conditions' => [ 'type' => $this->commentType ] ] );
        }

        return $this->render( 'all', [
             'dataProvider' => $dataProvider,
             'model' => $model
        ]);
    }

    public function actionCreate( $slug = null ) {

        // Find Model
        $model  = new ModelComment();

        $model->parentId    = Yii::$app->core->siteId;
        $model->parentType  = $this->parentType;
        $model->type        = $this->commentType;

        if( isset( $slug ) ) {

            $parentModel    = $this->parentService->findBySlug( $slug );

            if( isset( $parentModel ) ) {

                $model->parentId    = $parentModel->id;
            }
        }

        if( $model->load( Yii::$app->request->post(), 'ModelComment' )  && $model->validate() ) {

            $this->modelService->create( $model );

            return $this->redirect( $this->returnUrl );
        }

        return $this->render( 'create', [
            'model' => $model,
            'statusMap' => ModelComment::$statusMap
        ]);
    }

    public function actionUpdate( $id ) {

        // Find Model
        $model  = $this->modelService->getById( $id );

        // Update/Render if exist
        if( isset( $model ) ) {

            if( $model->load( Yii::$app->request->post(), 'ModelComment' )  && $model->validate() ) {

                $this->modelService->update( $model );

                return $this->redirect( $this->returnUrl );
            }

            return $this->render( 'update', [
                'model' => $model,
                'statusMap' => ModelComment::$statusMap
            ]);
        }

        // Model not found
        throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
    }

    public function actionDelete( $id ) {

        // Find Model
        $model  = $this->modelService->getById( $id );

        // Update/Render if exist
        if( isset( $model ) ) {

            if( $model->load( Yii::$app->request->post(), 'ModelComment' )  && $model->validate() ) {

                $this->modelService->delete( $model );

                return $this->redirect( $this->returnUrl );
            }

            return $this->render( 'delete', [
                'model' => $model,
                'statusMap' => ModelComment::$statusMap
            ]);
        }

        // Model not found
        throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
    }
}

?>