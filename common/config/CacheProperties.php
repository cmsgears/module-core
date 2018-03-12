<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\config;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * CacheProperties provide methods to access the properties specific to caching.
 *
 * @since 1.0.0
 */
class CacheProperties extends Properties {

	// Variables ---------------------------------------------------

	// Globals ----------------

	const PROP_CACHING				= 'caching';

	const PROP_CACHE_TYPE			= 'cache_type';

	const PROP_CACHE_DURATION		= 'cache_duration';

	const PROP_CACHING_HTML 		= 'html_caching';

	const PROP_CACHING_JSON 		= 'json_caching';

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private static $instance;

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

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

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CacheProperties -----------------------

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
