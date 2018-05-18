<?php
namespace cmsgears\core\frontend\controllers;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\frontend\config\CoreGlobalWeb;

use cmsgears\core\common\models\forms\ResetPassword;
use cmsgears\core\common\models\resources\Address;

class UserController extends \cmsgears\core\frontend\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $basePath;

	// Protected --------------

	protected $optionService;
	protected $countryService;
	protected $provinceService;
	protected $modelAddressService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->crudPermission		= CoreGlobal::PERM_USER;

		$this->basePath				= '/user';

		$this->modelService			= Yii::$app->factory->get( 'userService' );

		$this->optionService		= Yii::$app->factory->get( 'optionService' );
		$this->countryService		= Yii::$app->factory->get( 'countryService' );
		$this->provinceService		= Yii::$app->factory->get( 'provinceService' );
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
				'class' => VerbFilter::className(),
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

		$user	= Yii::$app->user->getIdentity();

		if( $user->load( Yii::$app->request->post(), 'User' ) && $user->validate() ) {

			// Update User and Site Member
			$this->modelService->update( $user );

			return $this->refresh();
		}

		$genderMap	= $this->optionService->getIdNameMapByCategorySlug( CoreGlobal::CATEGORY_GENDER, [ [ 'id' => '0', 'name' => 'Choose Gender' ] ] );

		return $this->render( CoreGlobalWeb::PAGE_PROFILE, [
			'user' => $user,
			'genderMap' => $genderMap
		]);
	}

	/**
	 * The account action allows user to change password.
	 *
	 * @return string
	 */
	public function actionAccount() {

		$user			= Yii::$app->user->getIdentity();
		$model			= new ResetPassword();
		$model->email	= $user->email;

		if( $model->load( Yii::$app->request->post(), 'ResetPassword' ) && $model->validate() ) {

			$this->modelService->resetPassword( $user, $model, false );

			return $this->refresh();
		}

		return $this->render( CoreGlobalWeb::PAGE_ACCOUNT, [
			'model' => $model
		]);
	}

	/**
	 * The address action allows user to update primary address using form submit.
	 *
	 * In case we need multiple address, this action can be overridden by child classes to load multiple address.
	 *
	 * @return string
	 */
	public function actionAddress() {

		$user		= Yii::$app->user->getIdentity();
		$address	= $user->primaryAddress;

		if( empty( $address ) ) {

			$address	= new Address();
		}

		if( $address->load( Yii::$app->request->post(), 'Address' ) && $address->validate() ) {

			$modelAddress	= $this->modelAddressService->createOrUpdateByType( $address, [ 'parentId' => $user->id, 'parentType' => CoreGlobal::TYPE_USER, 'type' => Address::TYPE_PRIMARY ] );

			return $this->refresh();
		}

		$countryMap		= $this->countryService->getIdNameMap();
		$countryId		= array_keys( $countryMap )[ 0 ];
		$provinceMap	= $this->provinceService->getMapByCountryId( $countryId );

		return $this->render( CoreGlobalWeb::PAGE_ADDRESS, [
			'address' => $address,
			'countryMap' => $countryMap,
			'provinceMap' => $provinceMap
		]);
	}

	/**
	 * The settings action pre-load the privacy, notification and reminder settings and send them to view.
	 * In case more settings are required, we can either override this action or use the model service to access additional settings.
	 *
	 * @return string
	 */
	public function actionSettings() {

		$user	= Yii::$app->user->getIdentity();

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
