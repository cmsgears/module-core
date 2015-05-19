<?php
namespace cmsgears\core\common\components;

// Yii Imports
use \Yii;
use yii\base\Component;

// CMG Imports
use cmsgears\core\common\validators\CoreValidator;

/**
 * The core component for CMSGears based sites. It must be initialised for app bootstrap using the name cmgCore. 
 * It define the post login and logout path to redirect user to a different path than the default one. Though ajax 
 * based login need to specify the path within the javascript code.
 * 
 * All the admin sites must set useRbac to true to get the admin functional since the admin controllers use it for almost every action. 
 */
class Core extends Component {

	/**
	 * @var identify the currently active site based on the url request.
	 */
	public $siteName			= "main";

	/**
	 * @var test whether the web app is multi-site.
	 */
	public $multiSite			= false;

	/**
	 * @var test whether the web app is sub domain or sub directory based in case $multiSite is set to true.
	 */
	public $subDirectory		= true;

	/**
	 * @var default redirect path to be used for post login. It will be used by login action of Site Controller to redirect users 
	 * after successful login in case use role home url is not set.
	 */
	public $loginRedirectPage	= "site/index";

	/**
	 * @var Redirect path to be used for post logout.
	 */
	public $logoutRedirectPage	= "site/login";

	/**
	 * @var The indicator whether CMG RBAC has to be used for the project. All the admin sites must set this to true. Though it's optional for 
	 * front end sites. The front end sites can use either CMG RBAC or Yii's RBAC system.
	 */
	public $useRbac				= false;

	/**
	 * @var The default filter class available for CMG RBAC system. A different filter can be used based on project needs.
	 */
	public $rbacFilterClass		= "cmsgears\core\common\\filters\RbacFilter"; 

	/**
	 * @var It store the list of filters available for the Rbac Filter and works only when rbac is enabled.
	 * A Controller can define filters to be performed for each action while checking the permission.
	 */
	public $rbacFilters			= [];

	/**
	 * @var It can be used to check whether apis are available for the app. Most probable apis are provided using OAuth 2.0.
	 */
	public $apis				= false;

	/**
	 * @var The WYSIWYG editor widget class. It will be used by Core Module to edit newsletter content. The dependent modules can also use it to edit the html content.
	 */
	public $editorClass			= null;

	/**
	 * Initialise the CMG Core Component.
	 */
    public function init() {

        parent::init();

		// Initialise core validators
        CoreValidator::initValidators();

		// Set CMSGears alias to be used by all modules, plugins, widgets and themes
		Yii::setAlias( "cmsgears", dirname( dirname( dirname( __DIR__ ) ) ) );
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

	public function getSiteName() {

		return $this->siteName;
	}

	public function isMultiSite() {

		return $this->multiSite;
	}

	public function isSubDirectory() {

		return $this->subDirectory;
	}

	/**
	 * The method getLoginRedirectPage returns the default path to be redirected after login using the non-ajax based form.
	 * @return path used for post login
	 */
	public function getLoginRedirectPage() {

		return $this->loginRedirectPage;
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
}

?>