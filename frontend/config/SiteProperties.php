<?php
namespace cmsgears\core\frontend\config;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class SiteProperties extends \cmsgears\core\common\config\CmgProperties {

	// Variables ---------------------------------------------------

	// Global -----------------

	const PROP_AVATAR_USER		= 'avatar_user';
	const PROP_AVATAR_SITE		= 'avatar_site';

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

	public function getSiteAvatar() {

		return $this->properties[ self::PROP_AVATAR_SITE ];
	}
}
