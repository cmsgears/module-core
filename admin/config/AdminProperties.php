<?php
namespace cmsgears\core\admin\config;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\admin\config\AdminGlobalCore;
 
use cmsgears\core\common\models\entities\Config;

use cmsgears\core\common\services\OptionService;
use cmsgears\core\common\services\ConfigService;

class AdminProperties {

	/**
	 * The property defines the active theme for the site.
	 */	
	const PROP_THEME				= "theme";
	
	/**
	 * The property defines the active theme version to reload browsers in case theme is upgraded.
	 */	
	const PROP_THEME_VERSION		= "theme-version";

	private $properties;

	// Singleton instance
	private static $instance;

	// Constructor and Initialisation ------------------------------

 	private function __construct() {

	}

	/**
	 * Return Singleton instance.
	 */
	public static function getInstance() {

		if( !isset( self::$instance ) ) {

			self::$instance	= new AdminProperties();

			self::$instance->init();
		}

		return self::$instance;
	}

	/**
	 * Initialise the properties from database.
	 */
	public function init() {

		$type				= OptionService::findByCategoryNameKey( CoreGlobal::CATEGORY_CONFIG_TYPE, AdminGlobalCore::CONFIG_ADMIN );
		$this->properties	= ConfigService::getKeyValueMapByType( $type->getValue() );
	}

	/**
	 * Return web property for the specified key.
	 */
	public function getProperty( $key ) {

		return $this->properties[ key ];
	}
	
	/**
	 * Returns current active Theme name.
	 */
	public function getTheme() {
		
		return $this->properties[ self::PROP_THEME ];
	}

	/**
	 * Returns current active Theme version.
	 */
	public function getThemeVersion() {
		
		return $this->properties[ self::PROP_THEME_VERSION ];
	}
}

?>