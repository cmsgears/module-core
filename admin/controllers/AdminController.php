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

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * AdminController provides actions specific to users.
 *
 * @since 1.0.0
 */
class AdminController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $optionService;

	protected $countryService;
	protected $provinceService;
	protected $regionService;
	protected $addressService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Views
		$this->setViewPath( '@cmsgears/module-core/admin/views/admin' );

		// Permission
		$this->crudPermission = CoreGlobal::PERM_ADMIN;

		// Config
		$this->apixBase	= 'core/profile';

		// Services
		$this->modelService = Yii::$app->factory->get( 'userService' );

		$this->optionService = Yii::$app->factory->get( 'optionService' );

		$this->countryService	= Yii::$app->factory->get( 'countryService' );
		$this->provinceService	= Yii::$app->factory->get( 'provinceService' );
		$this->regionService	= Yii::$app->factory->get( 'regionService' );
		$this->addressService	= Yii::$app->factory->get( 'addressService' );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ]
			],
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
					'profile'  => [ 'permission' => CoreGlobal::PERM_ADMIN ],
					'settings'  => [ 'permission' => CoreGlobal::PERM_ADMIN ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'profile'  => [ 'get', 'post' ],
					'settings'  => [ 'get', 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// AdminController -----------------------

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
		$privacy		= $this->modelService->getMetaNameMetaMapByType( $user, CoreGlobal::SETTINGS_PRIVACY );
		$notification	= $this->modelService->getMetaNameMetaMapByType( $user, CoreGlobal::SETTINGS_NOTIFICATION );
		$reminder		= $this->modelService->getMetaNameMetaMapByType( $user, CoreGlobal::SETTINGS_REMINDER );

		return $this->render( 'settings', [
			'user' => $user,
			'privacy' => $privacy,
			'notification' => $notification,
			'reminder' => $reminder
		]);
	}

}
