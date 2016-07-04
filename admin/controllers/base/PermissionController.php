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

		$this->crudPermission 	= CoreGlobal::PERM_RBAC;
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

		$behaviors[ 'rbac' ][ 'actions' ][ 'matrix' ] 	= [ 'permission' => CoreGlobal::PERM_RBAC ];

		$behaviors[ 'verbs' ][ 'actions' ][ 'matrix' ] 	= [ 'get' ];

		return $behaviors;
    }

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// PermissionController ------------------

	public function actionAll() {

		$dataProvider = $this->modelService->getPageByType( $this->type );

	    return $this->render( 'all', [
	         'dataProvider' => $dataProvider
	    ]);
	}

	public function actionMatrix() {

		$dataProvider 	= $this->modelService->getPageByType( $this->type );
		$rolesList		= $this->roleService->getIdNameListByType( $this->type );

	    return $this->render( 'matrix', [
			'dataProvider' => $dataProvider,
			'rolesList' => $rolesList
	    ]);
	}

	public function actionCreate() {

		$modelClass		= $this->modelService->getModelClass();
		$model			= new $modelClass;
		$model->type 	= $this->type;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$permission 		= $this->modelService->create( $model );

			$binder 			= new Binder();
			$binder->binderId	= $permission->id;

			$binder->load( Yii::$app->request->post(), 'Binder' );

			$this->modelService->bindRoles( $binder );

			return $this->redirect( $this->returnUrl );
		}

		$permissionMap	= $this->modelService->getIdNameMapByType( $this->type, [ 'prepend' => [ [ 'id' => 0, 'name' => 'Choose Permission' ] ] ] );
		$roles			= $this->roleService->getIdNameListByType( $this->type );

    	return $this->render( 'create', [
    		'model' => $model,
    		'permissionMap' => $permissionMap,
    		'roles' => $roles
    	]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model		= $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), 'Permission' )  && $model->validate() ) {

				$permission 		= $this->modelService->update( $model );

				$binder 			= new Binder();
				$binder->binderId	= $permission->id;

				$binder->load( Yii::$app->request->post(), 'Binder' );

				$this->modelService->bindRoles( $binder );

				return $this->redirect( $this->returnUrl );
			}

			$permissionMap	= $this->modelService->getIdNameMapByType( $this->type, [ 'prepend' => [ [ 'id' => 0, 'name' => 'Choose Permission' ] ] ] );
			$roles			= $this->roleService->getIdNameListByType( $this->type );

	    	return $this->render( 'update', [
	    		'model' => $model,
	    		'permissionMap' => $permissionMap,
	    		'roles' => $roles
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model		= $this->modelService->getById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), 'Permission' ) ) {

				$this->modelService->delete( $model );

				return $this->redirect( $this->returnUrl );
			}

			$permissionMap	= $this->modelService->getIdNameMapByType( $this->type, [ 'prepend' => [ [ 'id' => 0, 'name' => 'Choose Permission' ] ] ] );
			$roles			= $this->roleService->getIdNameListByType( $this->type );

	    	return $this->render( 'delete', [
	    		'model' => $model,
	    		'permissionMap' => $permissionMap,
	    		'roles' => $roles
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>