<?php
namespace cmsgears\core\admin\config;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\config\CmgProperties;

class AdminProperties extends CmgProperties {

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
}

?>