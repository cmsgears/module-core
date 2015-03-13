<?php
namespace cmsgears\modules\core\common\config;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;
 
use cmsgears\modules\core\common\models\entities\Config;

use cmsgears\modules\core\common\services\OptionService;
use cmsgears\modules\core\common\services\ConfigService;

/**
 * The CoreProperties class provides methods to access the core properties defined in database.
 * It also define the accessor methods for pre-defined properties.
 */
class CoreProperties {

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

	public static function getInstance() {

		if( !isset( self::$instance ) ) {

			self::$instance	= new CoreProperties();

			self::$instance->init();
		}

		return self::$instance;
	}

	public function init() {

		$type				= OptionService::findByCategoryNameKey( CoreGlobal::CATEGORY_CONFIG_TYPE, CoreGlobal::CONFIG_CORE );
		$this->properties	= ConfigService::getKeyValueMapByType( $type->getValue() );
	}

	public function getProperty( $key ) {

		return $this->properties[ key ];
	}

	public function useLocaleMessage() {

		return $this->properties[ self::PROP_LOCALE_MESSAGE ];
	}

	public function getTempDir() {
		
		return $this->properties[ self::PROP_UPLOAD_DIR ] . self::DIR_TEMP;
	}

	public function getAvatarDir() {
		
		return $this->properties[ self::PROP_UPLOAD_DIR ] . self::DIR_AVATAR;
	}

	public function getLanguage() {
		
		return $this->properties[ self::PROP_LANGUAGE ];
	}
	
	public function getCharset() {
		
		return $this->properties[ self::PROP_CHARSET ];
	}

	public function getSiteTitle() {
		
		return $this->properties[ self::PROP_SITE_TITLE ]; 
	}
	
	public function getSiteName() {
		
		return $this->properties[ self::PROP_SITE_NAME ]; 
	}

	public function getSiteUrl() {
		
		return $this->properties[ self::PROP_SITE_URL ]; 
	}
	
	public function getRootUrl() {
		
		return $this->properties[ self::PROP_SITE_URL ] . \Yii::getAlias( '@web' ) ; 
	}
}

?>