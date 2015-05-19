<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\CmgFile;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\SiteMember;

use cmsgears\core\admin\services\CategoryService;
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
	                'all' => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'create' => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'update' => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_IDENTITY ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index'  => ['get'],
	                'all' => ['get'],
	                'create' => ['get', 'post'],
	                'update' => ['get', 'post'],
	                'delete' => ['get', 'post']
                ]
            ]
        ];
    }

	// UserController --------------------

	public function actionIndex() {

		$this->redirect( "all" );
	}

	public function actionAll() {

		$pagination = UserService::getPaginationByUsers();

	    return $this->render('all', [
	         'page' => $pagination['page'],
	         'pages' => $pagination['pages'],
	         'total' => $pagination['total']
	    ]);
	}

	public function actionCreate() {

		$model		= new User();
		$siteMember	= new SiteMember();

		$model->setScenario( "create" );

		if( $model->load( Yii::$app->request->post( "User" ), "" ) && $model->validate() && $siteMember->load( Yii::$app->request->post( "SiteMember" ), "" ) ) {

			// Create User
			$user 		= UserService::create( $model );

			// Add User to current Site
			$siteMember	= SiteMemberService::create( $model, $siteMember );

			if( $user && $siteMember ) {

				// Send Account Mail
				Yii::$app->cmgCoreMailer->sendCreateUserMail( $this->getCoreProperties(), $this->getMailProperties(), $model );

				return $this->redirect( "all" );
			}
		}

		$roles 		= RoleService::getIdNameMap();
		$genders 	= CategoryService::getOptionIdNameMapByName( CoreGlobal::CATEGORY_GENDER );

		return $this->render('create', [
			'model' => $model,
			'siteMember' => $siteMember,
			'roles' => $roles,
			'genders' => $genders
		]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model		= UserService::findById( $id );
		$avatar 	= new CmgFile();

		// Update/Render if exist
		if( isset( $model ) ) {

			$siteMember	= $model->siteMember;

			$model->setScenario( "update" );

			if( $model->load( Yii::$app->request->post( "User" ), "" )  && $model->validate() && $siteMember->load( Yii::$app->request->post( "SiteMember" ), "" ) ) {

				$avatar->load( Yii::$app->request->post( "Avatar" ), "" );

				// Update User and Site Member
				if( UserService::update( $model, $avatar ) && SiteMemberService::update( $siteMember ) ) {

					$this->refresh();
				}
			}

			$roles 		= RoleService::getIdNameMap();
			$genders 	= CategoryService::getOptionIdNameMapByName( CoreGlobal::CATEGORY_GENDER );
			$avatar		= $model->avatar;
			
	    	return $this->render('update', [
	    		'model' => $model,
	    		'siteMember' => $siteMember,
	    		'avatar' => $avatar,
	    		'roles' => $roles,
	    		'genders' => $genders,
	    		'status' => User::$statusMapUpdate
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= UserService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			$siteMember	= $model->siteMember;

			if( $model->load( Yii::$app->request->post( "User" ), "" ) ) {

				if( UserService::delete( $model ) ) {

					return $this->redirect( "users" );
				}
			}
			else {

				$roles 		= RoleService::getIdNameMap();
				$genders 	= CategoryService::getOptionIdNameMapByName( CoreGlobal::CATEGORY_GENDER );

	        	return $this->render('delete', [
	        		'model' => $model,
	        		'siteMember' => $siteMember,
	        		'roles' => $roles,
	        		'genders' => $genders,
	        		'status' => User::$statusMapUpdate
	        	]);
			}
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>