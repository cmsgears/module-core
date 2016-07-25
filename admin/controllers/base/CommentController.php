<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

abstract class CommentController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $commentUrl;

	// Protected --------------

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
                	'index'  => [ 'permission' => $this->crudPermission ],
	                'all'  => [ 'permission' => $this->crudPermission ],
	                'create'  => [ 'permission' => $this->crudPermission ],
	                'update'  => [ 'permission' => $this->crudPermission ],
	                'delete'  => [ 'permission' => $this->crudPermission ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index' => [ 'get' ],
	                'all'  => [ 'get' ],
	                'create'  => [ 'get', 'post' ],
	                'update'  => [ 'get', 'post' ],
	                'delete'  => [ 'get', 'post' ]
                ]
            ]
        ];
    }


	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

    // CommentController ---------------------

    public function actionAll( $pid = null ) {

        Url::remember( [ "$this->commentUrl/all?pid=$pid" ], $this->commentUrl );

		$model			= null;
        $dataProvider   = null;

        if( isset( $pid ) ) {

			$model			= $this->parentService->findById( $pid );
            $dataProvider 	= $this->modelService->getPageByParent( $model->id, $this->parentType, [ 'conditions' => [ 'type' => $this->commentType ] ] );
        }
		else {

			$dataProvider 	= $this->modelService->getPageByParentType( $this->parentType, [ 'conditions' => [ 'type' => $this->commentType ] ] );
		}

        return $this->render( 'all', [
             'dataProvider' => $dataProvider,
             'model' => $model
        ]);
    }

    public function actionCreate( $pid ) {

		$modelClass			= $this->modelService->getModelClass();
		$model				= new $modelClass;
        $model->parentId    = $pid;
        $model->parentType  = $this->parentType;
        $model->type        = $this->commentType;
		$parentModel    	= $this->parentService->findById( $pid );

        if( isset( $parentModel ) ) {

            $model->parentId    = $parentModel->id;
        }

		if( isset( $this->scenario ) ) {

			call_user_func_array( [ $model, 'setScenario' ], [ $this->scenario ] );
		}

        if( $model->load( Yii::$app->request->post(), $model->getClassName() )  && $model->validate() ) {

            $this->modelService->create( $model );

            return $this->redirect( $this->returnUrl );
        }

        return $this->render( 'create', [
            'model' => $model
        ]);
    }

	public function actionUpdate( $id ) {

		// Find Model
		$model	= $this->modelService->getById( $id );

		// Update if exist
		if( isset( $model ) ) {

			if( isset( $this->scenario ) ) {

				call_user_func_array( [ $model, 'setScenario' ], [ $this->scenario ] );
			}

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->modelService->update( $model );

				return $this->redirect( $this->returnUrl );
			}

			// Render view
	    	return $this->render( 'update', [
	    		'model' => $model
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= $this->modelService->getById( $id );

		// Delete if exist
		if( isset( $model ) ) {

			if( isset( $this->scenario ) ) {

				call_user_func_array( [ $model, 'setScenario' ], [ $this->scenario ] );
			}

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				try {

			    	$this->modelService->delete( $model );

					return $this->redirect( $this->returnUrl );
			    }
			    catch( Exception $e ) {

				    throw new HttpException( 409,  Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
				}
			}

			// Render view
	    	return $this->render( 'delete', [
	    		'model' => $model
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
