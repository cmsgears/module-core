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

/**
 * SiteProperties provides access to properties specific to frontend application.
 *
 * @since 1.0.0
 */
class SiteProperties extends \cmsgears\core\common\config\Properties {

	// Variables ---------------------------------------------------

	// Global -----------------

	const PROP_AVATAR_DEFAULT = 'default_avatar';

	const PROP_AVATAR_USER = 'user_avatar';

	const PROP_AVATAR_MAIL = 'mail_avatar';

	const PROP_BANNER_DEFAULT = 'default_banner';

	const PROP_BANNER_PAGE = 'page_banner';

	const PROP_BANNER_MAIL = 'mail_banner';

	const PROP_FONTS = 'fonts';

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

	public function getDefaultAvatar() {

		return $this->properties[ self::PROP_AVATAR_DEFAULT ];
	}

	public function getUserAvatar() {

		return $this->properties[ self::PROP_AVATAR_USER ];
	}

	public function getMailAvatar() {

		return $this->properties[ self::PROP_AVATAR_MAIL ];
	}

	public function getDefaultBanner() {

		return $this->properties[ self::PROP_BANNER_DEFAULT ];
	}

	public function getPageBanner() {

		return $this->properties[ self::PROP_BANNER_PAGE ];
	}

	public function getMailBanner() {

		return $this->properties[ self::PROP_BANNER_MAIL ];
	}

	public function getFonts() {

		return $this->properties[ self::PROP_FONTS ];
	}

}
