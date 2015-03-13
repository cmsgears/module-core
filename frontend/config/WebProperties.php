<?php
namespace cmsgears\modules\core\frontend\config;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;
use cmsgears\modules\core\frontend\config\WebGlobalCore;
 
use cmsgears\modules\core\common\models\entities\Config;

use cmsgears\modules\core\common\services\OptionService;
use cmsgears\modules\core\common\services\ConfigService;

class WebProperties {

	private $properties;

	// Singleton instance
	private static $instance;

	// Constructor and Initialisation ------------------------------

 	private function __construct() {

	}

	public static function getInstance() {

		if( !isset( self::$instance ) ) {

			self::$instance	= new WebProperties();

			self::$instance->init();
		}

		return self::$instance;
	}

	public function init() {

		$type				= OptionService::findByCategoryNameKey( CoreGlobal::CATEGORY_CONFIG_TYPE, WebGlobalCore::CONFIG_SITE );
		$this->properties	= ConfigService::getKeyValueMapByType( $type->getValue() );
	}

	public function getProperty( $key ) {

		return $this->properties[ key ];
	}
}