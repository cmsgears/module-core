<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\config;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * The CoreProperties class provides methods to access the core properties defined in database.
 * It also define the accessor methods for pre-defined properties.
 *
 * @since 1.0.0
 */
class CoreProperties extends Properties {

	// Variables ---------------------------------------------------

	// Globals ----------------

	const DIR_TEMP		= 'temp/';
	const DIR_AVATAR	= 'avatar/';

	/**
	 * The property will be used to decide whether database need to be searched for locale messages.
	 */
	const PROP_LOCALE_MESSAGE = 'locale_message';

	/**
	 * The property defines the default language for the site.
	 */
	const PROP_LANGUAGE	= 'language';

	/**
	 * The property defines the default locale for the site.
	 */
	const PROP_LOCALE = 'locale';

	/**
	 * The property defines the default character set for the site.
	 */
	const PROP_CHARSET = 'charset';

	/**
	 * The property defines site title to be used on browser title.
	 */
	const PROP_SITE_TITLE = 'site_title';

	/**
	 * The property defines site name to be used at various places like emails, site footer.
	 */
	const PROP_SITE_NAME = 'site_name';

	/**
	 * The property defines site url to be used at various places like emails.
	 */
	const PROP_SITE_URL = 'site_url';

	/**
	 * The property defines admin url to be used at various places like emails.
	 */
	const PROP_ADMIN_URL = 'admin_url';

	const PROP_RESOURCE_URL = 'resource_url';

	const PROP_REGISTRATION = 'registration';

	const PROP_LOGIN = 'login';

	const PROP_CHANGE_EMAIL = 'change_email';

	const PROP_CHANGE_USERNAME = 'change_username';

	const PROP_CHANGE_MOBILE = 'change_mobile';

	const PROP_FORMAT_DATE = 'date_format';

	const PROP_FORMAT_TIME = 'time_format';

	const PROP_FORMAT_DATE_TIME = 'date_time_format';

	const PROP_TIMEZONE = 'timezone';

	/**
	 * It checks whether system can login user on activation.
	 */
	const PROP_AUTO_LOGIN = 'auto_login';

	const PROP_AUTO_LOAD = 'auto_load';

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

			self::$instance	= new CoreProperties();

			self::$instance->init( CoreGlobal::CONFIG_CORE );
		}

		return self::$instance;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CoreProperties ------------------------

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

		$siteUrl 	= $this->properties[ self::PROP_SITE_URL ];
		$host		= explode( '.', $_SERVER[ 'HTTP_HOST' ] );
		$siteSlug 	= array_shift( $host );

		// Child Domain
		if( $siteSlug !== 'www' ) {

			$siteUrl = preg_replace( '/www\./', "{$siteSlug}.", $siteUrl );
		}

		return $siteUrl;
	}

	/**
	 * Returns the site URL for the app. It can be used by admin app to refer to web app.
	 */
	public function getAdminUrl() {

		$adminUrl	= $this->properties[ self::PROP_ADMIN_URL ];
		$host		= explode( '.', $_SERVER[ 'HTTP_HOST' ] );
		$siteSlug 	= array_shift( $host );

		// Child Domain
		if( $siteSlug !== 'www' ) {

			$adminUrl = preg_replace( '/www\./', "{$siteSlug}.", $adminUrl );
		}

		return $adminUrl;
	}

	public function getResourceUrl() {

		$resourceUrl 	= $this->properties[ self::PROP_RESOURCE_URL ];
		$host			= explode( '.', $_SERVER[ 'HTTP_HOST' ] );
		$siteSlug 		= array_shift( $host );

		// Child Domain
		if( $siteSlug !== 'www' ) {

			$resourceUrl = preg_replace( '/www\./', "{$siteSlug}.", $resourceUrl );
		}

		return $resourceUrl;
	}

	/**
	 * Returns whether registration is allowed from site.
	 */
	public function isRegistration() {

		return $this->properties[ self::PROP_REGISTRATION ];
	}

	/**
	 * Returns whether login is allowed from site.
	 */
	public function isLogin() {

		return $this->properties[ self::PROP_LOGIN ];
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
	 * Returns whether mobile number change is allowed for user profile.
	 */
	public function isChangeMobile() {

		return $this->properties[ self::PROP_CHANGE_MOBILE ];
	}

	public function getDateFormat() {

		return $this->properties[ self::PROP_FORMAT_DATE ];
	}

	public function getTimeFormat() {

		return $this->properties[ self::PROP_FORMAT_TIME ];
	}

	public function getDateTimeFormat() {

		return $this->properties[ self::PROP_FORMAT_DATE_TIME ];
	}

	public function getTimezone() {

		return $this->properties[ self::PROP_TIMEZONE ];
	}

	public function isAutoLogin() {

		return $this->properties[ self::PROP_AUTO_LOGIN ];
	}

	public function isAutoLoad() {

		return $this->properties[ self::PROP_AUTO_LOAD ];
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
