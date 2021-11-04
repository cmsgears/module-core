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

use cmsgears\core\common\models\resources\File;
use cmsgears\core\common\models\resources\UserMeta;

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

	protected $metaService;
	protected $memberService;
	protected $roleService;

	protected $optionService;
	protected $localeService;

	protected $superRoleId;
	protected $userRoleId;

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
		$this->metaService		= Yii::$app->factory->get( 'userMetaService' );
		$this->memberService	= Yii::$app->factory->get( 'siteMemberService' );
		$this->roleService		= Yii::$app->factory->get( 'roleService' );

		$this->optionService	= Yii::$app->factory->get( 'optionService' );
		$this->localeService	= Yii::$app->factory->get( 'localeService' );

		// User Role
		$userRole = $this->roleService->getBySlugType( CoreGlobal::ROLE_USER, CoreGlobal::TYPE_SYSTEM );

		$this->userRoleId = isset( $userRole ) ? $userRole->id : null;

		// Super Admin
		$superRole = $this->roleService->getBySlugType( CoreGlobal::ROLE_SUPER_ADMIN, CoreGlobal::TYPE_SYSTEM );

		$this->superRoleId = isset( $superRole ) ? $superRole->id : null;

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-rbac', 'child' => 'user' ];

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
			'gallery' => [ [ 'label' => 'Users', 'url' => $this->returnUrl ], [ 'label' => 'Gallery' ] ]
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
					'update'  => [ 'permission' => $this->crudPermission, 'filters' => [ 'discover' ] ],
					'delete'  => [ 'permission' => $this->crudPermission ],
					'gallery'  => [ 'permission' => $this->crudPermission ]
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
					'gallery'  => [ 'get', 'post' ]
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

	public function actions() {

		return [
			'gallery' => [ 'class' => 'cmsgears\core\common\actions\gallery\Manage' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UserController ------------------------

	public function actionAll() {

		Url::remember( Yii::$app->request->getUrl(), 'users' );

		$modelClass = $this->modelService->getModelClass();

		$dataProvider = $this->modelService->getPage();

		$roleMap = $this->roleService->getIdNameMapByType( CoreGlobal::TYPE_SYSTEM );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'roleMap' => $roleMap,
			'statusMap' => $modelClass::$statusMap
		]);
	}

	public function actionCreate() {

		$modelClass = $this->modelService->getModelClass();

		$user = Yii::$app->core->getUser();

		$model	= $this->modelService->getModelObject();
		$member	= $this->memberService->getModelObject();
		$avatar	= File::loadFile( null, 'Avatar' );
		$banner	= File::loadFile( null, 'Banner' );
		$video	= File::loadFile( null, 'Video' );

		$model->setScenario( 'create' );

		$member->siteId = Yii::$app->core->siteId;
		$member->roleId	= $this->userRoleId;

		$model->type = $this->type;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) &&
			$member->load( Yii::$app->request->post(), $member->getClassName() ) &&
			$model->validate() && $member->validate() ) {

			$valid = !( $user->activeSiteMember->roleId != $this->superRoleId && $member->roleId == $this->superRoleId );

			if( $valid ) {

				// Create User
				$this->model = $this->modelService->create( $model, [
					'admin' => true, 'avatar' => $avatar, 'banner' => $banner, 'video' => $video
				]);

				if( $this->model ) {

					$this->model->refresh();

					$member->userId = $this->model->id;

					// Add User to current Site
					$member = $this->memberService->create( $member );

					// Default Settings
					$this->metaService->initByNameType( $this->model->id, CoreGlobal::META_RECEIVE_EMAIL, CoreGlobal::SETTINGS_NOTIFICATION, UserMeta::VALUE_TYPE_FLAG );
					$this->metaService->initByNameType( $this->model->id, CoreGlobal::META_RECEIVE_EMAIL, CoreGlobal::SETTINGS_REMINDER, UserMeta::VALUE_TYPE_FLAG );

					if( $this->model && $member ) {

						// Load User Permissions
						$this->model->loadPermissions();

						// Send Account Mail
						Yii::$app->coreMailer->sendCreateUserMail( $this->model );

						return $this->redirect( 'all' );
					}
				}
			}
		}

		$roleMap = $this->roleService->getIdNameMapByType( CoreGlobal::TYPE_SYSTEM );

		if( $user->activeSiteMember->roleId != $this->superRoleId ) {

			unset( $roleMap[ $this->superRoleId ] );
		}

		$genderMap	= $this->optionService->getIdNameMapByCategorySlug( CoreGlobal::CATEGORY_GENDER, [ 'default' => true ] );
		$maritalMap = $this->optionService->getIdNameMapByCategorySlug( CoreGlobal::CATEGORY_MARITAL, [ 'default' => true ] );
		$localeMap	= $this->localeService->getIdNameMap( [ 'default' => true ] );

		return $this->render( 'create', [
			'model' => $model,
			'member' => $member,
			'avatar' => $avatar,
			'banner' => $banner,
			'video' => $video,
			'roleMap' => $roleMap,
			'statusMap' => $modelClass::$userStatusMap,
			'genderMap' => $genderMap,
			'maritalMap' => $maritalMap,
			'localeMap' => $localeMap
		]);
	}

	public function actionUpdate( $id ) {

		$modelClass = $this->modelService->getModelClass();

		$user = Yii::$app->core->getUser();

		// Model from Discover Filter
		$model = $this->model;

		$member	= $model->activeSiteMember;
		$avatar	= File::loadFile( $model->avatar, 'Avatar' );
		$banner	= File::loadFile( $model->banner, 'Banner' );
		$video	= File::loadFile( $model->video, 'Video' );

		$model->setScenario( 'update' );

		// Model checks
		$oldRoleId = $member->roleId;

		// Load & Validate
		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) &&
			$member->load( Yii::$app->request->post(), $member->getClassName() ) &&
			$model->validate() && $member->validate() ) {

			$valid = !( $user->activeSiteMember->roleId != $this->superRoleId && $member->roleId == $this->superRoleId );

			if( $valid ) {

				// Update User
				$this->model = $this->modelService->update( $model, [
					'admin' => true, 'avatar' => $avatar, 'banner' => $banner, 'video' => $video
				]);

				$this->model->refresh();

				// Update Site Member
				$this->memberService->update( $member );

				// Check Role Change
				$this->modelService->checkRoleChange( $model, $oldRoleId );

				return $this->redirect( $this->returnUrl );
			}
		}

		// Filter Super Admin Role
		$roleMap = $this->roleService->getIdNameMapByType( CoreGlobal::TYPE_SYSTEM );

		if( $user->activeSiteMember->roleId != $this->superRoleId ) {

			unset( $roleMap[ $this->superRoleId ] );
		}

		$genderMap	= $this->optionService->getIdNameMapByCategorySlug( CoreGlobal::CATEGORY_GENDER, [ 'default' => true ] );
		$maritalMap = $this->optionService->getIdNameMapByCategorySlug( CoreGlobal::CATEGORY_MARITAL, [ 'default' => true ] );
		$localeMap	= $this->localeService->getIdNameMap( [ 'default' => true ] );

		// Render
		return $this->render( 'update', [
			'model' => $model,
			'member' => $member,
			'avatar' => $avatar,
			'banner' => $banner,
			'video' => $video,
			'roleMap' => $roleMap,
			'statusMap' => $modelClass::$userStatusMap,
			'genderMap' => $genderMap,
			'maritalMap' => $maritalMap,
			'localeMap' => $localeMap
		]);
	}

	public function actionDelete( $id ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			$modelClass = $this->modelService->getModelClass();

			$member = $model->activeSiteMember;

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) ) {

				try {

					$this->model = $model;

					$this->modelService->delete( $model );

					return $this->redirect( $this->returnUrl );
				}
				catch( Exception $e ) {

					throw new HttpException( 409, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY ) );
				}
			}

			$roleMap = $this->roleService->getIdNameMapByType( CoreGlobal::TYPE_SYSTEM );

			return $this->render( 'delete', [
				'model' => $model,
				'member' => $member,
				'avatar' => $model->avatar,
				'banner' => $model->banner,
				'video' => $model->video,
				'roleMap' => $roleMap,
				'statusMap' => $modelClass::$userStatusMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
