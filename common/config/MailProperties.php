<?php
namespace cmsgears\modules\core\common\config;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;
 
use cmsgears\modules\core\common\models\entities\Config;

use cmsgears\modules\core\common\services\OptionService;
use cmsgears\modules\core\common\services\ConfigService;

class MailProperties {
	
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

	private $properties;

	// Singleton instance
	private static $instance;

	// Constructor and Initialisation ------------------------------

 	private function __construct() {

	}

	public static function getInstance() {

		if( !isset( self::$instance ) ) {

			self::$instance	= new MailProperties();

			self::$instance->init();
		}

		return self::$instance;
	}

	public function init() {

		$type				= OptionService::findByCategoryNameKey( CoreGlobal::CATEGORY_CONFIG_TYPE, CoreGlobal::CONFIG_MAIL );
		$this->properties	= ConfigService::getKeyValueMapByType( $type->getValue() );
	}

	public function getProperty( $key ) {
		
		return $this->properties[ key ];
	}

	public function isSmtp() {

		return $this->properties[ self::PROP_SMTP ];
	}

	public function getSmtpUsername() {

		return $this->properties[ self::PROP_SMTP_USERNAME ];
	}

	public function getSmtpPassword() {

		return $this->properties[ self::PROP_SMTP_PASSWORD ];
	}

	public function getSmtpHost() {

		return $this->properties[ self::PROP_SMTP_HOST ];
	}
	
	public function getSmtpPort() {

		return $this->properties[ self::PROP_SMTP_PORT ];
	}

	public function isDebug() {

		return $this->properties[ self::PROP_DEBUG ];
	}

	public function getSenderName() {

		return $this->properties[ self::PROP_SENDER_NAME ];
	}
	
	public function getSenderEmail() {

		return $this->properties[ self::PROP_SENDER_EMAIL ];
	}
	
	public function getContactName() {

		return $this->properties[ self::PROP_CONTACT_NAME ];
	}
	
	public function getContactEmail() {

		return $this->properties[ self::PROP_CONTACT_EMAIL ];
	}
	
	public function getInfoName() {

		return $this->properties[ self::PROP_INFO_NAME ];
	}
	
	public function getInfoEmail() {

		return $this->properties[ self::PROP_INFO_EMAIL ];
	}
}

?>