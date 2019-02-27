<?php
/**
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 * @license https://www.cmsgears.org/license/
 * @package module
 * @subpackage core
 */
namespace cmsgears\core\common\components;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\validators\CoreValidator;

use cmsgears\core\common\services\entities\UserService;

use cmsgears\core\common\base\Config;

/**
 * The core component for CMSGears based sites. It must be initialised for app bootstrap
 * using the name core.
 *
 * It define the post login and logout path to redirect user to a different path than the
 * default one. Though ajax based login need to specify the path within the javascript code.
 *
 * All the admin sites must set rbac to true to get the admin functional since the admin
 * controllers use it for almost every action.
 *
 * @since 1.0.0
 */
class Core extends Config {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	/**
	 * @var int The main site id to load configurations in case sub sites are not configured.
	 */
	public $mainSiteId			= 1;

	/**
	 * @var string The main site slug to load configurations in case sub sites are not configured.
	 */
	public $mainSiteSlug		= 'main';

	/**
	 * @var string string Used to debug multi-site.
	 */
	public $defaultSiteSlug		= 'main';

	/**
	 * @var int Identifies the currently active site based on the url request.
	 */
	public $siteId				= 1;

	/**
	 * @var string Identifies the currently active site based on the url request.
	 */
	public $siteSlug			= 'main';

	/**
	 * @var \cmsgears\core\common\models\entities\Site The currently active site based on the url request.
	 */
	public $site				= null;

	/**
	 * @var boolean Identifies whether all the site config need to be loaded at once or by type i.e. module or plugin.
	 */
	public $siteConfigAll 		= false;

	/**
	 * @var boolean Check whether the web app is multi-site.
	 */
	public $multiSite			= false;

	/**
	 * @var boolean Check whether the web app is sub domain or sub directory based in case $multiSite is set to true.
	 */
	public $subDirectory		= true;

	public $testHosts = [ 'localhost' ];

	/**
	 *
	 * @var \cmsgears\core\common\models\entities\User The active logged in user.
	 */
	public $user;

	// The three type of apps.

	public $appAdmin			= CoreGlobal::APP_ADMIN;
	public $appFrontend			= CoreGlobal::APP_FRONTEND;
	public $appConsole			= CoreGlobal::APP_CONSOLE;

	/**
	 * It can be used in case user approval from admin is required.
	 */
	public $userApproval		= false;

	/**
	 * It can be used to test otp validity in milliseconds.
	 */
	public $otpValidity			= 600000; // 10 minues by default

	/**
	 * It can be used to test token validity in milliseconds.
	 */
	public $tokenValidity		= 600000; // 10 minues by default

	/**
	 * @var default redirect path to be used for post login. It will be used by login action of
	 * Site Controller to redirect users after successful login in case user role home url is not set.
	 */
	public $loginRedirectPage		= '/';

	/**
	 * @var Redirect path to be used when user is newly registered and not active. $userApproval
	 * must be true for it.
	 */
	public $confirmRedirectPage		= '/';

	/**
	 * @var Redirect path to be used for post logout.
	 */
	public $logoutRedirectPage		= '/login';

	/**
	 * @var The indicator whether CMG RBAC has to be used for the project. All the admin sites must
	 * set this to true. Though it's optional for front end sites. The front end sites can use either
	 * CMG RBAC or Yii's RBAC system or no RBAC system based on project needs.
	 */
	public $rbac				= true;

	/**
	 * @var The default filter class available for CMG RBAC system. A different filter can be used
	 * based on project needs.
	 */
	public $rbacFilterClass		= 'cmsgears\core\common\\filters\RbacFilter';

	/**
	 * @var It store the list of filters available for the Rbac Filter and works only when rbac is enabled.
	 * A Controller can define filters to be performed for each action while checking the permission.
	 */
	public $rbacFilters			= [];

	/**
	 * It can be used to check whether APIS are enabled for the application. APIS are provided
	 * using the access token for the same user base i.e. the web application is also supported by
	 * mobile applications having same users. It's used by user class to load permissions when accessed
	 * using access token.
	 *
	 * OAuth 2.0 or other authorization mechanism can be used to provide APIS to 3rd party
	 * web and mobile applications.
	 *
	 * @var boolean
	 */
	public $apis				= false;

	/**
	 * APIS validity in days will be used to check whether the date when access token is generated is
	 * older and user must be forced to login. A new access token must be generated on login.
	 *
	 * @var int
	 */
	public $apisValidity		= 7;

	/**
	 * @var The WYSIWYG editor config to edit the html content.
	 */
	public $editor = null;

	/**
	 * @var It can be used by model classes to determine the fields for trim filter.
	 */
	public $trimFieldValue		= true;

	// Different Text Sizes - These can be overriden using config if required
	public $tinyText			= CoreGlobal::TEXT_TINY;
	public $smallText			= CoreGlobal::TEXT_SMALL;
	public $mediumText			= CoreGlobal::TEXT_MEDIUM;
	public $largeText			= CoreGlobal::TEXT_LARGE;
	public $xLargeText			= CoreGlobal::TEXT_XLARGE;
	public $xxLargeText			= CoreGlobal::TEXT_XXLARGE;
	public $xxxLargeText		= CoreGlobal::TEXT_XXXLARGE;
	public $xtraLargeText		= CoreGlobal::TEXT_XTRALARGE;

	/**
	 * @var Switch for notification feature. If it's set to true, either Notify Module
	 * must be installed or eventManager component must be configured.
	 */
	public $notifications		= false;

	/**
	 * @var Switch for activities feature. If it's set to true, either Notify Module must
	 * be installed or eventManager component must be configured.
	 */
	public $activities			= false;

	/**
	 * @var Update selective allows services to update selected columns.
	 */
	public $updateSelective		= true;

	/**
	 *
	 * @var boolean Check whether soft delete is enabled.
	 */
	public $softDelete			= true;

	// Locations
	public $provinceLabel	= 'Province';
	public $regionLabel		= 'Region';
	public $zipLabel		= 'Postal Code';

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	/**
	 * Initialise the CMG Core Component.
	 */
	public function init() {

		parent::init();

		// Initialise core validators
		CoreValidator::initValidators();

		/** Set CMSGears alias to be used by all modules, plugins, widgets and themes.
		 * It will be located within the vendor directory for composer.
		 */
		Yii::setAlias( 'cmsgears', dirname( dirname( dirname( __DIR__ ) ) ) );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Properties

	/**
	 * The hasModule method check whether a module is available for the app. We can use it for conditional cases.
	 * @param string $name the module name
	 * @return whether module name and root path are set in app config file
	 */
	public function hasModule( $name ) {

		$module = \Yii::$app->getModule( $name );

		return isset( $module );
	}

	/**
	 * The hasTheme method check whether a theme is available for the app. We can use it to make certain things available in a different theme
	 * apart from currently active theme.
	 * @param string $name the theme name
	 * @return whether theme name and root path are set in app config file
	 */
	public function hasTheme( $name ) {

		//TODO - Add code to check availability of a theme from themes folder
	}

	/**
	 * The hasWidget method check whether a widget is available for the app. The theme can show the widgets conditionally based on availablility.
	 * @param string $name the widget name
	 * @return whether widget name and root path are set in app config file
	 */
	public function hasWidget( $name ) {

		//TODO - Add code to check availability of a widget from database and widgets folder
	}

	public function getMainSiteId() {

		return $this->mainSiteId;
	}

	/**
	 * The method getMainSiteSlug returns the site slug for main site.
	 * @return string
	 */
	public function getMainSiteSlug() {

		return $this->mainSiteSlug;
	}

	public function getDefaultSiteSlug() {

		return $this->defaultSiteSlug;
	}

	/**
	 * The method getSiteId returns the site id for default site. It's more useful in case multi-site feature is enabled.
	 * @return string
	 */
	public function getSiteId() {

		return $this->siteId;
	}

	/**
	 * The method getSiteSlug returns the site slug for default site. It's more useful in case multi-site feature is enabled.
	 * @return string
	 */
	public function getSiteSlug() {

		return $this->siteSlug;
	}

	public function setSite( $site ) {

		$this->site	= $site;

		$this->siteId	= $site->id;
		$this->siteSlug	= $site->slug;

		return $this->site;
	}

	public function getSite() {

		return $this->site;
	}

	/**
	 * The method isMultiSite can be used to check whether multi-site feature is required.
	 *
	 * @return boolean
	 */
	public function isMultiSite() {

		return $this->multiSite;
	}

	public function isGuest() {

		return Yii::$app->user->isGuest();
	}

	public function getUser() {

		if( empty( $this->user ) ) {

			$this->user = Yii::$app->user->getIdentity();
		}

		return $this->user;
	}

	/**
	 * The method isSubDirectory can be used to check whether multi-site feature needs to follow sub directory approach instead of sub domain.
	 * @return boolean
	 */
	public function isSubDirectory() {

		return $this->subDirectory;
	}

	public function getTestHosts() {

		return $this->testHosts;
	}

	public function getAppAdmin() {

		return $this->appAdmin;
	}

	public function getAppFrontend() {

		return $this->appFrontend;
	}

	public function getAppConsole() {

		return $this->appConsole;
	}

	public function isUserApproval() {

		return $this->userApproval;
	}

	public function getOtpValidity() {

		return $this->otpValidity;
	}

	public function getOtpValidityMins() {

		return ( $this->otpValidity / 60000 );
	}

	public function getTokenValidity() {

		return $this->tokenValidity;
	}

	/**
	 * The method getLoginRedirectPage returns the default path to be redirected after login using the non-ajax based form.
	 * @return string - path used for post login
	 */
	public function getLoginRedirectPage() {

		return $this->loginRedirectPage;
	}

	public function getConfirmRedirectPage() {

		return $this->confirmRedirectPage;
	}

	/**
	 * The method getLogoutRedirectPage returns the default path to be redirected after logout using the non-ajax based form.
	 * @return path used for post logout
	 */
	public function getLogoutRedirectPage() {

		return $this->logoutRedirectPage;
	}

	/**
	 * The method isRbac is used by the User class to check whether RBAC is enabled. It can be used by any other class or code snippet.
	 * @return whether CMSGears simplified RBAC system has to be used
	 */
	public function isRbac() {

		return $this->rbac;
	}

	/**
	 * The method isRbac is used by the Controller classes.
	 * @return whether CMSGears simplified RBAC system has to be used
	 */
	public function getRbacFilterClass() {

		return $this->rbacFilterClass;
	}

	/**
	 * The method getRbacFilter returns the corresponding RBAC Filter class path based on given filter name. These filters are fine grained control over the permissions allowed for the user role.
	 */
	public function getRbacFilter( $filter ) {

		return $this->rbacFilters[ $filter ];
	}

	/**
	 * The method isApis is used to check whether apis are supported by the applications.
	 * @return the class name
	 */
	public function isApis() {

		return $this->apis;
	}

	public function getApisValidity() {

		return $this->apisValidity;
	}

	/**
	 * The method getEditor is used by the views to make a text area to edit html.
	 *
	 * @return the class name
	 */
	public function getEditor() {

		return $this->editor;
	}

	public function isTrimFieldValue() {

		return $this->trimFieldValue;
	}

	public function getSmallText() {

		return $this->smallText;
	}

	public function getMediumText() {

		return $this->mediumText;
	}

	public function getLargeText() {

		return $this->largeText;
	}

	public function getExtraLargeText() {

		return $this->xLargeText;
	}

	/**
	 * To test whether notifications are enabled.
	 */
	public function isNotifications() {

		return $this->notifications;
	}

	/**
	 * To test whether notifications are enabled.
	 */
	public function isActivities() {

		return $this->activities;
	}

	public function isUpdateSelective() {

		return $this->updateSelective;
	}

	public function isSoftDelete() {

		return $this->softDelete;
	}

	public function getProvinceLabel() {

		return $this->provinceLabel;
	}

	public function getRegionLabel() {

		return $this->regionLabel;
	}

	public function getZipLabel() {

		return $this->zipLabel;
	}

	// Cookies & Session

	public function setAppUser( $user ) {

		$cookieName = '_app-user';

		$guestUser[ 'user' ] = [ 'id' => $user->id, 'firstname' => $user->firstName, 'lastname' => $user->lastName, 'email' => $user->email ];

		if( isset( $_COOKIE[ $cookieName ] ) ) {

			$data = unserialize( $_COOKIE[ $cookieName ] );

			if( $data[ 'user' ][ 'id' ] != $user->id ) {

				return setcookie( $cookieName, serialize( $guestUser ), time() + ( 10 * 365 * 24 * 60 * 60 ), "/", null );
			}
		}
		else {

			return setcookie( $cookieName, serialize( $guestUser ), time() + ( 10 * 365 * 24 * 60 * 60 ), "/", null );
		}
	}

	// Call setAppUser at least once for new user before calling this method.
	public function getAppUser() {

		$cookieName = '_app-user';
		$appUser	= null;
		$user		= Yii::$app->user->identity;

		if( $user != null ) {

			$appUser = $user;
		}
		else if( isset( $_COOKIE[ $cookieName ] ) ) {

			$data = unserialize( $_COOKIE[ $cookieName ] );

			if( isset( $data[ 'user' ] ) ) {

				//$appUser = (object) $data[ 'user' ]['user'];

				$appUser = UserService::findById( $data[ 'user' ][ 'id' ] );
			}
		}

		return $appUser;
	}

	public function resetAppUser() {

		$cookieName = '_app-user';

		if( isset( $_COOKIE[ $cookieName ] ) ) {

			$data = unserialize( $_COOKIE[ $cookieName ] );

			$data[ 'user' ] = null;

			return setcookie( $cookieName, serialize( $data ), time() + ( 10 * 365 * 24 * 60 * 60 ), "/", null );
		}
	}

	/*
	 * @return $session - Open a session if does not exist in application.
	 */
	public function getSession() {

		$session = Yii::$app->session;

		if( !$session->isActive ) {

		   $session->open();
		}

		return $session;
	}

	/*
	 * @return $session - Open a session if does not exist in application.
	 */
	public function setSessionParam( $param, $value ) {

		Yii::$app->session->set( $param, $value );
	}

	/*
	 * @return $session - return session param
	 */
	public function getSessionParam( $param ) {

		return Yii::$app->session->get( $param );
	}

	/*
	 * @return $session - return session param
	 */
	public function clearSessionParam( $param ) {

		return Yii::$app->session->remove( $param );
	}

	/*
	 * @return $session - Open a session if does not exist in application.
	 */
	public function setSessionObject( $param, $object ) {

		$data = base64_encode( serialize( $object ) );

		Yii::$app->session->set( $param, $data );
	}

	/*
	 * @return $session - return session param
	 */
	public function getSessionObject( $param ) {

		$data = Yii::$app->session->get( $param );

		if( isset( $data ) ) {

			$object = unserialize( base64_decode( $data ) );

			return $object;
		}

		return null;
	}

	/*
	 * @return $session - return session param
	 */
	public function clearSessionObject( $param ) {

		return Yii::$app->session->remove( $param );
	}

}
