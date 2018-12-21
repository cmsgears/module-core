<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

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

use cmsgears\core\common\behaviors\ActivityBehavior;

/**
 * UserController provides actions specific to users.
 *
 * @since 1.0.0
 */
class UserController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $type;

	protected $memberService;
	protected $roleService;
	protected $optionService;

	protected $superRoleId;

	protected $countryService;
	protected $provinceService;
	protected $regionService;
	protected $addressService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Views
		$this->setViewPath( '@cmsgears/module-core/admin/views/user' );

		// Permission
		$this->crudPermission = CoreGlobal::PERM_IDENTITY;

		// Config
		$this->type		= CoreGlobal::TYPE_DEFAULT;
		$this->apixBase	= 'core/user';

		// Services
		$this->modelService		= Yii::$app->factory->get( 'userService' );
		$this->memberService	= Yii::$app->factory->get( 'siteMemberService' );
		$this->roleService		= Yii::$app->factory->get( 'roleService' );
		$this->optionService	= Yii::$app->factory->get( 'optionService' );

		$this->countryService	= Yii::$app->factory->get( 'countryService' );
		$this->provinceService	= Yii::$app->factory->get( 'provinceService' );
		$this->regionService	= Yii::$app->factory->get( 'regionService' );
		$this->addressService	= Yii::$app->factory->get( 'addressService' );

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
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ]
			],
			'all' => [ [ 'label' => 'Users' ] ],
			'create' => [ [ 'label' => 'Users', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Users', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Users', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ],
			'profile' => [ [ 'label' => 'User Profile' ] ],
			'settings' => [ [ 'label' => 'User Settings' ] ]
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
		$member	= $this->memberService->getModelObject();
		$avatar	= File::loadFile( null, 'Avatar' );
		$banner	= File::loadFile( null, 'Banner' );
		$video	= File::loadFile( null, 'Video' );

		$model->setScenario( 'create' );

		$member->siteId = Yii::$app->core->siteId;

		$model->type = $this->type;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) &&
			$member->load( Yii::$app->request->post(), $member->getClassName() ) &&
			$model->validate() && $member->validate() ) {

			// Create User
			$this->model = $this->modelService->create( $model, [ 'admin' => true, 'avatar' => $avatar, 'banner' => $banner, 'video' => $video ] );

			$member->userId = $this->model->id;

			// Add User to current Site
			$member = $this->memberService->create( $member );

			if( $this->model && $member ) {

				// Load User Permissions
				$this->model->loadPermissions();

				// Send Account Mail
				Yii::$app->coreMailer->sendCreateUserMail( $this->model );

				return $this->redirect( 'all' );
			}
		}

		$roleMap = $this->roleService->getIdNameMapByType( CoreGlobal::TYPE_SYSTEM );

		$user = Yii::$app->core->getUser();

		if( $user->activeSiteMember->roleId != $this->superRoleId ) {

			unset( $roleMap[ $this->superRoleId ] );
		}

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

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) &&
				$member->load( Yii::$app->request->post(), $member->getClassName() ) &&
				$model->validate() && $member->validate() ) {

				// Update User and Site Member
				$this->model = $this->modelService->update( $model, [ 'admin' => true, 'avatar' => $avatar, 'banner' => $banner, 'video' => $video ] );

				$this->memberService->update( $member );

				return $this->redirect( $this->returnUrl );
			}

			$roleMap = $this->roleService->getIdNameMapByType( CoreGlobal::TYPE_SYSTEM );

			$user = Yii::$app->core->getUser();

			if( $user->activeSiteMember->roleId != $this->superRoleId ) {

				unset( $roleMap[ $this->superRoleId ] );
			}

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

			$member = $model->activeSiteMember;

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) ) {

				try {

					$this->model = $model;

					$this->modelService->delete( $model );

					return $this->redirect( $this->returnUrl );
				}
				catch( Exception $e ) {

					throw new HttpException( 409, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
				}
			}

			$roleMap = $this->roleService->getIdNameMapByType( CoreGlobal::TYPE_SYSTEM );

			$user = Yii::$app->core->getUser();

			if( $user->activeSiteMember->roleId != $this->superRoleId ) {

				unset( $roleMap[ $this->superRoleId ] );
			}

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
		$model = Yii::$app->core->getUser();

		// Avatar
		$avatar = $model->avatar;

		// Address
		$address = $model->primaryAddress;

		if( empty( $address ) ) {

			$address = $this->addressService->getModelObject();
		}

		// Clear Sidebar
		$this->sidebar = [];

		$genderMap = $this->optionService->getIdNameMapByCategorySlug( CoreGlobal::CATEGORY_GENDER, [ 'default' => true ] );

		$countryMap		= Yii::$app->factory->get( 'countryService' )->getIdNameMap();
		$countryId		= isset( $address->country ) ? $address->country->id : array_keys( $countryMap )[ 0 ];
		$provinceMap	= Yii::$app->factory->get( 'provinceService' )->getMapByCountryId( $countryId, [ 'default' => true, 'defaultValue' => Yii::$app->core->provinceLabel ] );
		$provinceId		= isset( $address->province ) ? $address->province->id : array_keys( $provinceMap )[ 0 ];
		$regionMap		= Yii::$app->factory->get( 'regionService' )->getMapByProvinceId( $provinceId, [ 'default' => true, 'defaultValue' => Yii::$app->core->regionLabel ] );

		return $this->render( 'profile', [
			'model' => $model,
			'avatar' => $avatar,
			'address' => $address,
			'genderMap' => $genderMap,
			'countryMap' => $countryMap,
			'provinceMap' => $provinceMap,
			'regionMap' => $regionMap
		]);
	}

	public function actionSettings() {

		// Find Model
		$user = Yii::$app->core->getUser();

		// Clear Sidebar
		$this->sidebar = [];

		// Load key settings
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

}
