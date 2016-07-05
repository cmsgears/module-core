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
use cmsgears\core\common\models\entities\Role;

abstract class RoleController extends CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $type;

	protected $permissionService;

	protected $modelHierarchyService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		$this->setViewPath( '@cmsgears/module-core/admin/views/role' );

		$this->crudPermission 	= CoreGlobal::PERM_RBAC;
		$this->modelService		= Yii::$app->factory->get( 'roleService' );
		$this->type				= CoreGlobal::TYPE_SYSTEM;

		$this->permissionService		= Yii::$app->factory->get( 'permissionService' );
		$this->modelHierarchyService	= Yii::$app->factory->get( 'modelHierarchyService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// RoleController ------------------------

	public function actionAll() {

		$dataProvider = $this->modelService->getPageByType( $this->type );

	    return $this->render( 'all', [
	         'dataProvider' => $dataProvider
	    ]);
	}

	public function actionCreate() {

		$modelClass		= $this->modelService->getModelClass();
		$model			= new $modelClass;
		$model->type 	= $this->type;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$role				= $this->modelService->create( $model );

			$binder 			= new Binder();
			$binder->binderId	= $role->id;

			$binder->load( Yii::$app->request->post(), 'Binder' );

			$this->modelService->bindPermissions( $binder );

			/*
			$binder->load( Yii::$app->request->post(), 'Children' );

			$this->modelHierarchyService->assignChildren( CoreGlobal::TYPE_ROLE, $binder );
			*/

			return $this->redirect( $this->returnUrl );
		}

		$roleMap		= $this->modelService->getIdNameMapByType( $this->type );
		$permissions	= $this->permissionService->getIdNameListByType( $this->type );

    	return $this->render( 'create', [
    		'model' => $model,
    		'roleMap' => $roleMap,
    		'permissions' => $permissions
    	]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model		= $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() )  && $model->validate() ) {

				$role				= $this->modelService->update( $model );

				$binder 			= new Binder();
				$binder->binderId	= $model->id;

				$binder->load( Yii::$app->request->post(), 'Binder' );

				$this->modelService->bindPermissions( $binder );

				return $this->redirect( $this->returnUrl );
			}

			$roleMap		= $this->modelService->getIdNameMapByType( $this->type, [ 'prepend' => [ [ 'id' => 0, 'name' => 'Choose Role' ] ] ] );
			$permissions	= $this->permissionService->getIdNameListByType( $this->type );

	    	return $this->render( 'update', [
	    		'model' => $model,
	    		'roleMap' => $roleMap,
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

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) ) {

				$this->modelService->delete( $model );

				return $this->redirect( $this->returnUrl );
			}

			$roleMap		= $this->modelService->getIdNameMapByType( $this->type, [ 'prepend' => [ [ 'id' => 0, 'name' => 'Choose Role' ] ] ] );
			$permissions	= $this->permissionService->getIdNameListByType( $this->type );

	    	return $this->render( 'delete', [
	    		'model' => $model,
	    		'roleMap' => $roleMap,
	    		'permissions' => $permissions
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>