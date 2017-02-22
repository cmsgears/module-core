<?php
namespace cmsgears\core\frontend\config;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class SiteProperties extends \cmsgears\core\common\config\CmgProperties {

	// Variables ---------------------------------------------------

	// Global -----------------

	const PROP_AVATAR_USER			= 'avatar_user';
	const PROP_AVATAR_SITE			= 'avatar_site';
	const PROP_COMMENTS				= 'comments';
	const PROP_COMMENTS_USER		= 'comments_user';
	const PROP_COMMENTS_RECENT		= 'comments_recent';
	const PROP_COMMENTS_LIMIT		= 'comments_limit';
	const PROP_COMMENTS_EMAIL		= 'comments_email';
	const PROP_COMMENTS_AUTO		= 'comments_auto';
	const PROP_COMMENTS_BLACKLIST	= 'comments_blacklist';

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

	public function isComments() {

		return $this->properties[ self::PROP_COMMENTS ];
	}

	public function isUserComments() {

		return $this->properties[ self::PROP_COMMENTS_USER ];
	}

	public function getCommentsLimit() {

		return $this->properties[ self::PROP_COMMENTS_LIMIT ];
	}

	public function isCommentsEmail() {

		return $this->properties[ self::PROP_COMMENTS_EMAIL ];
	}

	public function isCommentsAuto() {

		return $this->properties[ self::PROP_COMMENTS_AUTO ];
	}

	public function getCommentsFilter() {

		return $this->properties[ self::PROP_COMMENTS_FILTER ];
	}
}
