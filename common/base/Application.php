<?php
/**
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 * @license https://www.cmsgears.org/license/
 * @package module
 * @subpackage core
 */
namespace cmsgears\core\common\base;

// Yii Imports
use Yii;
use yii\web\Application as BaseApplication;

// CMG Imports
use cmsgears\core\common\config\CoreProperties;

use cmsgears\core\common\services\entities\SiteService;

/**
 * The Application extends yii web Application and adds multi-site routing support.
 *
 * It read core properties and configure application's date, time format and initialise time-zone.
 *
 * It alter the request and read child site name in case application supports multi-site either at sub-domain or sub-directory level.
 *
 * @since 1.0.0
 */
class Application extends BaseApplication {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Application

	/**
	 * This method extends the controller instance creation and add additional functionality
	 * to initialize application properties by reading core properties.
	 *
	 * It also detect whether multi-site is enable and change route accordingly after reading
	 * the site name from current route. The identified site properties will be used where applicable.
	 *
	 * @see \yii\base\Module#createController()
	 */
	public function createController( $route ) {

		$coreProperties = null;

		$site		= null;
		$siteRoute	= null;

		// double slashes or leading/ending slashes may cause substr problem
		$route = trim( $route, '/' );

		// Process multi site request
		if( Yii::$app->core->multiSite ) {

			if( $route === '' ) {

				$route = $this->defaultRoute;
			}

			if( strpos( $route, '//' ) !== false ) {

				return false;
			}

			// Sub-Directory
			if( Yii::$app->core->subDirectory ) {

				if( strpos( $route, '/' ) !== false ) {

					list( $site, $siteRoute ) = explode( '/', $route, 2 );

					// Find Site
					$site = SiteService::findBySlug( $site );

					// Use main Site
					if( empty( $site ) ) {

						$site = SiteService::findBySlug( 'main' );

						$siteRoute = $route;
					}

					// Site Found
					if( isset( $site ) ) {

						// Configure Site
						Yii::$app->core->site		= $site;
						Yii::$app->core->siteId		= $site->id;
						Yii::$app->core->siteSlug	= $site->slug;

						$coreProperties	= CoreProperties::getInstance();

						$this->configureSiteTheme( $site );
					}
				}
			}
			// Sub-Domain
			else {

				// Find Site
              	$hostname	= $_SERVER[ 'HTTP_HOST' ];
				$host		= explode( ".", $hostname );
				$siteName	= 'main';

				if( count( $host ) == 2 ) {

					$siteName = 'main';
				}
				else {

					$siteName = array_shift( $host );

					// Accessed via www or localhost
					if( strcmp( $siteName, 'www' ) == 0 || in_array( $hostname, Yii::$app->core->testHosts ) ) {

						$siteName = Yii::$app->core->defaultSiteSlug;
					}
				}

				$site = SiteService::findBySlug( $siteName );

				// Site Found
				if( isset( $site ) ) {

					// Configure Site
					Yii::$app->core->site		= $site;
					Yii::$app->core->siteId		= $site->id;
					Yii::$app->core->siteSlug	= $site->slug;

					$coreProperties	= CoreProperties::getInstance();

					$this->configureSiteTheme( $site );
				}
			}
		}
		// Process single site request
		else {

			$siteSlug = Yii::$app->core->siteSlug;

			$site = SiteService::findBySlug( $siteSlug );

			// Site Found
			if( isset( $site ) ) {

				// Configure Site
				Yii::$app->core->site		= $site;
				Yii::$app->core->siteId		= $site->id;
				Yii::$app->core->siteSlug	= $site->slug;

				$coreProperties	= CoreProperties::getInstance();
			}
		}

		// Configure App
		if( isset( $coreProperties ) ) {

			Yii::$app->formatter->dateFormat	= $coreProperties->getDateFormat();
			Yii::$app->formatter->timeFormat	= $coreProperties->getTimeFormat();

			Yii::$app->formatter->datetimeFormat = $coreProperties->getDateTimeFormat();

			Yii::$app->timeZone = $coreProperties->getTimezone();
		}

		if( isset( $siteRoute ) ) {

			return parent::createController( $siteRoute );
		}

		return parent::createController( $route );
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Application ---------------------------

	private function configureSiteTheme( $site ) {

		$theme = $site->theme;

		// Site Theme
		if( Yii::$app->id == Yii::$app->core->appFrontend ) {

			// Theme Found
			if( isset( $theme ) ) {

				$themePath = 'themes\\' . $theme->slug . '\\Theme';

				Yii::$app->view->theme

				Yii::$app->view->theme = new $themePath;

				Yii::$app->assetManager->bundles = require( Yii::getAlias( '@themes' ) . "/assets/$theme->slug/" . ( YII_ENV_PROD ? 'prod.php' : 'dev.php' ) );
			}
		}
		// Admin Theme
		else if( Yii::$app->id == Yii::$app->core->appAdmin ) {

			Yii::$app->assetManager->bundles = require( Yii::getAlias( '@themes' ) . "/assets/admin/" . ( YII_ENV_PROD ? 'prod.php' : 'dev.php' ) );
		}
	}

}
