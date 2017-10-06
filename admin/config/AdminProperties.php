<?php
namespace cmsgears\core\admin\config;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\config\CmgProperties;

class AdminProperties extends CmgProperties {

	// Variables ---------------------------------------------------

	// Global -----------------

	const PROP_CMG_POWERED		= 'cmg_powered';

	const PROP_AVATAR_USER		= 'user_avatar';

	const PROP_AVATAR_DEFAULT	= 'default_avatar';

	const PROP_BANNER_DEFAULT	= 'default_banner';

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private static $instance;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// AdminProperties -----------------------

	// Singleton

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

	// Properties

	public function isCmgPowered() {

		return $this->properties[ self::PROP_CMG_POWERED ];
	}

	public function getUserAvatar() {

		return $this->properties[ self::PROP_AVATAR_USER ];
	}

	public function getDefaultAvatar() {

		return $this->properties[ self::PROP_AVATAR_DEFAULT ];
	}

	public function getDefaultBanner() {

		return $this->properties[ self::PROP_BANNER_DEFAULT];
	}
}
