<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
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

abstract class BaseUserController extends BaseController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );		
	}

	// Instance Methods --------------------------------------------

	// UserController --------------------

	public function actionAll( $roleSlug = null, $permissionSlug = null, $showCreate = true ) {

		$dataProvider = null;

		if( isset( $roleSlug ) ) {

			$dataProvider = UserService::getPaginationByRoleSlug( $roleSlug );
		}
		else if( isset( $permissionSlug ) ) {

			$dataProvider = UserService::getPaginationByPermissionSlug( $permissionSlug );
		}
		else {

			$dataProvider = UserService::getPagination();
		}

	    return $this->render( '@cmsgears/module-core/admin/views/user/all', [
			'dataProvider' => $dataProvider,
			'showCreate' => $showCreate
	    ]);
	}

	public function actionCreate( $roleType = null, $roleSlug = null ) {

		$model		= new User();
		$siteMember	= new SiteMember();

		$model->setScenario( 'create' );

		if( isset( $roleSlug ) ) {

			$role 				= RoleService::findBySlug( $roleSlug );
			$siteMember->roleId = $role->id;
		}

		if( $model->load( Yii::$app->request->post(), 'User' ) && $siteMember->load( Yii::$app->request->post(), 'SiteMember' ) && $model->validate() ) {

			// Create User
			$user 		= UserService::create( $model );

			// Add User to current Site
			$siteMember	= SiteMemberService::create( $model, $siteMember );

			if( $user && $siteMember ) {
				
				// Load User Permissions
				$model->loadPermissions();

				// Send Account Mail
				Yii::$app->cmgCoreMailer->sendCreateUserMail( $model );

				$this->redirect( $this->returnUrl );
			}
		}

		if( isset( $roleSlug ) ) {

			return $this->render( '@cmsgears/module-core/admin/views/user/create', [
				'model' => $model,
				'siteMember' => $siteMember
			]);
		}
		else {
			
			$roleMap 	= RoleService::getIdNameMapByType( $roleType );

			return $this->render( '@cmsgears/module-core/admin/views/user/create', [
				'sidebar' => $this->sidebar,
				'model' => $model,
				'siteMember' => $siteMember,
				'roleMap' => $roleMap
			]);			
		}
	}

	public function actionUpdate( $id, $roleType = null, $roleSlug = null ) {

		// Find Model
		$model		= UserService::findById( $id );
		$avatar 	= new CmgFile();

		// Update/Render if exist
		if( isset( $model ) ) {

			$siteMember	= $model->siteMember;

			$model->setScenario( 'update' );

			UserService::checkNewsletterMember( $model );

			if( $model->load( Yii::$app->request->post(), 'User' ) && $siteMember->load( Yii::$app->request->post(), 'SiteMember' ) && $model->validate() ) {

				$avatar->load( Yii::$app->request->post(), 'File' );

				// Update User and Site Member
				if( UserService::update( $model, $avatar ) && SiteMemberService::update( $siteMember ) ) {

					$this->redirect( $this->returnUrl );
				}
			}

			$avatar		= $model->avatar;

			if( isset( $roleSlug ) ) {

		    	return $this->render( '@cmsgears/module-core/admin/views/user/update', [
		    		'model' => $model,
		    		'siteMember' => $siteMember,
		    		'avatar' => $avatar,
		    		'status' => User::$statusMapUpdate
		    	]);
			}
			else {

				$roleMap 	= RoleService::getIdNameMapByType( $roleType );

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

	public function actionDelete( $id, $roleType = null, $roleSlug = null ) {

		// Find Model
		$model		= UserService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			$siteMember	= $model->siteMember;

			if( $model->load( Yii::$app->request->post(), 'User' ) ) {

				if( UserService::delete( $model ) ) {

					$this->redirect( $this->returnUrl );
				}
			}
			else {

				$roleMap 	= RoleService::getIdNameMapByType( $roleType );

	        	return $this->render( '@cmsgears/module-core/admin/views/user/delete', [
	        		'model' => $model,
	        		'siteMember' => $siteMember,
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