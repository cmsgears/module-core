<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\frontend\config;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\config\Properties;

/**
 * SiteProperties provides access to properties specific to frontend application.
 *
 * @since 1.0.0
 */
class SiteProperties extends Properties {

	// Variables ---------------------------------------------------

	// Global -----------------

	const PROP_AVATAR_USER		= 'user_avatar';

	const PROP_AVATAR_DEFAULT	= 'default_avatar';

	const PROP_BANNER_DEFAULT	= 'default_banner';

	const PROP_FONTS			= 'fonts';

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private static $instance;

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public static function getInstance() {

		if( !isset( self::$instance ) ) {

			self::$instance	= new SiteProperties();

			self::$instance->init( CoreGlobal::CONFIG_FRONTEND );
		}

		return self::$instance;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Properties ----------------------------

	public function getUserAvatar() {

		return $this->properties[ self::PROP_AVATAR_USER ];
	}

	public function getDefaultAvatar() {

		return $this->properties[ self::PROP_AVATAR_DEFAULT ];
	}

	public function getDefaultBanner() {

		return $this->properties[ self::PROP_BANNER_DEFAULT ];
	}

	public function getFonts() {

		return $this->properties[ self::PROP_FONTS ];
	}

}
