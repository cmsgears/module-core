<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Role;

use cmsgears\core\admin\models\forms\PermissionBinderForm;

use cmsgears\core\admin\services\RoleService;
use cmsgears\core\admin\services\PermissionService;

use cmsgears\core\admin\controllers\BaseController;

class RoleController extends BaseController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'index'  => [ 'permission' => CoreGlobal::PERM_RBAC ],
	                'all'   => [ 'permission' => CoreGlobal::PERM_RBAC ],
	                'create' => [ 'permission' => CoreGlobal::PERM_RBAC ],
	                'update' => [ 'permission' => CoreGlobal::PERM_RBAC ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_RBAC ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index'  => ['get'],
	                'all'   => ['get'],
	                'create' => ['get', 'post'],
	                'update' => ['get', 'post'],
	                'delete' => ['get', 'post']
                ]
            ]
        ];
    }

	// RoleController

	public function actionIndex() {

		$this->redirect( 'all' );
	}

	public function actionAll() {

		$pagination = RoleService::getPagination();

	    return $this->render('all', [
	         'page' => $pagination['page'],
	         'pages' => $pagination['pages'],
	         'total' => $pagination['total']
	    ]);
	}

	public function actionCreate() {

		$model	= new Role();

		$model->setScenario( "create" );

		if( $model->load( Yii::$app->request->post( "Role" ), "" )  && $model->validate() ) {

			if( RoleService::create( $model ) ) {

				$binder = new PermissionBinderForm();

				$binder->roleId	= $model->id;
				$binder->load( Yii::$app->request->post( "Binder" ), "" );

				RoleService::bindPermissions( $binder );

				return $this->redirect( "all" );
			}
		}

		$permissions	= PermissionService::getIdNameList();

    	return $this->render('create', [
    		'model' => $model,
    		'permissions' => $permissions
    	]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model	= RoleService::findById( $id );
		
		// Update/Render if exist
		if( isset( $model ) ) {

			$model->setScenario( "update" );
	
			if( $model->load( Yii::$app->request->post( "Role"), "" )  && $model->validate() ) {
	
				if( RoleService::update( $model ) ) {
	
					$binder = new PermissionBinderForm();
	
					$binder->roleId	= $model->id;
					$binder->load( Yii::$app->request->post( "Binder" ), "" );
	
					RoleService::bindPermissions( $binder );
	
					$this->refresh();
				}
			}
	
			$permissions	= PermissionService::getIdNameList();
	
	    	return $this->render('update', [
	    		'model' => $model,
	    		'permissions' => $permissions
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= RoleService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( isset($_POST) && count($_POST) > 0 ) {
	
				if( RoleService::delete( $model ) ) {
		
					return $this->redirect( "all" );
				}
			}

			$permissions	= PermissionService::getIdNameList();

	    	return $this->render('delete', [
	    		'model' => $model,
	    		'permissions' => $permissions
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>