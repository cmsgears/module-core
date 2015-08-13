<?php
namespace cmsgears\core\admin\config;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\config\CmgProperties;

class AdminProperties extends CmgProperties {

	/**
	 * The property defines the active theme for the site.
	 */	
	const PROP_THEME				= "theme";
	
	/**
	 * The property defines the active theme version to reload browsers in case theme is upgraded.
	 */	
	const PROP_THEME_VERSION		= "theme-version";

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

			self::$instance->init( CoreGlobal::CONFIG_ADMIN );
		}

		return self::$instance;
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