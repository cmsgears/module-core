<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\Binder;
use cmsgears\core\common\models\entities\Permission;

abstract class PermissionController extends CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $type;

	protected $roleService;

	protected $modelHierarchyService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->setViewPath( '@cmsgears/module-core/admin/views/permission' );

		// Permissions
		$this->crudPermission	= CoreGlobal::PERM_RBAC;
		
		// Services
		$this->modelService		= Yii::$app->factory->get( 'permissionService' );
		$this->type				= CoreGlobal::TYPE_SYSTEM;

		$this->roleService				= Yii::$app->factory->get( 'roleService' );
		$this->modelHierarchyService	= Yii::$app->factory->get( 'modelHierarchyService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		$behaviors	= parent::behaviors();

		$behaviors[ 'rbac' ][ 'actions' ][ 'matrix' ]	= [ 'permission' => CoreGlobal::PERM_RBAC ];

		$behaviors[ 'verbs' ][ 'actions' ][ 'matrix' ]	= [ 'get' ];

		return $behaviors;
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// PermissionController ------------------

	public function actionMatrix() {

		$dataProvider	= $this->modelService->getPageByType( $this->type );
		$rolesList		= $this->roleService->getIdNameListByType( $this->type );

		return $this->render( 'matrix', [
			'dataProvider' => $dataProvider,
			'rolesList' => $rolesList
		]);
	}

	public function actionAll() {

		$dataProvider = $this->modelService->getPageByType( $this->type, [ 'conditions' => [ 'group' => false ] ] );

		return $this->render( 'all', [
			 'dataProvider' => $dataProvider
		]);
	}

	public function actionGroups() {

		$dataProvider = $this->modelService->getPageByType( $this->type, [ 'conditions' => [ 'group' => true ] ] );

		return $this->render( 'groups/all', [
			 'dataProvider' => $dataProvider
		]);
	}

	public function actionCreate() {

		$modelClass		= $this->modelService->getModelClass();
		$model			= new $modelClass;
		$model->type	= $this->type;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$permission			= $this->modelService->create( $model );

			$binder				= new Binder();
			$binder->binderId	= $permission->id;

			$binder->load( Yii::$app->request->post(), 'Binder' );

			$this->modelService->bindRoles( $binder );

			if( $model->group ) {

				return $this->redirect( [ 'groups' ] );
			}

			return $this->redirect( "update?id=$model->id" );
		}

		$roles			= $this->roleService->getIdNameListByType( $this->type );
		//$permissions	= $this->modelService->getLeafIdNameListByType( $this->type );

		return $this->render( 'create', [
			'model' => $model,
			//'permissions' => $permissions,
			'roles' => $roles
		]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model		= $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), 'Permission' )  && $model->validate() ) {

				$permission			= $this->modelService->update( $model );

				$binder				= new Binder();
				$binder->binderId	= $model->id;

				$binder->load( Yii::$app->request->post(), 'Binder' );
				$this->modelService->bindRoles( $binder );

				if( $model->group ) {

					$binder->load( Yii::$app->request->post(), 'Children' );
					$this->modelHierarchyService->assignRootChildren( CoreGlobal::TYPE_PERMISSION, $binder );
				}

				if( $model->group ) {

					return $this->redirect( [ 'groups' ] );
				}

				return $this->redirect( "update?id=$model->id" );
			}

			$roles			= $this->roleService->getIdNameListByType( $this->type );
			$permissions	= $this->modelService->getLeafIdNameListByType( $this->type );

			return $this->render( 'update', [
				'model' => $model,
				'roles' => $roles,
				'permissions' => $permissions
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model		= $this->modelService->getById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), 'Permission' ) ) {

				if( $model->group ) {

					$this->modelHierarchyService->deleteByRootId( $model->id, CoreGlobal::TYPE_PERMISSION );

					return $this->redirect( [ 'groups' ] );
				}

				$this->modelService->delete( $model );

				return $this->redirect( $this->returnUrl );
			}

			$roles	= $this->roleService->getIdNameListByType( $this->type );

			return $this->render( 'delete', [
				'model' => $model,
				'roles' => $roles
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
