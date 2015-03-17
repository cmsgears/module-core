<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\admin\config\AdminGlobalCore;

use cmsgears\core\common\models\entities\Permission;

use cmsgears\core\admin\models\forms\RoleBinderForm;

use cmsgears\core\admin\services\PermissionService;
use cmsgears\core\admin\services\RoleService;

use cmsgears\core\admin\controllers\BaseController;

use cmsgears\core\common\utilities\CodeGenUtil;
use cmsgears\core\common\utilities\MessageUtil;

class PermissionController extends BaseController {

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
                'permissions' => [
	                'index'  => Permission::PERM_RBAC,
	                'all'   => Permission::PERM_RBAC,
	                'matrix' => Permission::PERM_RBAC,
	                'create' => Permission::PERM_RBAC,
	                'update' => Permission::PERM_RBAC,
	                'delete' => Permission::PERM_RBAC
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index'  => ['get'],
	                'all'    => ['get'],
	                'matrix' => ['get'],
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

		$pagination = PermissionService::getPagination();

	    return $this->render('all', [
	         'page' => $pagination['page'],
	         'pages' => $pagination['pages'],
	         'total' => $pagination['total']
	    ]);
	}

	public function actionMatrix() {

		$pagination = PermissionService::getPagination();

		$allRoles	= RoleService::getIdNameList();

	    return $this->render('matrix', [
	         'page' => $pagination['page'],
	         'pages' => $pagination['pages'],
	         'total' => $pagination['total'],
	         'allRoles' => $allRoles
	    ]);
	}

	public function actionCreate() {

		$model	= new Permission();

		$model->setScenario( "create" );

		if( $model->load( Yii::$app->request->post( "Permission" ), "" )  && $model->validate() ) {

			if( PermissionService::create( $model ) ) {

				$binder = new RoleBinderForm();

				$binder->permissionId	= $model->id;
				$binder->load( Yii::$app->request->post( "Binder" ), "" );

				PermissionService::bindRoles( $binder );

				return $this->redirect( "all" );
			}
		}
		
		$roles	= RoleService::getIdNameList();

    	return $this->render('create', [
    		'model' => $model,
    		'roles' => $roles
    	]);
	}

	public function actionUpdate( $id ) {

		// Find Model		
		$model	= PermissionService::findById( $id );
		
		// Update/Render if exist
		if( isset( $model ) ) {

			$model->setScenario( "update" );
	
			if( $model->load( Yii::$app->request->post( "Permission" ), "" )  && $model->validate() ) {
	
				if( PermissionService::update( $model ) ) {
	
					$binder = new RoleBinderForm();
	
					$binder->permissionId	= $model->id;
					$binder->load( Yii::$app->request->post( "Binder" ), "" );
	
					PermissionService::bindRoles( $binder );
	
					$this->refresh();
				}
			}
	
			$roles	= RoleService::getIdNameList();
	
	    	return $this->render('update', [
	    		'model' => $model,
	    		'roles' => $roles
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( MessageUtil::getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= PermissionService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( isset($_POST) && count($_POST) > 0 ) {

				if( PermissionService::delete( $model ) ) {

					return $this->redirect( "all" );
				}
			}

			$roles	= RoleService::getIdNameList();
	
	    	return $this->render('delete', [
	    		'model' => $model,
	    		'roles' => $roles
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( MessageUtil::getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>