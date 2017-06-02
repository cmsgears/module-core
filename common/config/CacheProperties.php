<?php
namespace cmsgears\core\common\config;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * The CoreProperties class provides methods to access the core properties defined in database.
 * It also define the accessor methods for pre-defined properties.
 */
class CacheProperties extends CmgProperties {

	// Variables ---------------------------------------------------

	//TODO Add code for caching the properties

	// Global -----------------

	const PROP_CACHING				= 'caching';

	const PROP_CACHE_TYPE			= 'cache_type';

	const PROP_CACHE_DURATION		= 'cache_duration';

	const PROP_CACHING_HTML 		= 'html_caching';

	const PROP_CACHING_JSON 		= 'json_caching';

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private static $instance;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// CoreProperties ------------------------

	// Singleton

	/**
	 * Return Singleton instance.
	 */
	public static function getInstance() {

		if( !isset( self::$instance ) ) {

			self::$instance	= new CacheProperties();

			self::$instance->init( CoreGlobal::CONFIG_CACHE );
		}

		return self::$instance;
	}

	// Properties

	public function isCaching() {

		return $this->properties[ self::PROP_CACHING ];
	}

	public function getCacheType() {

		return $this->properties[ self::PROP_CACHE_TYPE ];
	}

	public function getCacheDuration() {

		return $this->properties[ self::PROP_CACHE_DURATION ];
	}

	public function isHtmlCaching() {

		return $this->properties[ self::PROP_CACHING_HTML ];
	}

	public function isJsonCaching() {

		return $this->properties[ self::PROP_CACHING_JSON ];
	}
}
