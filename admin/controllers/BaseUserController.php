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

	protected $sidebar;
	protected $createUrl;

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );		
	}

	// Instance Methods --------------------------------------------

	// UserController --------------------

	public function actionAll( $roleSlug = null, $permissionSlug = null ) {

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
	    	'sidebar' => $this->sidebar,
	    	'createUrl' => $this->createUrl,
			'dataProvider' => $dataProvider
	    ]);
	}

	public function actionCreate( $returnUrl, $roleSlug = null ) {

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

				// Send Account Mail
				Yii::$app->cmgCoreMailer->sendCreateUserMail( $model );

				$this->redirect( $returnUrl );
			}
		}

		$genderMap 	= OptionService::getIdNameMapByCategoryName( CoreGlobal::CATEGORY_GENDER );
		
		if( isset( $roleSlug ) ) {

			return $this->render( '@cmsgears/module-core/admin/views/user/create', [
				'sidebar' => $this->sidebar,
				'returnUrl' => $returnUrl,
				'model' => $model,
				'siteMember' => $siteMember,
				'genderMap' => $genderMap,
				'returnUrl' => $returnUrl
			]);
		}
		else {
			
			$roleMap 	= RoleService::getIdNameMap();

			return $this->render( '@cmsgears/module-core/admin/views/user/create', [
				'sidebar' => $this->sidebar,
				'returnUrl' => $returnUrl,
				'model' => $model,
				'siteMember' => $siteMember,
				'roleMap' => $roleMap,
				'genderMap' => $genderMap,
				'returnUrl' => $returnUrl
			]);			
		}
	}

	public function actionUpdate( $returnUrl, $id, $roleSlug = null ) {

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

					$this->redirect( $returnUrl );
				}
			}

			$genderMap 	= OptionService::getIdNameMapByCategoryName( CoreGlobal::CATEGORY_GENDER );
			$avatar		= $model->avatar;

			if( isset( $roleSlug ) ) {

		    	return $this->render( '@cmsgears/module-core/admin/views/user/update', [
		    		'sidebar' => $this->sidebar,
		    		'returnUrl' => $returnUrl,
		    		'model' => $model,
		    		'siteMember' => $siteMember,
		    		'avatar' => $avatar,
		    		'genderMap' => $genderMap,
		    		'status' => User::$statusMapUpdate
		    	]);
			}
			else {

				$roleMap 	= RoleService::getIdNameMap();

		    	return $this->render( '@cmsgears/module-core/admin/views/user/update', [
		    		'sidebar' => $this->sidebar,
		    		'returnUrl' => $returnUrl,
		    		'model' => $model,
		    		'siteMember' => $siteMember,
		    		'avatar' => $avatar,
		    		'roleMap' => $roleMap,
		    		'genderMap' => $genderMap,
		    		'status' => User::$statusMapUpdate
		    	]);
			}
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $returnUrl, $id, $roleName = null ) {

		// Find Model
		$model		= UserService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			$siteMember	= $model->siteMember;

			if( $model->load( Yii::$app->request->post(), 'User' ) ) {

				if( UserService::delete( $model ) ) {

					$this->redirect( $returnUrl );
				}
			}
			else {

				$roleMap 	= RoleService::getIdNameMap();
				$genderMap 	= OptionService::getIdNameMapByCategoryName( CoreGlobal::CATEGORY_GENDER );

	        	return $this->render( '@cmsgears/module-core/admin/views/user/delete', [
	        		'sidebar' => $this->sidebar,
	        		'returnUrl' => $returnUrl,
	        		'model' => $model,
	        		'siteMember' => $siteMember,
	        		'roleMap' => $roleMap,
	        		'genderMap' => $genderMap,
	        		'status' => User::$statusMapUpdate
	        	]);
			}
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>