<?php
namespace cmsgears\modules\core\admin\config;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;
use cmsgears\modules\core\admin\config\AdminGlobalCore;
 
use cmsgears\modules\core\common\models\entities\Config;

use cmsgears\modules\core\common\services\OptionService;
use cmsgears\modules\core\common\services\ConfigService;

class AdminProperties {

	private $properties;

	// Singleton instance
	private static $instance;

	// Constructor and Initialisation ------------------------------

 	private function __construct() {

	}

	public static function getInstance() {

		if( !isset( self::$instance ) ) {

			self::$instance	= new AdminProperties();

			self::$instance->init();
		}

		return self::$instance;
	}

	public function init() {

		$type				= OptionService::findByCategoryNameKey( CoreGlobal::CATEGORY_CONFIG_TYPE, AdminGlobalCore::CONFIG_ADMIN );
		$this->properties	= ConfigService::getKeyValueMapByType( $type->getValue() );
	}

	public function getProperty( $key ) {

		return $this->properties[ key ];
	}
}

?>