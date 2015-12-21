<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Permission;
use cmsgears\core\common\models\forms\Binder;

use cmsgears\core\admin\services\PermissionService;
use cmsgears\core\admin\services\RoleService;

abstract class PermissionController extends Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
		
		$this->returnUrl	= Url::previous( 'permissions' );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'all' => [ 'permission' => CoreGlobal::PERM_RBAC ],
	                'matrix' => [ 'permission' => CoreGlobal::PERM_RBAC ],
	                'create' => [ 'permission' => CoreGlobal::PERM_RBAC ],
	                'update' => [ 'permission' => CoreGlobal::PERM_RBAC ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_RBAC ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'all' => [ 'get' ],
	                'matrix' => [ 'get' ],
	                'create' => [ 'get', 'post' ],
	                'update' => [ 'get', 'post' ],
	                'delete' => [ 'get', 'post' ]
                ]
            ]
        ];
    }

	// BasePermissionController ----------

	public function actionAll( $type = null ) {

		$dataProvider = PermissionService::getPaginationByType( $type );

	    return $this->render( 'all', [
	         'dataProvider' => $dataProvider
	    ]);
	}

	public function actionMatrix( $type = null ) {

		$dataProvider 	= PermissionService::getPaginationByType( $type );
		$rolesList		= RoleService::getIdNameListByType( $type );

	    return $this->render( 'matrix', [
			'dataProvider' => $dataProvider,
			'rolesList' => $rolesList
	    ]);
	}

	public function actionCreate( $type = null ) {

		$model			= new Permission();
		$model->type 	= $type;

		$model->setScenario( 'create' );

		if( $model->load( Yii::$app->request->post(), 'Permission' )  && $model->validate() ) {

			if( PermissionService::create( $model ) ) {

				$binder 			= new Binder();
				$binder->binderId	= $model->id;

				$binder->load( Yii::$app->request->post(), 'Binder' );

				PermissionService::bindRoles( $binder );

				$this->redirect( $this->returnUrl );
			}
		}

		$roles	= RoleService::getIdNameListByType( $type );

    	return $this->render( 'create', [
    		'model' => $model,
    		'roles' => $roles
    	]);
	}

	public function actionUpdate( $id, $type = null ) {

		// Find Model		
		$model		= PermissionService::findById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {
			
			$model->type 	= $type;

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'Permission' )  && $model->validate() ) {
	
				if( PermissionService::update( $model ) ) {
	
					$binder 			= new Binder();
					$binder->binderId	= $model->id;
	
					$binder->load( Yii::$app->request->post(), 'Binder' );
	
					PermissionService::bindRoles( $binder );
	
					$this->redirect( $this->returnUrl );
				}
			}
	
			$roles	= RoleService::getIdNameListByType( $type );
	
	    	return $this->render( 'update', [
	    		'model' => $model,
	    		'roles' => $roles
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $type = null ) {

		// Find Model
		$model		= PermissionService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			$model->type 	= $type;

			if( $model->load( Yii::$app->request->post(), 'Permission' ) ) {

				if( PermissionService::delete( $model ) ) {

					$this->redirect( $this->returnUrl );
				}
			}

			$roles	= RoleService::getIdNameListByType( $type );
	
	    	return $this->render( 'delete', [
	    		'model' => $model,
	    		'roles' => $roles
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>