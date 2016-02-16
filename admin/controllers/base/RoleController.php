<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\forms\Binder;

use cmsgears\core\admin\services\RoleService;
use cmsgears\core\admin\services\PermissionService;

abstract class RoleController extends Controller {

	protected $type;

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
		
		$this->returnUrl	= Url::previous( 'roles' );
		
		$this->type			= CoreGlobal::TYPE_SYSTEM;
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'index' => [ 'permission' => CoreGlobal::PERM_RBAC ],
	                'all' => [ 'permission' => CoreGlobal::PERM_RBAC ],
	                'create' => [ 'permission' => CoreGlobal::PERM_RBAC ],
	                'update' => [ 'permission' => CoreGlobal::PERM_RBAC ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_RBAC ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index'   => [ 'get' ],
	                'create' => [ 'get', 'post' ],
	                'update' => [ 'get', 'post' ],
	                'delete' => [ 'get', 'post' ]
                ]
            ]
        ];
    }
	
	// BaseRoleController -----------------

	public function actionIndex() {

		return $this->redirect( 'all' );
	}

	public function actionAll() {

		$dataProvider = RoleService::getPaginationByType( $this->type );

	    return $this->render( 'all', [
			'dataProvider' => $dataProvider
	    ]);
	}

	public function actionCreate() {

		$model			= new Role();
		$model->type 	= $this->type;

		$model->setScenario( 'create' );

		if( $model->load( Yii::$app->request->post(), 'Role' )  && $model->validate() ) {

			if( RoleService::create( $model ) ) {

				$binder 			= new Binder();
				$binder->binderId	= $model->id;

				$binder->load( Yii::$app->request->post(), 'Binder' );

				RoleService::bindPermissions( $binder );

				return $this->redirect( $this->returnUrl );
			}
		}

		$permissions	= PermissionService::getIdNameListByType( $this->type );

    	return $this->render( 'create', [
    		'model' => $model,
    		'permissions' => $permissions
    	]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model		= RoleService::findById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {
			
			$model->type 	= $this->type;

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'Role' )  && $model->validate() ) {

				if( RoleService::update( $model ) ) {

					$binder 			= new Binder();
					$binder->binderId	= $model->id;

					$binder->load( Yii::$app->request->post(), 'Binder' );

					RoleService::bindPermissions( $binder );
	
					return $this->redirect( $this->returnUrl );
				}
			}

			$permissions	= PermissionService::getIdNameListByType( $this->type );

	    	return $this->render( 'update', [
	    		'model' => $model,
	    		'permissions' => $permissions
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model		= RoleService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), 'Role' ) ) {

				if( RoleService::delete( $model ) ) {

					return $this->redirect( $this->returnUrl );
				}
			}

			$permissions	= PermissionService::getIdNameListByType( $this->type );

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