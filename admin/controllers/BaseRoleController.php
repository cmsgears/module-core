<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
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

		$dataProvider = null;

		if( isset( $type ) ) {

			$dataProvider = RoleService::getPaginationByType( $type );
		}
		else {

			$dataProvider = RoleService::getPagination();
		}

	    return $this->render( 'all', [
			'dataProvider' => $dataProvider
	    ]);
	}

	public function actionCreate( $returnUrl, $type = null ) {

		$model	= new Role();

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

				return $this->redirect( $returnUrl );
			}
		}

		$permissions	= PermissionService::getIdNameList( $type );

    	return $this->render( 'create', [
    		'returnUrl' => $returnUrl,
    		'model' => $model,
    		'permissions' => $permissions
    	]);
	}

	public function actionUpdate( $id, $returnUrl, $type = null ) {

		// Find Model
		$model	= RoleService::findById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'Role' )  && $model->validate() ) {

				if( RoleService::update( $model ) ) {

					$binder 			= new Binder();
					$binder->binderId	= $model->id;

					$binder->load( Yii::$app->request->post(), 'Binder' );

					RoleService::bindPermissions( $binder );
	
					$this->redirect( $returnUrl );
				}
			}

			$permissions	= PermissionService::getIdNameList( $type );

	    	return $this->render( 'update', [
	    		'returnUrl' => $returnUrl,
	    		'model' => $model,
	    		'permissions' => $permissions
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $returnUrl, $type = null ) {

		// Find Model
		$model	= RoleService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), 'Role' ) ) {

				if( RoleService::delete( $model ) ) {

					return $this->redirect( $returnUrl );
				}
			}

			$permissions	= PermissionService::getIdNameList( $type );

	    	return $this->render( 'delete', [
	    		'returnUrl' => $returnUrl,
	    		'model' => $model,
	    		'permissions' => $permissions
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>