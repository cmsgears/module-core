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
 * @author Bhagwat Singh Chouhan <bhagwat.chouhan@gmail.com>
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

		$site			= null;
		$siteRoute		= null;

		// Process multi site request
		if( Yii::$app->core->multiSite ) {

			if( $route === '' ) {

				$route = $this->defaultRoute;
			}

			// double slashes or leading/ending slashes may cause substr problem
			$route = trim( $route, '/' );

			if( strpos( $route, '//' ) !== false ) {

				return false;
			}

			// Sub-Directory
			if( Yii::$app->core->subDirectory ) {

				if( strpos( $route, '/' ) !== false ) {

					list( $site, $siteRoute ) = explode( '/', $route, 2 );

					// Find Site
					$site = SiteService::findBySlug( $site );

					// Site Found
					if( isset( $site ) ) {

						$coreProperties	= CoreProperties::getInstance();

						// Update base url to form urls
						Yii::$app->urlManager->baseUrl	= Yii::$app->urlManager->baseUrl . "/" . $site->name;
					}
				}
			}
			// Sub-Domain
			else {

				// Find Site
				$host		= explode( ".", $_SERVER[ 'HTTP_HOST' ] );
				$siteName	= 'main';

				if( count( $host ) == 2 ) {

					$siteName = 'main';
				}
				else {

					$siteName = array_shift( $host );

					// Accessed via www or localhost
					if( strcmp( $siteName, 'www' ) == 0 || strcmp( $siteName, 'localhost' ) == 0 ) {

						$siteName = 'main';
					}
				}

				$site = SiteService::findBySlug( $siteName );

				// Site Found
				if( isset( $site ) ) {

					$coreProperties	= CoreProperties::getInstance();
				}
			}
		}
		// Process single site request
		else {

			$site = SiteService::findBySlug( 'main' );

			// Site Found
			if( isset( $site ) ) {

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

		if( isset( $site ) ) {

			// Configure Site
			Yii::$app->core->site		= $site;
			Yii::$app->core->siteId		= $site->id;
			Yii::$app->core->siteSlug	= $site->slug;
		}

		if( isset( $siteRoute ) ) {

			return parent::createController( $siteRoute );
		}

		return parent::createController( $route );
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Application ---------------------------

}
