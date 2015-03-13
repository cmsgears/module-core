<?php
namespace cmsgears\modules\core\common\components;

use \Yii;
use yii\base\Component;

// CMG Imports
use cmsgears\modules\core\common\validators\CoreValidator;

/**
 * The core component for CMSGears based sites. It must be initialised for app bootstrap using the name cmgCore. It defines the various mappings for modules, 
 * themes and widgets available for the app. It also define the post login and logout path to redirect user to a different path than the default one. Though
 * ajax based login need to specify the path within the javascript code. 
 * 
 * All the admin sites must set useRbac to true to get the admin functional since the admin controllers use it for almost every action. 
 */
class Core extends Component {

	/**
	 * @var Modules map having name as key and root path as value for conditional usage.
	 */
    public $modulesMap 	= [];

	/**
	 * @var Themes map having name as key and root path as value for conditional usage.
	 */
    public $themesMap 	= [];

	/**
	 * @var Widgets map having name as key and root path as value for conditional usage.
	 */
    public $widgetsMap 	= [];

	/**
	 * @var Redirect path to be used for post login. It will be used by login action of Site Controller to redirect users after successful login.
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
	public $rbacFilterClass		= "cmsgears\modules\core\common\\filters\RbacFilter";
	
	/**
	 * @var The WYSIWYG editor widget class.
	 */
	public $editorClass			= null;

	/**
	 * Initialise the CMG Core Component.
	 */
    public function init() {

        parent::init();

		// Initialise core validators
        CoreValidator::initValidators();
    }

	/**
	 * The hasModule method check whether a module is available for the app. We can use it for conditional cases. Ex: Sidebar can show the 
	 * module links if it's available.
	 * @param string $name the module name
	 * @return whether module name and root path are set in app config file 
	 */
    public function hasModule( $name ) {

        return isset( $this->modulesMap[$name] );
    }

	/**
	 * The hasTheme method check whether a theme is available for the app. We can use it to make certain things available in a different theme 
	 * apart from currently active theme.
	 * @param string $name the theme name
	 * @return whether theme name and root path are set in app config file
	 */
    public function hasTheme( $name ) {

        return isset( $this->themesMap[$name] );
    }

	/**
	 * The hasWidget method check whether a widget is available for the app. The theme can show the widgets conditionally based on availablility.
	 * @param string $name the widget name
	 * @return whether widget name and root path are set in app config file
	 */
    public function hasWidget( $name ) {

        return isset( $this->widgetsMap[$name] );
    }

	/**
	 * The method getLoginRedirectPage returns the path to be redirected after login using the non-ajax based form.
	 * @return path used for post login
	 */
	public function getLoginRedirectPage() {

		return $this->loginRedirectPage;
	}

	/**
	 * The method getLogoutRedirectPage returns the path to be redirected after logout using the non-ajax based form.
	 * @return path used for post logout
	 */
	public function getLogoutRedirectPage() {

		return $this->logoutRedirectPage;
	}

	/**
	 * The method isRbac is used by the User class.
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
	 * The method getEditorClass is used by the views to make a text area to edit html.
	 * @return the class name
	 */	
	public function getEditorClass() {

		return $this->editorClass;
	}
}

?>