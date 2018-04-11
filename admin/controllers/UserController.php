<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\resources\File;

use cmsgears\core\admin\controllers\base\Controller;

use cmsgears\core\common\behaviors\ActivityBehavior;

class UserController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $memberService;
	protected $siteMemberService;
	protected $roleService;
	protected $optionService;

	protected $superRoleId;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = CoreGlobal::PERM_IDENTITY;

		// Services
		$this->modelService			= Yii::$app->factory->get( 'userService' );
		$this->siteMemberService	= Yii::$app->factory->get( 'siteMemberService' );
		$this->roleService			= Yii::$app->factory->get( 'roleService' );
		$this->optionService		= Yii::$app->factory->get( 'optionService' );

		// Super Admin
		$superRole = $this->roleService->getBySlugType( CoreGlobal::ROLE_SUPER_ADMIN, CoreGlobal::TYPE_SYSTEM );

		$this->superRoleId = isset( $superRole ) ?$superRole->id : null;

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-identity', 'child' => 'user' ];

		// Return Url
		$this->returnUrl = Url::previous( 'users' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/user/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'all' => [ [ 'label' => 'Users' ] ],
			'create' => [ [ 'label' => 'Users', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Users', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Users', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		return [
			'rbac' => [
				'class' => Yii::$app->core->getRbacFilterClass(),
				'actions' => [
					'index'	 => [ 'permission' => $this->crudPermission ],
					'all'  => [ 'permission' => $this->crudPermission ],
					'create'  => [ 'permission' => $this->crudPermission ],
					'update'  => [ 'permission' => $this->crudPermission ],
					'delete'  => [ 'permission' => $this->crudPermission ],
					'profile'  => [ 'permission' => CoreGlobal::PERM_ADMIN ],
					'settings'  => [ 'permission' => CoreGlobal::PERM_ADMIN ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'index' => [ 'get', 'post' ],
					'all'  => [ 'get' ],
					'create'  => [ 'get', 'post' ],
					'update'  => [ 'get', 'post' ],
					'delete'  => [ 'get', 'post' ],
					'profile'  => [ 'get', 'post' ],
					'settings'  => [ 'get', 'post' ]
				]
			],
			'activity' => [
				'class' => ActivityBehavior::class,
				'admin' => true,
				'create' => [ 'create' ],
				'update' => [ 'update' ],
				'delete' => [ 'delete' ]
			]
		];
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UserController ------------------------

	public function actionAll() {

		Url::remember( Yii::$app->request->getUrl(), 'users' );

		$dataProvider = $this->modelService->getPage();

		$roleMap = $this->roleService->getIdNameMapByType( CoreGlobal::TYPE_SYSTEM );

		unset( $roleMap[ $this->superRoleId ] );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'roleMap' => $roleMap,
			'statusMap' => User::$statusMap
		]);
	}

	public function actionCreate() {

		$model	= $this->modelService->getModelObject();
		$member	= $this->siteMemberService->getModelObject();
		$avatar	= File::loadFile( null, 'Avatar' );
		$banner	= File::loadFile( null, 'Banner' );
		$video	= File::loadFile( null, 'Video' );

		$model->setScenario( 'create' );

		$member->siteId = Yii::$app->core->siteId;

		if( $model->load( Yii::$app->request->post(), 'User' ) && $member->load( Yii::$app->request->post(), 'SiteMember' ) && $model->validate() && $member->validate() ) {

			// Create User
			$this->model = $this->modelService->create( $model, [ 'admin' => true, 'avatar' => $avatar, 'banner' => $banner, 'video' => $video ] );

			$member->userId = $this->model->id;

			// Add User to current Site
			$member = $this->siteMemberService->create( $member );

			if( $this->model && $member ) {

				// Load User Permissions
				$this->model->loadPermissions();

				// Send Account Mail
				Yii::$app->coreMailer->sendCreateUserMail( $this->model );

				return $this->redirect( 'all' );
			}
		}

		$roleMap = $this->roleService->getIdNameMapByType( CoreGlobal::TYPE_SYSTEM );

		unset( $roleMap[ $this->superRoleId ] );

		return $this->render( 'create', [
			'model' => $model,
			'member' => $member,
			'avatar' => $avatar,
			'banner' => $banner,
			'video' => $video,
			'roleMap' => $roleMap,
			'statusMap' => User::$statusMap
		]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$member	= $model->activeSiteMember;
			$avatar	= File::loadFile( $model->avatar, 'Avatar' );
			$banner	= File::loadFile( $model->banner, 'Banner' );
			$video	= File::loadFile( $model->video, 'Video' );

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'User' ) && $member->load( Yii::$app->request->post(), 'SiteMember' ) && $model->validate() && $member->validate() ) {

				// Update User and Site Member
				$this->model = $this->modelService->update( $model, [ 'admin' => true, 'avatar' => $avatar, 'banner' => $banner, 'video' => $video ] );

				$this->siteMemberService->update( $member );

				return $this->redirect( $this->returnUrl );
			}

			$roleMap = $this->roleService->getIdNameMapByType( CoreGlobal::TYPE_SYSTEM );

			unset( $roleMap[ $this->superRoleId ] );

			return $this->render( 'update', [
				'model' => $model,
				'member' => $member,
				'avatar' => $avatar,
				'banner' => $banner,
				'video' => $video,
				'roleMap' => $roleMap,
				'statusMap' => User::$statusMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			$member	= $model->activeSiteMember;

			if( $model->load( Yii::$app->request->post(), 'User' ) ) {

				try {

					$this->model = $this->modelService->delete( $model );

					return $this->redirect( $this->returnUrl );
				}
				catch( Exception $e ) {

					throw new HttpException( 409, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
				}
			}

			$roleMap = $this->roleService->getIdNameMapByType( CoreGlobal::TYPE_SYSTEM );

			unset( $roleMap[ $this->superRoleId ] );

			return $this->render( 'delete', [
				'model' => $model,
				'member' => $member,
				'avatar' => $model->avatar,
				'banner' => $model->banner,
				'video' => $model->video,
				'roleMap' => $roleMap,
				'statusMap' => User::$statusMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionProfile() {

		// Find Model
		$user = Yii::$app->user->getIdentity();

		$this->sidebar = [];

		// Update/Render if exist
		if( isset( $user ) ) {

			$genderMap = $this->optionService->getIdNameMapByCategorySlug( CoreGlobal::CATEGORY_GENDER, [ [ 'id' => '0', 'name' => 'Choose Gender' ] ] );

			return $this->render( 'profile', [
				'user' => $user,
				'genderMap' => $genderMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionSettings() {

		// Find Model
		$user				= Yii::$app->user->getIdentity();
		$this->sidebar		= [];

		// Update/Render if exist
		if( isset( $user ) ) {

			$privacy		= $this->modelService->getNameMetaMapByType( $user, CoreGlobal::SETTINGS_PRIVACY );
			$notification	= $this->modelService->getNameMetaMapByType( $user, CoreGlobal::SETTINGS_NOTIFICATION );
			$reminder		= $this->modelService->getNameMetaMapByType( $user, CoreGlobal::SETTINGS_REMINDER );

			return $this->render( 'settings', [
				'user' => $user,
				'privacy' => $privacy,
				'notification' => $notification,
				'reminder' => $reminder
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
