<?php
namespace cmsgears\core\frontend\config;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class SiteProperties extends \cmsgears\core\common\config\Properties {

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

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// WebProperties -------------------------

	public static function getInstance() {

		if( !isset( self::$instance ) ) {

			self::$instance	= new SiteProperties();

			self::$instance->init( CoreGlobal::CONFIG_FRONTEND );
		}

		return self::$instance;
	}

	// Properties

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
