<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\forms\Binder;

use cmsgears\core\admin\services\RoleService;
use cmsgears\core\admin\services\PermissionService;

use cmsgears\core\admin\controllers\BaseController;

abstract class BaseRoleController extends BaseController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// BaseRoleController -----------------

	public function actionAll( $type = null ) {

		$dataProvider = RoleService::getPaginationByType( $type );

	    return $this->render( 'all', [
			'dataProvider' => $dataProvider
	    ]);
	}

	public function actionCreate( $type = null ) {

		$model				= new Role();
		$this->returnUrl	= Url::previous( 'roles' );

		$model->setScenario( 'create' );

		if( isset( $type ) ) {

			$model->type = $type;
		}

		if( $model->load( Yii::$app->request->post(), 'Role' )  && $model->validate() ) {

			if( RoleService::create( $model ) ) {

				$binder 			= new Binder();
				$binder->binderId	= $model->id;

				$binder->load( Yii::$app->request->post(), 'Binder' );

				RoleService::bindPermissions( $binder );

				return $this->redirect( $this->returnUrl );
			}
		}

		$permissions	= PermissionService::getIdNameListByType( $type );

    	return $this->render( 'create', [
    		'model' => $model,
    		'permissions' => $permissions
    	]);
	}

	public function actionUpdate( $id, $returnUrl, $type = null ) {

		// Find Model
		$model				= RoleService::findById( $id );
		$this->returnUrl	= Url::previous( 'roles' );

		// Update/Render if exist
		if( isset( $model ) ) {

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'Role' )  && $model->validate() ) {

				if( RoleService::update( $model ) ) {

					$binder 			= new Binder();
					$binder->binderId	= $model->id;

					$binder->load( Yii::$app->request->post(), 'Binder' );

					RoleService::bindPermissions( $binder );
	
					$this->redirect( $this->returnUrl );
				}
			}

			$permissions	= PermissionService::getIdNameListByType( $type );

	    	return $this->render( 'update', [
	    		'model' => $model,
	    		'permissions' => $permissions
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $returnUrl, $type = null ) {

		// Find Model
		$model				= RoleService::findById( $id );
		$this->returnUrl	= Url::previous( 'roles' );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), 'Role' ) ) {

				if( RoleService::delete( $model ) ) {

					return $this->redirect( $this->returnUrl );
				}
			}

			$permissions	= PermissionService::getIdNameListByType( $type );

	    	return $this->render( 'delete', [
	    		'model' => $model,
	    		'permissions' => $permissions
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>