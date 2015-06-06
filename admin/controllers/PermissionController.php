<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Permission;
use cmsgears\core\common\models\forms\Binder;

use cmsgears\core\admin\services\PermissionService;
use cmsgears\core\admin\services\RoleService;

use cmsgears\core\admin\controllers\BaseController;

class PermissionController extends BaseController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'index'  => [ 'permission' => CoreGlobal::PERM_IDENTITY_RBAC ],
	                'all'   => [ 'permission' => CoreGlobal::PERM_IDENTITY_RBAC ],
	                'matrix' => [ 'permission' => CoreGlobal::PERM_IDENTITY_RBAC ],
	                'create' => [ 'permission' => CoreGlobal::PERM_IDENTITY_RBAC ],
	                'update' => [ 'permission' => CoreGlobal::PERM_IDENTITY_RBAC ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_IDENTITY_RBAC ]
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

	// RoleController --------------------

	public function actionIndex() {

		$this->redirect( 'all' );
	}

	public function actionAll() {

		$dataProvider = PermissionService::getPagination();

	    return $this->render('all', [
	         'dataProvider' => $dataProvider
	    ]);
	}

	public function actionMatrix() {

		$dataProvider 	= PermissionService::getPagination();
		$rolesList		= RoleService::getIdNameList();

	    return $this->render('matrix', [
	         'dataProvider' => $dataProvider,
	         'rolesList' => $rolesList
	    ]);
	}

	public function actionCreate() {

		$model	= new Permission();

		$model->setScenario( "create" );

		if( $model->load( Yii::$app->request->post(), "Permission" )  && $model->validate() ) {

			if( PermissionService::create( $model ) ) {

				$binder 			= new Binder();
				$binder->binderId	= $model->id;

				$binder->load( Yii::$app->request->post(), "Binder" );

				PermissionService::bindRoles( $binder );

				$this->redirect( [ "all" ] );
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

			if( $model->load( Yii::$app->request->post(), "Permission" )  && $model->validate() ) {
	
				if( PermissionService::update( $model ) ) {
	
					$binder 			= new Binder();
					$binder->binderId	= $model->id;
	
					$binder->load( Yii::$app->request->post(), "Binder" );
	
					PermissionService::bindRoles( $binder );
	
					$this->redirect( [ "all" ] );
				}
			}
	
			$roles	= RoleService::getIdNameList();
	
	    	return $this->render('update', [
	    		'model' => $model,
	    		'roles' => $roles
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= PermissionService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), "Permission" ) ) {

				if( PermissionService::delete( $model ) ) {

					$this->redirect( [ "all" ] );
				}
			}

			$roles	= RoleService::getIdNameList();
	
	    	return $this->render('delete', [
	    		'model' => $model,
	    		'roles' => $roles
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>