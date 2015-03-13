<?php
namespace cmsgears\modules\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;
use cmsgears\modules\core\admin\config\AdminGlobalCore;

use cmsgears\modules\core\common\models\entities\Role;
use cmsgears\modules\core\common\models\entities\Permission;

use cmsgears\modules\core\admin\models\forms\PermissionBinderForm;

use cmsgears\modules\core\admin\services\RoleService;
use cmsgears\modules\core\admin\services\PermissionService;

use cmsgears\modules\core\admin\controllers\BaseController;

use cmsgears\modules\core\common\utilities\CodeGenUtil;
use cmsgears\modules\core\common\utilities\MessageUtil;

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
                'permissions' => [
	                'index'  => Permission::PERM_RBAC,
	                'all'   => Permission::PERM_RBAC,
	                'create' => Permission::PERM_RBAC,
	                'update' => Permission::PERM_RBAC,
	                'delete' => Permission::PERM_RBAC
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

		if( $model->load( Yii::$app->request->post( "Role"), "" )  && $model->validate() ) {

			if( RoleService::create( $model ) ) {

				$binder = new PermissionBinderForm();

				$binder->roleId	= $model->getId();
				$binder->load( Yii::$app->request->post( "Binder" ), "" );

				RoleService::bindPermissions( $binder );

				return $this->redirect( [ self::URL_ALL ] );
			}
		}

		$permissions	= PermissionService::getIdNameArrayList();

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
	
					$binder->roleId	= $model->getId();
					$binder->load( Yii::$app->request->post( "Binder" ), "" );
	
					RoleService::bindPermissions( $binder );
	
					$this->refresh();
				}
			}
	
			$permissions	= PermissionService::getIdNameArrayList();
	
	    	return $this->render('update', [
	    		'model' => $model,
	    		'permissions' => $permissions
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( MessageUtil::getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= RoleService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( isset($_POST) && count($_POST) > 0 ) {
	
				if( RoleService::delete( $model ) ) {
		
					return $this->redirect( [ self::URL_ALL ] );
				}
			}

			$permissions	= PermissionService::getIdNameArrayList();

	    	return $this->render('delete', [
	    		'model' => $model,
	    		'permissions' => $permissions
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( MessageUtil::getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>