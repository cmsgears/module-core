<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\mappers\SiteMember;
use cmsgears\core\common\models\resources\File;

abstract class UserController extends CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $roleType;

	protected $roleSlug;

	protected $permissionSlug;

	protected $showCreate;

	protected $siteMemberService;
	protected $roleService;
	protected $optionService;

	protected $roleSuperAdmin;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->setViewPath( '@cmsgears/module-core/admin/views/user' );

		$this->crudPermission		= CoreGlobal::PERM_IDENTITY;
		$this->modelService			= Yii::$app->factory->get( 'userService' );

		$this->siteMemberService	= Yii::$app->factory->get( 'siteMemberService' );
		$this->roleService			= Yii::$app->factory->get( 'roleService' );
		$this->optionService		= Yii::$app->factory->get( 'optionService' );

		$this->returnUrl			= Url::previous( 'users' );

		$this->showCreate			= true;

		$this->roleSuperAdmin		= $this->roleService->getBySlug( 'super-admin', true );
		$this->roleSuperAdmin		= isset( $this->roleSuperAdmin ) ? $this->roleSuperAdmin->id : null;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UserController ------------------------

	public function actionAll() {

		$roleTable			= CoreTables::TABLE_ROLE;
		$permissionTable	= CoreTables::TABLE_PERMISSION;

		$dataProvider		= null;

		// Role specific users
		if( isset( $this->roleSlug ) ) {

			$dataProvider = $this->modelService->getPage( [ 'conditions' => [ "$roleTable.slug" => $this->roleSlug, "$roleTable.type" => CoreGlobal::TYPE_SYSTEM ], 'query' => User::queryWithSiteMembers() ] );
		}
		// Permission specific users
		else if( isset( $this->permissionSlug ) ) {

			$dataProvider = $this->modelService->getPage( [ 'conditions' => [ "$permissionTable.slug" => $this->permissionSlug, "$permissionTable.type" => CoreGlobal::TYPE_SYSTEM ], 'query' => User::queryWithSiteMembersPermissions() ] );
		}
		// All users
		else {

			$dataProvider = $this->modelService->getPage();
		}

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'showCreate' => $this->showCreate
		]);
	}

	public function actionCreate() {

		$model		= new User();
		$siteMember	= new SiteMember();
		$avatar		= File::loadFile( null, 'Avatar' );

		$model->setScenario( 'create' );

		if( isset( $this->roleSlug ) ) {

			$role				= $this->roleService->getBySlugType( $this->roleSlug, CoreGlobal::TYPE_SYSTEM );
			$siteMember->roleId = $role->id;
		}

		if( $model->load( Yii::$app->request->post(), 'User' ) && $siteMember->load( Yii::$app->request->post(), 'SiteMember' ) && $model->validate() ) {

			// Create User
			$user		= $this->modelService->create( $model, [ 'avatar' => $avatar ] );

			// Add User to current Site
			$siteMember = $this->siteMemberService->create( $user, [ 'siteMember' => $siteMember, 'roleId' => $siteMember->roleId ] );

			if( $user && $siteMember ) {

				// Load User Permissions
				$user->loadPermissions();

				// Send Account Mail
				Yii::$app->coreMailer->sendCreateUserMail( $user );

				return $this->redirect( $this->returnUrl );
			}
		}

		if( isset( $this->roleSlug ) ) {

			return $this->render( 'create', [
				'model' => $model,
				'siteMember' => $siteMember
			]);
		}
		else {

			$roleMap	= $this->roleService->getIdNameMapByType( $this->roleType );

			unset( $roleMap[ $this->roleSuperAdmin ] );

			return $this->render( 'create', [
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
		$model		= $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$siteMember	= $model->activeSiteMember;
			$avatar		= File::loadFile( $model->avatar, 'Avatar' );

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'User' ) && $siteMember->load( Yii::$app->request->post(), 'SiteMember' ) && $model->validate() ) {

				// Update User and Site Member
				$this->modelService->update( $model, [ 'avatar' => $avatar ] );

				$this->siteMemberService->update( $siteMember );

				return $this->redirect( $this->returnUrl );
			}

			if( isset( $this->roleSlug ) ) {

				return $this->render( 'update', [
					'model' => $model,
					'siteMember' => $siteMember,
					'avatar' => $avatar,
					'status' => User::$statusMap
				]);
			}
			else {

				$roleMap	= $this->roleService->getIdNameMapByType( $this->roleType );

				unset( $roleMap[ $this->roleSuperAdmin ] );

				return $this->render( 'update', [
					'model' => $model,
					'siteMember' => $siteMember,
					'avatar' => $avatar,
					'roleMap' => $roleMap,
					'status' => User::$statusMap
				]);
			}
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model		= $this->modelService->getById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			$siteMember	= $model->activeSiteMember;
			$avatar		= $model->avatar;

			if( $model->load( Yii::$app->request->post(), 'User' ) ) {

				$this->modelService->delete( $model );

				return $this->redirect( $this->returnUrl );
			}
			else {

				$roleMap	= $this->roleService->getIdNameMapByType( $this->roleType );

				unset( $roleMap[ $this->roleSuperAdmin ] );

				return $this->render( 'delete', [
					'model' => $model,
					'siteMember' => $siteMember,
					'avatar' => $avatar,
					'roleMap' => $roleMap,
					'status' => User::$statusMap
				]);
			}
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
