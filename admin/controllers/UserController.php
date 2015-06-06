<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\CmgFile;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\SiteMember;

use cmsgears\core\admin\services\OptionService;
use cmsgears\core\admin\services\SiteMemberService;
use cmsgears\core\admin\services\UserService;
use cmsgears\core\admin\services\RoleService;

class UserController extends BaseController {

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
	                'index'  => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'admins' => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'users' => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'create' => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'update' => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_IDENTITY ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index'  => ['get'],
	                'admins' => ['get'],
	                'users' => ['get'],
	                'create' => ['get', 'post'],
	                'update' => ['get', 'post'],
	                'delete' => ['get', 'post']
                ]
            ]
        ];
    }

	// UserController --------------------

	public function actionIndex() {

		$this->redirect( [ "all" ] );
	}

	public function actionAdmins() {

		$dataProvider = UserService::getPaginationByAdmins();

		Url::remember( [ "/cmgcore/user/admins" ], 'users' );

	    return $this->render('admins', [
			'dataProvider' => $dataProvider
	    ]);
	}

	public function actionUsers() {

		$dataProvider = UserService::getPaginationByUsers();

		Url::remember( [ "/cmgcore/user/users" ], 'users' );

	    return $this->render('users', [
			'dataProvider' => $dataProvider
	    ]);
	}

	public function actionCreate() {

		$model		= new User();
		$siteMember	= new SiteMember();
		$returnUrl	= Url::previous( "users" );

		$model->setScenario( "create" );

		if( $model->load( Yii::$app->request->post(), "User" ) && $model->validate() && $siteMember->load( Yii::$app->request->post(), "SiteMember" ) ) {

			// Create User
			$user 		= UserService::create( $model );

			// Add User to current Site
			$siteMember	= SiteMemberService::create( $model, $siteMember );

			if( $user && $siteMember ) {

				// Send Account Mail
				Yii::$app->cmgCoreMailer->sendCreateUserMail( $this->getCoreProperties(), $this->getMailProperties(), $model );

				$this->redirect( $returnUrl );
			}
		}

		$roles 		= RoleService::getIdNameMap();
		$genders 	= OptionService::getIdNameMapByCategoryName( CoreGlobal::CATEGORY_GENDER );

		return $this->render('create', [
			'model' => $model,
			'siteMember' => $siteMember,
			'roles' => $roles,
			'genders' => $genders,
			'returnUrl' => $returnUrl
		]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model		= UserService::findById( $id );
		$avatar 	= new CmgFile();
		$returnUrl	= Url::previous( "users" );

		// Update/Render if exist
		if( isset( $model ) ) {

			$siteMember	= $model->siteMember;

			$model->setScenario( "update" );

			if( $model->load( Yii::$app->request->post( "User" ), "" )  && $model->validate() && $siteMember->load( Yii::$app->request->post( "SiteMember" ), "" ) ) {

				$avatar->load( Yii::$app->request->post( "File" ), "" );

				// Update User and Site Member
				if( UserService::update( $model, $avatar ) && SiteMemberService::update( $siteMember ) ) {

					$this->redirect( $returnUrl );
				}
			}

			$roles 		= RoleService::getIdNameMap();
			$genders 	= OptionService::getIdNameMapByCategoryName( CoreGlobal::CATEGORY_GENDER );
			$avatar		= $model->avatar;
			
	    	return $this->render('update', [
	    		'model' => $model,
	    		'siteMember' => $siteMember,
	    		'avatar' => $avatar,
	    		'roles' => $roles,
	    		'genders' => $genders,
	    		'status' => User::$statusMapUpdate,
	    		'returnUrl' => $returnUrl
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model		= UserService::findById( $id );
		$returnUrl	= Url::previous( "users" );

		// Delete/Render if exist
		if( isset( $model ) ) {

			$siteMember	= $model->siteMember;

			if( $model->load( Yii::$app->request->post( "User" ), "" ) ) {

				if( UserService::delete( $model ) ) {

					$this->redirect( $returnUrl );
				}
			}
			else {

				$roles 		= RoleService::getIdNameMap();
				$genders 	= OptionService::getIdNameMapByCategoryName( CoreGlobal::CATEGORY_GENDER );

	        	return $this->render('delete', [
	        		'model' => $model,
	        		'siteMember' => $siteMember,
	        		'roles' => $roles,
	        		'genders' => $genders,
	        		'status' => User::$statusMapUpdate,
	        		'returnUrl' => $returnUrl
	        	]);
			}
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>