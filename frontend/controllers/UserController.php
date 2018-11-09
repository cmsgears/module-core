<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\frontend\controllers;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;
use yii\base\InvalidArgumentException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\frontend\config\CoreGlobalWeb;

use cmsgears\core\common\models\forms\ResetPassword;
use cmsgears\core\common\models\resources\File;

/**
 * UserController process the actions specific to User model.
 *
 * @since 1.0.0
 */
class UserController extends \cmsgears\core\frontend\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $basePath;

	// Protected --------------

	protected $optionService;

	protected $countryService;
	protected $provinceService;
	protected $regionService;
	protected $addressService;
	protected $modelAddressService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = CoreGlobal::PERM_USER;

		// Config
		$this->basePath = '/user';
		$this->apixBase = 'core/user';

		// Services
		$this->modelService = Yii::$app->factory->get( 'userService' );

		$this->optionService = Yii::$app->factory->get( 'optionService' );

		$this->countryService		= Yii::$app->factory->get( 'countryService' );
		$this->provinceService		= Yii::$app->factory->get( 'provinceService' );
		$this->regionService		= Yii::$app->factory->get( 'regionService' );
		$this->addressService		= Yii::$app->factory->get( 'addressService' );
		$this->modelAddressService	= Yii::$app->factory->get( 'modelAddressService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	public function behaviors() {

		return [
			'rbac' => [
				'class' => Yii::$app->core->getRbacFilterClass(),
				'actions' => [
					// User dashboard
					'index' => [ 'permission' => $this->crudPermission ],
					'home' => [ 'permission' => $this->crudPermission ],
					// User details
					'profile' => [ 'permission' => $this->crudPermission ],
					'account' => [ 'permission' => $this->crudPermission ],
					'address' => [ 'permission' => $this->crudPermission ],
					'settings' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					// User dashboard
					'index' => [ 'get' ],
					'home' => [ 'get' ],
					// User details
					'profile' => [ 'get', 'post' ],
					'account' => [ 'get', 'post' ],
					'address' => [ 'get', 'post' ],
					'settings' => [ 'get' ]
				]
			]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UserController ------------------------

	// Redirect user to appropriate home page
	public function actionIndex() {

		$this->checkHome();
	}

	// Default home page for user
	public function actionHome() {

		return $this->render( CoreGlobalWeb::PAGE_INDEX );
	}

	/**
	 * The action profile allows users to update their profile.
	 *
	 * @return string
	 */
	public function actionProfile() {

		// Find Model
		$user = Yii::$app->core->getUser();

		// Avatar
		$avatar = File::loadFile( $user->avatar, 'Avatar' );

		// Scenario
		$user->setScenario( 'profile' );

		if( $user->load( Yii::$app->request->post(), $user->getClassName() ) && $user->validate() ) {

			// Update User
			$this->modelService->update( $user, [ 'avatar' => $avatar ] );

			// Refresh Page
			return $this->refresh();
		}

		$genderMap = $this->optionService->getIdNameMapByCategorySlug( CoreGlobal::CATEGORY_GENDER, [ 'prepend' => [ [ 'id' => '0', 'name' => 'Choose Gender' ] ] ] );

		return $this->render( CoreGlobalWeb::PAGE_PROFILE, [
			'user' => $user,
			'avatar' => $avatar,
			'genderMap' => $genderMap
		]);
	}

	/**
	 * The account action allows user to change password.
	 *
	 * @return string
	 */
	public function actionAccount() {

		// Find Model
		$user	= Yii::$app->core->getUser();
		$model	= new ResetPassword();

		// Configure Model
		$model->email = $user->email;

		// Old password required if it was already set
		if( !empty( $user->passwordHash ) ) {

			$model->setScenario( 'oldPassword' );
		}

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			// Update User
			$this->modelService->resetPassword( $user, $model, false );

			return $this->refresh();
		}

		return $this->render( CoreGlobalWeb::PAGE_ACCOUNT, [
			'user' => $user,
			'model' => $model
		]);
	}

	/**
	 * The address action allows user to update primary address using form submit. Use the
	 * corresponding Ajax Action to handle multiple user address.
	 *
	 * In case we need multiple address using form submit, this action can be overridden by
	 * child classes to load multiple address.
	 *
	 * @return string
	 */
	public function actionAddress( $ctype ) {

		$user		= Yii::$app->core->getUser();
		$address	= null;

		// Accept only selected type for a user
		if( !in_array( $ctype, [ Address::TYPE_PRIMARY, Address::TYPE_BILLING, Address::TYPE_MAILING, Address::TYPE_SHIPPING ] ) ) {

			throw new InvalidArgumentException( 'Address type not allowed.' );
		}

		switch( $ctype ) {

			case Address::TYPE_PRIMARY: {

				$address = $user->primaryAddress;

				break;
			}
			case Address::TYPE_BILLING: {

				$address = $user->billingAddress;

				break;
			}
			case Address::TYPE_MAILING: {

				$address = $user->mailingAddress;

				break;
			}
			case Address::TYPE_SHIPPING: {

				$address = $user->shippingAddress;

				break;
			}
		}

		if( empty( $address ) ) {

			$address = $this->addressService->getModelObject();
		}

		if( $address->load( Yii::$app->request->post(), $address->getClassName() ) && $address->validate() ) {

			// Create/Update Address
			$address = $this->addressService->createOrUpdate( $address );

			// Create Mapping
			$modelAddress = $this->modelAddressService->activateByModelId( $user->id, CoreGlobal::TYPE_USER, $address->id, $ctype );

			return $this->refresh();
		}

		$countryMap		= Yii::$app->factory->get( 'countryService' )->getIdNameMap();
		$countryId		= isset( $address->country ) ? $address->country->id : array_keys( $countryMap )[ 0 ];
		$provinceMap	= Yii::$app->factory->get( 'provinceService' )->getMapByCountryId( $countryId, [ 'default' => true, 'defaultValue' => Yii::$app->core->provinceLabel ] );
		$provinceId		= isset( $address->province ) ? $address->province->id : array_keys( $provinceMap )[ 0 ];
		$regionMap		= Yii::$app->factory->get( 'regionService' )->getMapByProvinceId( $provinceId, [ 'default' => true, 'defaultValue' => Yii::$app->core->regionLabel ] );

		return $this->render( 'address', [
			'user' => $user,
			'address' => $address,
			'countryMap' => $countryMap,
			'provinceMap' => $provinceMap,
			'regionMap' => $regionMap
		]);
	}

	/**
	 * The settings action pre-load the privacy, notification and reminder settings and
	 * send them to view. In case more settings are required, we can either override this
	 * action or use the model service to access additional settings.
	 *
	 * @return string
	 */
	public function actionSettings() {

		$user = Yii::$app->core->getUser();

		// Load key settings
		$privacy		= $this->modelService->getNameMetaMapByType( $user, CoreGlobal::SETTINGS_PRIVACY );
		$notification	= $this->modelService->getNameMetaMapByType( $user, CoreGlobal::SETTINGS_NOTIFICATION );
		$reminder		= $this->modelService->getNameMetaMapByType( $user, CoreGlobal::SETTINGS_REMINDER );

		// NOTE: Rest of the settings can be loaded in view if required.

		// TODO: Check for options to cache all the user attributes.

		return $this->render( CoreGlobalWeb::PAGE_SETTINGS, [
			'user' => $user,
			'privacy' => $privacy,
			'notification' => $notification,
			'reminder' => $reminder
		]);
	}

}
