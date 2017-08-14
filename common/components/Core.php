<?php
namespace cmsgears\core\common\components;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\validators\CoreValidator;

use cmsgears\core\common\services\entities\UserService;

/**
 * The core component for CMSGears based sites. It must be initialised for app bootstrap using the name core.
 * It define the post login and logout path to redirect user to a different path than the default one. Though ajax
 * based login need to specify the path within the javascript code.
 *
 * All the admin sites must set useRbac to true to get the admin functional since the admin controllers use it for almost every action.
 */
class Core extends \yii\base\Component {

	// Global -----------------

	// Public -----------------

	/**
	 * @var Stats will be used to store table row count to solve pagination link issue to generate links for large tables without any joins or where conditions specially for InnoDB engine.
	 * It provides the following features to an application:
	 * # Create stats table when enabled.
	 * # Stats Triggers can be enabled to update row count on addition and deletion.
	 * # CRON job or MySQL event can be added to update row count in case triggers are not used.
	 * # The stats table storing row counts can be avoided if pages are scrolled using previous and next buttons without having page links.
	 */
	public $stats				= false;

	/**
	 * @var It will add triggers while running migrations on row addition and deletion if stats is enabled. In case triggers are skipped, project specific migration can be created in order to add the relevant triggers.
	 */
	public $statsTriggers		= false;

	/**
	 * @var main site to load configurations in case sub sites are not configured.
	 */
	public $mainSiteId			= 1;

	/**
	 * @var main site to load configurations in case sub sites are not configured.
	 */
	public $mainSiteSlug		= 'main';

	/**
	 * @var identify the currently active site based on the url request.
	 */
	public $siteId				= 1;

	/**
	 * @var identify the currently active site based on the url request.
	 */
	public $siteSlug			= 'main';

	/**
	 * @var currently active site based on the url request.
	 */
	public $site				= null;

	/**
	 * @var It identify whether all the site config need to be loaded at once or by type i.e. module or plugin.
	 */
	public $siteConfigAll 		= false;

	/**
	 * @var test whether the web app is multi-site.
	 */
	public $multiSite			= false;

	/**
	 * @var test whether the web app is sub domain or sub directory based in case $multiSite is set to true.
	 */
	public $subDirectory		= true;

	public $appAdmin			= CoreGlobal::APP_ADMIN;
	public $appFrontend			= CoreGlobal::APP_FRONTEND;
	public $appConsole			= CoreGlobal::APP_CONSOLE;

	/**
	 * It can be used in case user approval from admin is required.
	 */
	public $userApproval		= false;

	/**
	 * @var default redirect path to be used for post login. It will be used by login action of Site Controller to redirect users
	 * after successful login in case user role home url is not set.
	 */
	public $loginRedirectPage		= '/';

	/**
	 * @var Redirect path to be used when user is newly registered and not active. $userApproval must be true for it.
	 */
	public $confirmRedirectPage		= '/';

	/**
	 * @var Redirect path to be used for post logout.
	 */
	public $logoutRedirectPage		= '/login';

	/**
	 * @var The indicator whether CMG RBAC has to be used for the project. All the admin sites must set this to true. Though it's optional for
	 * front end sites. The front end sites can use either CMG RBAC or Yii's RBAC system or no RBAC system based on project needs.
	 */
	public $useRbac				= true;

	/**
	 * @var The default filter class available for CMG RBAC system. A different filter can be used based on project needs.
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
	 * OAuth 2.0 can be used to provide APIS to 3rd party web and mobile applications.
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
	 * @var The WYSIWYG editor widget class. It will be used by Core Module to edit newsletter content. The dependent modules can also use it to edit the html content.
	 */
	public $editorClass			= null;

	/**
	 * @var It can be used by model classes to determine the fields for trim filter.
	 */
	public $trimFieldValue		= true;

	// Different Text Sizes - These can be overriden using config if required
	public $smallText			= CoreGlobal::TEXT_SMALL;
	public $mediumText			= CoreGlobal::TEXT_MEDIUM;
	public $largeText			= CoreGlobal::TEXT_LARGE;
	public $xLargeText			= CoreGlobal::TEXT_XLARGE;
	public $xxLargeText			= CoreGlobal::TEXT_XXLARGE;
	public $xxxLargeText		= CoreGlobal::TEXT_XXXLARGE;
	public $xtraLargeText		= CoreGlobal::TEXT_XTRALARGE;

	/**
	 * @var Switch for notification feature. If it's set to true, either Notify Module must be installed or eventManager component must be configured.
	 */
	public $notifications		= false;

	/**
	 * @var Update selective allows services to update selected columns.
	 */
	public $updateSelective		= true;

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

		// Set CMSGears alias to be used by all modules, plugins, widgets and themes. It will be located within the vendor directory for composer.
		Yii::setAlias( 'cmsgears', dirname( dirname( dirname( __DIR__ ) ) ) );

		// Register application components and objects i.e. CMG and Project
		$this->registerComponents();
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Core ----------------------------------

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
	 * @return boolean
	 */
	public function isMultiSite() {

		return $this->multiSite;
	}

	/**
	 * The method isSubDirectory can be used to check whether multi-site feature needs to follow sub directory approach instead of sub domain.
	 * @return boolean
	 */
	public function isSubDirectory() {

		return $this->subDirectory;
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

		return $this->useRbac;
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
	 * The method getEditorClass is used by the views to make a text area to edit html. It must be set
	 * @return the class name
	 */
	public function getEditorClass() {

		return $this->editorClass;
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

	public function isUpdateSelective() {

		return $this->updateSelective;
	}

	// Components and Objects

	public function registerComponents() {

		// Init system services
		$this->initSystemServices();

		// Register services
		$this->registerResourceServices();
		$this->registerMapperServices();
		$this->registerEntityServices();

		// Init services
		$this->initResourceServices();
		$this->initMapperServices();
		$this->initEntityServices();
	}

	public function initSystemServices() {

		$factory = Yii::$app->factory->getContainer();

		//$factory->set( '<name>', '<classpath>' );
	}

	public function registerResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IAddressService', 'cmsgears\core\common\services\resources\AddressService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\ICategoryService', 'cmsgears\core\common\services\resources\CategoryService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IFileService', 'cmsgears\core\common\services\resources\FileService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IFormFieldService', 'cmsgears\core\common\services\resources\FormFieldService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IFormService', 'cmsgears\core\common\services\resources\FormService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IGalleryService', 'cmsgears\core\common\services\resources\GalleryService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IModelMetaService', 'cmsgears\core\common\services\resources\ModelMetaService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IModelCommentService', 'cmsgears\core\common\services\resources\ModelCommentService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IModelHierarchyService', 'cmsgears\core\common\services\resources\ModelHierarchyService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IOptionService', 'cmsgears\core\common\services\resources\OptionService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\ISiteMetaService', 'cmsgears\core\common\services\resources\SiteMetaService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\ITagService', 'cmsgears\core\common\services\resources\TagService' );
	}

	public function registerMapperServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\core\common\services\interfaces\mappers\IModelAddressService', 'cmsgears\core\common\services\mappers\ModelAddressService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\mappers\IModelCategoryService', 'cmsgears\core\common\services\mappers\ModelCategoryService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\mappers\IModelFileService', 'cmsgears\core\common\services\mappers\ModelFileService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\mappers\IModelFormService', 'cmsgears\core\common\services\mappers\ModelFormService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\mappers\IModelGalleryService', 'cmsgears\core\common\services\mappers\ModelGalleryService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\mappers\IModelOptionService', 'cmsgears\core\common\services\mappers\ModelOptionService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\mappers\IModelTagService', 'cmsgears\core\common\services\mappers\ModelTagService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\mappers\ISiteMemberService', 'cmsgears\core\common\services\mappers\SiteMemberService' );
	}

	public function registerEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\core\common\services\interfaces\entities\ICountryService', 'cmsgears\core\common\services\entities\CountryService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\entities\IProvinceService', 'cmsgears\core\common\services\entities\ProvinceService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\entities\ICityService', 'cmsgears\core\common\services\entities\CityService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\entities\IObjectService', 'cmsgears\core\common\services\entities\ObjectService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\entities\IPermissionService', 'cmsgears\core\common\services\entities\PermissionService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\entities\IRoleService', 'cmsgears\core\common\services\entities\RoleService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\entities\ISiteService', 'cmsgears\core\common\services\entities\SiteService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\entities\ITemplateService', 'cmsgears\core\common\services\entities\TemplateService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\entities\IThemeService', 'cmsgears\core\common\services\entities\ThemeService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\entities\IUserService', 'cmsgears\core\common\services\entities\UserService' );
	}

	public function initResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'addressService', 'cmsgears\core\common\services\resources\AddressService' );
		$factory->set( 'categoryService', 'cmsgears\core\common\services\resources\CategoryService' );
		$factory->set( 'fileService', 'cmsgears\core\common\services\resources\FileService' );
		$factory->set( 'formFieldService', 'cmsgears\core\common\services\resources\FormFieldService' );
		$factory->set( 'formService', 'cmsgears\core\common\services\resources\FormService' );
		$factory->set( 'galleryService', 'cmsgears\core\common\services\resources\GalleryService' );
		$factory->set( 'modelMetaService', 'cmsgears\core\common\services\resources\ModelMetaService' );
		$factory->set( 'modelCommentService', 'cmsgears\core\common\services\resources\ModelCommentService' );
		$factory->set( 'modelHierarchyService', 'cmsgears\core\common\services\resources\ModelHierarchyService' );
		$factory->set( 'optionService', 'cmsgears\core\common\services\resources\OptionService' );
		$factory->set( 'siteMetaService', 'cmsgears\core\common\services\resources\SiteMetaService' );
		$factory->set( 'tagService', 'cmsgears\core\common\services\resources\TagService' );
	}

	public function initMapperServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'modelAddressService', 'cmsgears\core\common\services\mappers\ModelAddressService' );
		$factory->set( 'modelCategoryService', 'cmsgears\core\common\services\mappers\ModelCategoryService' );
		$factory->set( 'modelFileService', 'cmsgears\core\common\services\mappers\ModelFileService' );
		$factory->set( 'modelFormService', 'cmsgears\core\common\services\mappers\ModelFormService' );
		$factory->set( 'modelGalleryService', 'cmsgears\core\common\services\mappers\ModelGalleryService' );
		$factory->set( 'modelOptionService', 'cmsgears\core\common\services\mappers\ModelOptionService' );
		$factory->set( 'modelTagService', 'cmsgears\core\common\services\mappers\ModelTagService' );
		$factory->set( 'siteMemberService', 'cmsgears\core\common\services\mappers\SiteMemberService' );
	}

	public function initEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'countryService', 'cmsgears\core\common\services\entities\CountryService' );
		$factory->set( 'provinceService', 'cmsgears\core\common\services\entities\ProvinceService' );
		$factory->set( 'cityService', 'cmsgears\core\common\services\entities\CityService' );
		$factory->set( 'objectService', 'cmsgears\core\common\services\entities\ObjectDataService' );
		$factory->set( 'permissionService', 'cmsgears\core\common\services\entities\PermissionService' );
		$factory->set( 'roleService', 'cmsgears\core\common\services\entities\RoleService' );
		$factory->set( 'siteService', 'cmsgears\core\common\services\entities\SiteService' );
		$factory->set( 'templateService', 'cmsgears\core\common\services\entities\TemplateService' );
		$factory->set( 'themeService', 'cmsgears\core\common\services\entities\ThemeService' );
		$factory->set( 'userService', 'cmsgears\core\common\services\entities\UserService' );
	}

	// Cookies

	public function setAppUser( $user ) {

		$cookieName				= '_app-user';

		$guestUser[ 'user' ]	= [ 'id' => $user->id, 'firstname' => $user->firstName, 'lastname' => $user->lastName, 'email' => $user->email ];

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
		$appUser	= [];
		$user		= Yii::$app->user->identity;

		if( $user != null ) {

			$appUser	= $user;
		}
		else if( isset( $_COOKIE[ $cookieName ] ) ) {

			$data = unserialize( $_COOKIE[ $cookieName ] );

			if( isset( $data[ 'user' ] ) ) {

				//$appUser	  = (object) $data[ 'user' ]['user'];

				$appUser = UserService::findById( $data[ 'user' ][ 'id' ] );
			}
		}

		return $appUser;
	}

	public function resetAppUser() {

		$cookieName = '_app-user';

		if( isset( $_COOKIE[ $cookieName ] ) ) {

			$data 				= unserialize( $_COOKIE[ $cookieName ] );

			$data[ 'user' ]	   = null;

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

		$session = $this->getSession();

		$session->set( $param, $value );
	}
}
