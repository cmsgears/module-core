<?php
namespace cmsgears\core\common\config;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
 
use cmsgears\core\common\models\entities\Config;

use cmsgears\core\common\services\OptionService;
use cmsgears\core\common\services\ConfigService;

/**
 * The CoreProperties class provides methods to access the core properties defined in database.
 * It also define the accessor methods for pre-defined properties.
 */
class CoreProperties {

	//TODO Add code for caching the properties

	const DIR_TEMP					= "temp/";
	const DIR_AVATAR				= "avatar/";

	/**
	 * The property will be used to decide whether database need to be searched for locale messages.
	 */
	const PROP_LOCALE_MESSAGE		= "locale message";
	
	/**
	 * The property defines the default language for the site.
	 */	
	const PROP_LANGUAGE				= "language";
	
	/**
	 * The property defines the default character set for the site.
	 */	
	const PROP_CHARSET				= "charset";
	
	/**
	 * The property defines site title to be used on browser title.
	 */
	const PROP_SITE_TITLE			= "site title";
	
	/**
	 * The property defines site name to be used at various places like emails, site footer.
	 */
	const PROP_SITE_NAME			= "site name";

	/**
	 * The property defines site url to be used at various places like emails. The admin site can use this to make url for front end sites.
	 */
	const PROP_SITE_URL				= "site url";

	/**
	 * The map stores all the core properties. It can be queried for properties not listed above.
	 */
	private $properties;
	
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

			self::$instance->init();
		}

		return self::$instance;
	}
	
	/*
	 * Initialise the properties from database.
	 */ 
	public function init() {

		$type				= OptionService::findByNameCategoryName( CoreGlobal::CONFIG_CORE, CoreGlobal::CATEGORY_CONFIG_TYPE );
		$this->properties	= ConfigService::getNameValueMapByType( $type->value );
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
	 * Return core property for the specified key.
	 */
	public function getProperty( $key ) {

		return $this->properties[ key ];
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
	 * Returns the root URL for the app
	 */
	public function getRootUrl() {

		return $this->properties[ self::PROP_SITE_URL ] . \Yii::getAlias( '@web' ) ; 
	}
}

?>