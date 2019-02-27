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

	const PROP_CACHE_DURATION		= 'cache_duration';

	const PROP_DEFAULT_CACHE		= 'default_cache';

	const PROP_PRIMARY_CACHE 		= 'primary_cache';

	const PROP_SECONDARY_CACHE 		= 'secondary_cache';

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

	/**
	 * Check whether caching is enabled.
	 *
	 * @return boolean
	 */
	public function isCaching() {

		return $this->properties[ self::PROP_CACHING ];
	}

	/**
	 * Returns the cache duration.
	 *
	 * @return integer
	 */
	public function getCacheDuration() {

		return $this->properties[ self::PROP_CACHE_DURATION ];
	}

	/**
	 * Returns the default caching mechanism.
	 *
	 * @return string
	 */
	public function getDefaultCache() {

		return $this->properties[ self::PROP_DEFAULT_CACHE ];
	}

	/**
	 * Returns the primary caching mechanism.
	 *
	 * @return string
	 */
	public function getPrimaryCache() {

		return $this->properties[ self::PROP_PRIMARY_CACHE ];
	}

	/**
	 * Returns the secondary caching mechanism.
	 *
	 * @return string
	 */
	public function getSecondaryCache() {

		return $this->properties[ self::PROP_SECONDARY_CACHE ];
	}

}
