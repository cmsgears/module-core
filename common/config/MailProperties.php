<?php
namespace cmsgears\core\common\config;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class MailProperties extends CmgProperties {

	//TODO Add code for caching the properties

	/**
	 * The property to find whether SMTP is required.
	 */
	const PROP_SMTP				= "smtp";
	
	/**
	 * The property defines SMTP username.
	 */
	const PROP_SMTP_USERNAME	= "smtp username";
	
	/**
	 * The property defines SMTP password.
	 */
	const PROP_SMTP_PASSWORD	= "smtp password";
	
	/**
	 * The property defines SMTP host.
	 */	
	const PROP_SMTP_HOST		= "smtp host";
	
	/**
	 * The property defines SMTP port.
	 */
	const PROP_SMTP_PORT		= "smtp port";

	/**
	 * The property defines whether mailer need to debug.
	 */
	const PROP_DEBUG			= "debug";

	/**
	 * The property defines the default sender name.
	 */
	const PROP_SENDER_NAME		= "sender name";

	/**
	 * The property defines the default sender email.
	 */
	const PROP_SENDER_EMAIL		= "sender email";
	
	/**
	 * The property defines the default contact name. It will be used by contact form.
	 */
	const PROP_CONTACT_NAME		= "contact name";
	
	/**
	 * The property defines the default contact email. It will be used by contact form.
	 */
	const PROP_CONTACT_EMAIL	= "contact email";
	
	/**
	 * The property defines the default info name.
	 */
	const PROP_INFO_NAME		= "info name";

	/**
	 * The property defines the default info email. It can be used on contact page.
	 */
	const PROP_INFO_EMAIL		= "info email";

	// Singleton instance
	private static $instance;

	// Constructor and Initialisation ------------------------------

 	private function __construct() {

	}

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

	/**
	 * Return mail property for the specified key.
	 */
	public function getProperty( $key ) {

		return $this->properties[ key ];
	}

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

?>