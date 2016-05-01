<?php
namespace cmsgears\core\common\components;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\validators\CoreValidator;

/**
 * The core component for CMSGears based sites. It must be initialised for app bootstrap using the name cmgCore.
 * It define the post login and logout path to redirect user to a different path than the default one. Though ajax
 * based login need to specify the path within the javascript code.
 *
 * All the admin sites must set useRbac to true to get the admin functional since the admin controllers use it for almost every action.
 */
class Core extends \yii\base\Component {

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
	 * @var test whether the web app is multi-site.
	 */
	public $multiSite			= false;

	/**
	 * @var test whether the web app is sub domain or sub directory based in case $multiSite is set to true.
	 */
	public $subDirectory		= true;

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
	 * @var It can be used to check whether apis are available for the app. Most probably apis are provided using OAuth 2.0 for mobile applications. It's used by User class to load permissions when accessed using auth token.
	 */
	public $apis				= false;

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
	public $extraLargeText		= CoreGlobal::TEXT_XLARGE;

	/**
	 * Initialise the CMG Core Component.
	 */
    public function init() {

        parent::init();

		// Initialise core validators
        CoreValidator::initValidators();

		// Set CMSGears alias to be used by all modules, plugins, widgets and themes. It will be located within the vendor directory for composer.
		Yii::setAlias( 'cmsgears', dirname( dirname( dirname( __DIR__ ) ) ) );
    }

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

		return $this->extraLargeText;
	}
}

?>