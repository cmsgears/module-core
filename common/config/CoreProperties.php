<?php
namespace cmsgears\core\common\config;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * The CoreProperties class provides methods to access the core properties defined in database.
 * It also define the accessor methods for pre-defined properties.
 */
class CoreProperties extends CmgProperties {

	//TODO Add code for caching the properties

	const DIR_TEMP					= 'temp/';
	const DIR_AVATAR				= 'avatar/';

	/**
	 * The property will be used to decide whether database need to be searched for locale messages.
	 */
	const PROP_LOCALE_MESSAGE		= 'locale message';

	/**
	 * The property defines the default language for the site.
	 */	
	const PROP_LANGUAGE				= 'language';

	/**
	 * The property defines the default locale for the site.
	 */	
	const PROP_LOCALE				= 'locale';

	/**
	 * The property defines the default character set for the site.
	 */	
	const PROP_CHARSET				= 'charset';

	/**
	 * The property defines site title to be used on browser title.
	 */
	const PROP_SITE_TITLE			= 'site title';
	
	/**
	 * The property defines site name to be used at various places like emails, site footer.
	 */
	const PROP_SITE_NAME			= 'site name';

	/**
	 * The property defines site url to be used at various places like emails.
	 */
	const PROP_SITE_URL				= 'site url';

	/**
	 * The property defines admin url to be used at various places like emails.
	 */
	const PROP_ADMIN_URL			= 'admin url';
	
	const PROP_REGISTRATION			= 'registration';

	const PROP_CHANGE_EMAIL			= 'change email';

	const PROP_CHANGE_USERNAME		= 'change username';

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

			self::$instance	= new CoreProperties();

			self::$instance->init( CoreGlobal::CONFIG_CORE );
		}

		return self::$instance;
	}

	/**
	 * Returns Temp directory to store temporary files.
	 */
	public function getTempDir() {

		return Yii::$app->fileManager->uploadDir . self::DIR_TEMP;
	}

	/**
	 * Returns Avatar directory to store avatars.
	 */
	public function getAvatarDir() {

		return Yii::$app->fileManager->uploadDir . self::DIR_AVATAR;
	}

	/**
	 * It can be used to identify whether database based Locale message is required.
	 */
	public function useLocaleMessage() {

		return $this->properties[ self::PROP_LOCALE_MESSAGE ];
	}

	/**
	 * Returns Language to be used by Browser.
	 */
	public function getLanguage() {
		
		return $this->properties[ self::PROP_LANGUAGE ];
	}

	/**
	 * Returns Charset to be used by Browser.
	 */
	public function getLocale() {
		
		return $this->properties[ self::PROP_LOCALE ];
	}

	/**
	 * Returns Charset to be used by Browser.
	 */
	public function getCharset() {
		
		return $this->properties[ self::PROP_CHARSET ];
	}

	/**
	 * Returns Site Title to be used for Browser title.
	 */
	public function getSiteTitle() {
		
		return $this->properties[ self::PROP_SITE_TITLE ]; 
	}

	/**
	 * Returns Site Name to be used at generic places like footer etc. It can be either same or different from Site Title.
	 */
	public function getSiteName() {

		return $this->properties[ self::PROP_SITE_NAME ]; 
	}

	/** 
	 * Returns the site URL for the app. It can be used by admin app to refer to web app.
	 */
	public function getSiteUrl() {

		return $this->properties[ self::PROP_SITE_URL ]; 
	}

	/** 
	 * Returns the site URL for the app. It can be used by admin app to refer to web app.
	 */
	public function getAdminUrl() {

		return $this->properties[ self::PROP_ADMIN_URL ]; 
	}

	/** 
	 * Returns whether registration is allowed from site.
	 */
	public function isRegistration() {

		return $this->properties[ self::PROP_REGISTRATION ]; 
	}

	/** 
	 * Returns whether email change is allowed fur user profile.
	 */
	public function isChangeEmail() {

		return $this->properties[ self::PROP_CHANGE_EMAIL ];
	}

	/** 
	 * Returns whether username change is allowed for user profile.
	 */
	public function isChangeUsername() {

		return $this->properties[ self::PROP_CHANGE_USERNAME ]; 
	}

	/** 
	 * Returns the root URL for the app
	 */
	public function getRootUrl( $admin = false ) {

		if( $admin ) {

			return $this->properties[ self::PROP_ADMIN_URL ] . \Yii::getAlias( '@web' ) ;
		}

		return $this->properties[ self::PROP_SITE_URL ] . \Yii::getAlias( '@web' ) ; 
	}
}

?>