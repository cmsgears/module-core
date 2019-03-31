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
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\Binder;

/**
 * RoleController provides actions specific to role model.
 *
 * @since 1.0.0
 */
abstract class RoleController extends CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $type;
	protected $system;

	protected $permissionService;

	protected $hierarchyService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Views
		$this->setViewPath( '@cmsgears/module-core/admin/views/role' );

		// Permission
		$this->crudPermission = CoreGlobal::PERM_RBAC;

		// Config
		$this->type		= CoreGlobal::TYPE_SYSTEM;
		$this->apixBase	= 'core/role';

		// Services
		$this->modelService = Yii::$app->factory->get( 'roleService' );

		$this->permissionService	= Yii::$app->factory->get( 'permissionService' );
		$this->hierarchyService		= Yii::$app->factory->get( 'modelHierarchyService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// RoleController ------------------------

	public function actionAll( $config = [] ) {

		$dataProvider = $this->modelService->getPageByType( $this->type );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider
		]);
	}

	public function actionCreate( $config = [] ) {

		$model = $this->modelService->getModelObject();

		$model->type = $this->type;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->create( $model );

			$binder	= new Binder( [ 'binderId' => $this->model->id ] );

			$binder->load( Yii::$app->request->post(), 'Binder' );

			$this->modelService->bindPermissions( $binder );

			return $this->redirect( $this->returnUrl );
		}

		$permissions	= $this->permissionService->getIdNameListByType( $this->type );
		$spermissions	= $this->system ? $this->permissionService->getIdNameListByType( CoreGlobal::TYPE_SYSTEM ) : [];

		return $this->render( 'create', [
			'model' => $model,
			'type' => $this->type,
			'permissions' => $permissions,
			'spermissions' => $spermissions
		]);
	}

	public function actionUpdate( $id, $config = [] ) {

		// Find Model
		$model	= $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() )	&& $model->validate() ) {

				$this->modelService->update( $model );

				$model->refresh();

				$binder	= new Binder( [ 'binderId' => $model->id ] );

				$binder->load( Yii::$app->request->post(), 'Binder' );

				$this->modelService->bindPermissions( $binder );

				return $this->redirect( $this->returnUrl );
			}

			$permissions	= $this->permissionService->getIdNameListByType( $this->type );
			$spermissions	= $this->system ? $this->permissionService->getIdNameListByType( CoreGlobal::TYPE_SYSTEM ) : [];

			return $this->render( 'update', [
				'model' => $model,
				'type' => $this->type,
				'permissions' => $permissions,
				'spermissions' => $spermissions
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $config = [] ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) ) {

				$this->modelService->delete( $model );

				return $this->redirect( $this->returnUrl );
			}

			$permissions	= $this->permissionService->getIdNameListByType( $this->type );
			$spermissions	= $this->system ? $this->permissionService->getIdNameListByType( CoreGlobal::TYPE_SYSTEM ) : [];

			return $this->render( 'delete', [
				'model' => $model,
				'type' => $this->type,
				'permissions' => $permissions,
				'spermissions' => $spermissions
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
