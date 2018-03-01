<?php
namespace cmsgears\core\common\config;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class CommentProperties extends Properties {

	// Variables ---------------------------------------------------

	// Global -----------------

	const PROP_COMMENTS				= 'comments';
	const PROP_COMMENTS_USER		= 'comments_user';
	const PROP_COMMENTS_RECENT		= 'comments_recent';
	const PROP_COMMENTS_LIMIT		= 'comments_limit';
	const PROP_COMMENTS_EMAIL_ADMIN	= 'comments_email_admin';
	const PROP_COMMENTS_EMAIL_USER	= 'comments_email_user';
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

			self::$instance	= new CommentProperties();

			self::$instance->init( CoreGlobal::CONFIG_COMMENT );
		}

		return self::$instance;
	}

	// Properties

	public function isComments() {

		return $this->properties[ self::PROP_COMMENTS ];
	}

	public function isUserComments() {

		return $this->properties[ self::PROP_COMMENTS_USER ];
	}

	public function getCommentsLimit() {

		return $this->properties[ self::PROP_COMMENTS_LIMIT ];
	}

	public function isCommentsEmailAdmin() {

		return $this->properties[ self::PROP_COMMENTS_EMAIL_ADMIN ];
	}

	public function isCommentsEmailUser() {

		return $this->properties[ self::PROP_COMMENTS_EMAIL_USER ];
	}

	public function isCommentsAuto() {

		return $this->properties[ self::PROP_COMMENTS_AUTO ];
	}

	public function getCommentsFilter() {

		return $this->properties[ self::PROP_COMMENTS_FILTER ];
	}
}
