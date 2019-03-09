<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use Yii;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * CommentController provides actions specific to comment model.
 *
 * @since 1.0.0
 */
abstract class CommentController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $title;

	public $parentUrl;

	public $urlKey;

	public $commentType;

	// Protected --------------

	protected $parentType;

	protected $parentService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Views
		$this->setViewPath( '@cmsgears/module-core/admin/views/comment' );

		// Permission
		$this->crudPermission = CoreGlobal::PERM_CORE;

		// Config
		$this->apixBase = 'core/comment';
		$this->title	= 'Comment';

		// Services
		$this->modelService = Yii::$app->factory->get( 'modelCommentService' );

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
					'index' => [ 'permission' => $this->crudPermission ],
					'all' => [ 'permission' => $this->crudPermission ],
					'create' => [ 'permission' => $this->crudPermission ],
					'update' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
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

	public function actionIndex() {

		return $this->redirect( 'all' );
	}

	public function actionAll( $pid = null ) {

		Url::remember( Yii::$app->request->getUrl(), $this->urlKey );

		$modelClass	= $this->modelService->getModelClass();

		$parent = null;

		$dataProvider	= null;
        $commentTable	= $this->modelService->getModelTable();

		if( isset( $pid ) ) {

			$parent = $this->parentService->findById( $pid );

			$dataProvider = $this->modelService->getPageByParent( $parent->id, $this->parentType, [ 'conditions' => [ "$commentTable.type" => $this->commentType ] ] );
		}
		else {

			$dataProvider = $this->modelService->getPageByParentType( $this->parentType, [ 'conditions' => [ "$commentTable.type" => $this->commentType ] ] );
		}

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'statusMap' => $modelClass::$statusMap,
			'parent' => $parent
		]);
	}

	public function actionCreate( $pid ) {

		$modelClass	= $this->modelService->getModelClass();

		$model = new $modelClass;

		$model->siteId		= Yii::$app->core->getSiteId();
		$model->parentId	= $pid;
		$model->parentType	= $this->parentType;
		$model->type		= $this->commentType;
		$parentModel		= $this->parentService->findById( $pid );

		if( isset( $parentModel ) ) {

			$model->parentId = $parentModel->id;
		}

		if( isset( $this->scenario ) ) {

			call_user_func_array( [ $model, 'setScenario' ], [ $this->scenario ] );
		}

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->create( $model, [ 'admin' => true ] );

			if( isset( $pid ) ) {

				return $this->redirect( "all?pid=$pid" );
			}
			else {

				return $this->redirect( 'all' );
			}
		}

		return $this->render( 'create', [
			'model' => $model,
			'title' => $this->title,
			'statusMap' => $modelClass::$statusMap
		]);
	}

	public function actionUpdate( $id ) {

		$modelClass	= $this->modelService->getModelClass();

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update if exist
		if( isset( $model ) ) {

			if( isset( $this->scenario ) ) {

				call_user_func_array( [ $model, 'setScenario' ], [ $this->scenario ] );
			}

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->update( $model, [ 'admin' => true ] );

				return $this->redirect( $this->returnUrl );
			}

			// Render view
			return $this->render( 'update', [
				'model' => $model,
				'title' => $this->title,
				'statusMap' => $modelClass::$statusMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		$modelClass	= $this->modelService->getModelClass();

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
				'model' => $model,
				'title' => $this->title,
				'statusMap' => $modelClass::$statusMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
