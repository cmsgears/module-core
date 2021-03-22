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
 * MailProperties provide methods to access the properties specific to SMTP server.
 *
 * @since 1.0.0
 */
class MailProperties extends Properties {

	// Variables ---------------------------------------------------

	// Globals ----------------

	/**
	 * The property to find whether SMTP is required.
	 */
	const PROP_SMTP = 'smtp';

	/**
	 * The property defines SMTP username.
	 */
	const PROP_SMTP_USERNAME = 'smtp_username';

	/**
	 * The property defines SMTP password.
	 */
	const PROP_SMTP_PASSWORD = 'smtp_password';

	/**
	 * The property defines SMTP host.
	 */
	const PROP_SMTP_HOST = 'smtp_host';

	/**
	 * The property defines SMTP port.
	 */
	const PROP_SMTP_PORT = 'smtp_port';

	/**
	 * The property defines SMTP port.
	 */
	const PROP_SMTP_ENCRYPTION = 'smtp_encryption';

	/**
	 * The property defines whether mailer need to debug.
	 */
	const PROP_DEBUG = 'debug';

	/**
	 * The property defines the default sender name.
	 */
	const PROP_SENDER_NAME = 'sender_name';

	/**
	 * The property defines the default sender email.
	 */
	const PROP_SENDER_EMAIL = 'sender_email';

	/**
	 * The property defines the default contact name. It will be used by contact form.
	 */
	const PROP_CONTACT_NAME = 'contact_name';

	/**
	 * The property defines the default contact email. It will be used by contact form.
	 */
	const PROP_CONTACT_EMAIL = 'contact_email';

	/**
	 * The property defines the default info name.
	 */
	const PROP_INFO_NAME = 'info_name';

	/**
	 * The property defines the default info email. It can be used on contact page.
	 */
	const PROP_INFO_EMAIL = 'info_email';

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

			self::$instance	= new MailProperties();

			self::$instance->init( CoreGlobal::CONFIG_MAIL );
		}

		return self::$instance;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// MailProperties -------------------------

	/**
	 * Returns whether smtp is required for sending mails.
	 */
	public function isSmtp() {

		return $this->properties[ self::PROP_SMTP ];
	}

	/**
	 * Returns smtp username for mails sent via smtp.
	 */
	public function getSmtpUsername() {

		return $this->properties[ self::PROP_SMTP_USERNAME ];
	}

	/**
	 * Returns smtp password for mails sent via smtp.
	 */
	public function getSmtpPassword() {

		return $this->properties[ self::PROP_SMTP_PASSWORD ];
	}

	/**
	 * Returns smtp host for mails sent via smtp.
	 */
	public function getSmtpHost() {

		return $this->properties[ self::PROP_SMTP_HOST ];
	}

	/**
	 * Returns smtp port for mails sent via smtp.
	 */
	public function getSmtpPort() {

		return $this->properties[ self::PROP_SMTP_PORT ];
	}

	/**
	 * Returns smtp encryption for mails sent via smtp.
	 */
	public function getSmtpEncryption() {

		return $this->properties[ self::PROP_SMTP_ENCRYPTION ];
	}

	/**
	 * Returns whether debugging is required for mail api.
	 */
	public function isDebug() {

		return $this->properties[ self::PROP_DEBUG ];
	}

	/**
	 * Returns name for email sender.
	 */
	public function getSenderName() {

		return $this->properties[ self::PROP_SENDER_NAME ];
	}

	/**
	 * Returns email for email sender.
	 */
	public function getSenderEmail() {

		return $this->properties[ self::PROP_SENDER_EMAIL ];
	}

	/**
	 * Returns name for contact form.
	 */
	public function getContactName() {

		return $this->properties[ self::PROP_CONTACT_NAME ];
	}

	/**
	 * Returns email for contact form.
	 */
	public function getContactEmail() {

		return $this->properties[ self::PROP_CONTACT_EMAIL ];
	}

	/**
	 * Returns name for info.
	 */
	public function getInfoName() {

		return $this->properties[ self::PROP_INFO_NAME ];
	}

	/**
	 * Returns email for info.
	 */
	public function getInfoEmail() {

		return $this->properties[ self::PROP_INFO_EMAIL ];
	}

}
