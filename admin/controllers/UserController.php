<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\admin\config\AdminGlobalCore;

use cmsgears\core\common\models\entities\CmgFile;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\Category;
use cmsgears\core\common\models\entities\Option;
use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\entities\Permission;

use cmsgears\core\admin\services\CategoryService;
use cmsgears\core\admin\services\UserService;
use cmsgears\core\admin\services\RoleService;

use cmsgears\core\common\utilities\CodeGenUtil;
use cmsgears\core\common\utilities\MessageUtil;

class UserController extends BaseController {

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
	                'index'  => [ 'permission' => Permission::PERM_IDENTITY_USER ],
	                'all'   => [ 'permission' => Permission::PERM_IDENTITY_USER ],
	                'create' => [ 'permission' => Permission::PERM_IDENTITY_USER ],
	                'update' => [ 'permission' => Permission::PERM_IDENTITY_USER ],
	                'delete' => [ 'permission' => Permission::PERM_IDENTITY_USER ]
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

	// UserController

	public function actionIndex() {

		$this->redirect( "all" );
	}

	public function actionAll() {

		$pagination = UserService::getPagination();
		$roles 		= RoleService::getIdNameList();
		$roles 		= CodeGenUtil::generateIdNameArray( $roles );

	    return $this->render('all', [
	         'page' => $pagination['page'],
	         'pages' => $pagination['pages'],
	         'total' => $pagination['total'],
	         'roles' => $roles
	    ]);
	}

	public function actionCreate() {

		$model	= new User();

		$model->setScenario( "create" );

		if( $model->load( Yii::$app->request->post() )  && $model->validate() ) {

			if( UserService::create( $model ) ) {

				// Send Account Mail
				Yii::$app->cmgCoreMailer->sendCreateUserMail( $this->getCoreProperties(), $this->getMailProperties(), $model );

				return $this->redirect( "all" );
			}
		}

		$roles 		= RoleService::getIdNameList();
		$roles 		= CodeGenUtil::generateIdNameArray( $roles );
		$genders 	= CategoryService::getOptionIdKeyMapByName( CoreGlobal::CATEGORY_GENDER );

    	return $this->render('create', [
    		'model' => $model,
    		'roles' => $roles,
    		'genders' => $genders
    	]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model	= UserService::findById( $id );
		$avatar = new CmgFile();
		
		// Update/Render if exist
		if( isset( $model ) ) {

			$model->setScenario( "update" );

			if( $model->load( Yii::$app->request->post() )  && $model->validate() ) {

				$avatar->load( Yii::$app->request->post( "File" ), "" );

				if( UserService::update( $model, $avatar ) ) {
	
					$this->refresh();
				}
			}

			$roles 		= RoleService::getIdNameList();
			$roles 		= CodeGenUtil::generateIdNameArray( $roles );

			$genders 	= CategoryService::getOptionIdKeyMapByName( CoreGlobal::CATEGORY_GENDER );
			$avatar		= $model->avatar;
			
	    	return $this->render('update', [
	    		'model' => $model,
	    		'avatar' => $avatar,
	    		'roles' => $roles,
	    		'genders' => $genders,
	    		'status' => User::$statusMapUpdate
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( MessageUtil::getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= UserService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post() ) ) {
	
				if( UserService::delete( $model ) ) {
	
					return $this->redirect( "all" );
				}
			}
			else {

				$roles 		= RoleService::getIdNameList();
				$roles 		= CodeGenUtil::generateIdNameArray( $roles );

				$genders 	= CategoryService::getOptionIdKeyMapByName( CoreGlobal::CATEGORY_GENDER );

	        	return $this->render('delete', [
	        		'model' => $model,
	        		'roles' => $roles,
	        		'genders' => $genders,
	        		'status' => User::$statusMapUpdate
	        	]);
			}
		}

		// Model not found
		throw new NotFoundHttpException( MessageUtil::getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>