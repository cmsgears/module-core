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
 * CommentProperties provide methods to access the properties specific to comments and
 * feedbacks submitted by users.
 *
 * @since 1.0.0
 */
class CommentProperties extends Properties {

	// Variables ---------------------------------------------------

	// Globals ----------------

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

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public static function getInstance() {

		if( !isset( self::$instance ) ) {

			self::$instance	= new CommentProperties();

			self::$instance->init( CoreGlobal::CONFIG_COMMENT );
		}

		return self::$instance;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CommentProperties ---------------------

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
