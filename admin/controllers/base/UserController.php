<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\mappers\SiteMember;
use cmsgears\core\common\models\resources\CmgFile;

use cmsgears\core\common\services\mappers\SiteMemberService;
use cmsgears\core\admin\services\entities\UserService;
use cmsgears\core\admin\services\entities\RoleService;
use cmsgears\core\admin\services\resources\OptionService;

abstract class UserController extends Controller {

	protected $roleType;

	protected $roleSlug;

	protected $permissionSlug;

	protected $showCreate;

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->returnUrl	= Url::previous( 'users' );

		$this->showCreate 	= true;
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'index' => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'all' => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'create' => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'update' => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_IDENTITY ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index' => [ 'get' ],
	                'all' => [ 'get' ],
	                'create' => [ 'get', 'post' ],
	                'update' => [ 'get', 'post' ],
	                'delete' => [ 'get', 'post' ]
                ]
            ]
        ];
    }

	// UserController --------------------

	public function actionIndex() {

		return $this->redirect( 'all' );
	}

	public function actionAll() {

		$dataProvider = null;

		if( isset( $this->roleSlug ) ) {

			$dataProvider = UserService::getPaginationByRoleSlug( $this->roleSlug );
		}
		else if( isset( $this->permissionSlug ) ) {

			$dataProvider = UserService::getPaginationByPermissionSlug( $this->permissionSlug );
		}
		else {

			$dataProvider = UserService::getPagination();
		}

	    return $this->render( '@cmsgears/module-core/admin/views/user/all', [
			'dataProvider' => $dataProvider,
			'showCreate' => $this->showCreate
	    ]);
	}

	public function actionCreate() {

		$model		= new User();
		$siteMember	= new SiteMember();
		$avatar 	= CmgFile::loadFile( null, 'Avatar' );

		$model->setScenario( 'create' );

		if( isset( $this->roleSlug ) ) {

			$role 				= RoleService::findBySlug( $this->roleSlug );
			$siteMember->roleId = $role->id;
		}

		if( $model->load( Yii::$app->request->post(), 'User' ) && $siteMember->load( Yii::$app->request->post(), 'SiteMember' ) && $model->validate() ) {

			// Create User
			$user 		= UserService::create( $model, $avatar );

			// Add User to current Site
			$siteMember	= SiteMemberService::create( $model, $siteMember );

			if( $user && $siteMember ) {

				// Load User Permissions
				$model->loadPermissions();

				// Send Account Mail
				Yii::$app->cmgCoreMailer->sendCreateUserMail( $model );

				return $this->redirect( $this->returnUrl );
			}
		}

		if( isset( $this->roleSlug ) ) {

			return $this->render( '@cmsgears/module-core/admin/views/user/create', [
				'model' => $model,
				'siteMember' => $siteMember
			]);
		}
		else {

			$roleMap 	= RoleService::getIdNameMapByType( $this->roleType );

			return $this->render( '@cmsgears/module-core/admin/views/user/create', [
				'sidebar' => $this->sidebar,
				'model' => $model,
				'siteMember' => $siteMember,
	    		'avatar' => $avatar,
				'roleMap' => $roleMap
			]);
		}
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model		= UserService::findById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$siteMember	= $model->siteMember;
			$avatar 	= CmgFile::loadFile( $model->avatar, 'Avatar' );

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'User' ) && $siteMember->load( Yii::$app->request->post(), 'SiteMember' ) && $model->validate() ) {

				// Update User and Site Member
				if( UserService::update( $model, $avatar ) && SiteMemberService::update( $siteMember ) ) {

					return $this->redirect( $this->returnUrl );
				}
			}

			if( isset( $this->roleSlug ) ) {

		    	return $this->render( '@cmsgears/module-core/admin/views/user/update', [
		    		'model' => $model,
		    		'siteMember' => $siteMember,
		    		'avatar' => $avatar,
		    		'status' => User::$statusMapUpdate
		    	]);
			}
			else {

				$roleMap 	= RoleService::getIdNameMapByType( $this->roleType );

		    	return $this->render( '@cmsgears/module-core/admin/views/user/update', [
		    		'model' => $model,
		    		'siteMember' => $siteMember,
		    		'avatar' => $avatar,
		    		'roleMap' => $roleMap,
		    		'status' => User::$statusMapUpdate
		    	]);
			}
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model		= UserService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			$siteMember	= $model->siteMember;
			$avatar 	= $model->avatar;

			if( $model->load( Yii::$app->request->post(), 'User' ) ) {

				if( UserService::delete( $model, $avatar ) ) {

					return $this->redirect( $this->returnUrl );
				}
			}
			else {

				$roleMap 	= RoleService::getIdNameMapByType( $this->roleType );

	        	return $this->render( '@cmsgears/module-core/admin/views/user/delete', [
	        		'model' => $model,
	        		'siteMember' => $siteMember,
		    		'avatar' => $avatar,
	        		'roleMap' => $roleMap,
	        		'status' => User::$statusMapUpdate
	        	]);
			}
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>