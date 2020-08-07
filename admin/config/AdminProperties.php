<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\config;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * AdminProperties provide methods to access the properties specific to admin.
 *
 * @since 1.0.0
 */
class AdminProperties extends \cmsgears\core\common\config\Properties {

	// Variables ---------------------------------------------------

	// Global -----------------

	const PROP_CMG_POWERED = 'cmg_powered';

	const PROP_AVATAR_DEFAULT = 'default_avatar';

	const PROP_AVATAR_USER = 'user_avatar';

	const PROP_AVATAR_MAIL = 'mail_avatar';

	const PROP_BANNER_DEFAULT = 'default_banner';

	const PROP_BANNER_PAGE = 'page_banner';

	const PROP_BANNER_MAIL = 'mail_banner';

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private static $instance;

	// Constructor and Initialisation ------------------------------

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

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// AdminProperties -----------------------

	public function isCmgPowered() {

		return $this->properties[ self::PROP_CMG_POWERED ];
	}

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

}
