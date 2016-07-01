<?php
namespace cmsgears\core\common\base;

// Yii Imports
use \Yii;
use yii\base\Component;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\config\CoreProperties;

use cmsgears\core\common\services\entities\SiteService;

class Application extends \yii\web\Application {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Application

    public function createController( $route ) {

		// site config
		$coreProperties	= CoreProperties::getInstance();

		Yii::$app->formatter->dateFormat		= $coreProperties->getDateFormat();
		Yii::$app->formatter->timeFormat		= $coreProperties->getTimeFormat();
		Yii::$app->formatter->datetimeFormat	= $coreProperties->getDateTimeFormat();
		Yii::$app->timeZone						= $coreProperties->getTimezone();

		// find whether multisite is enabled
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

					list ( $site, $siteRoute ) = explode( '/', $route, 2 );

					// Find Site
					$site = SiteService::findBySlug( $site );

					// Site Found
					if( isset( $site ) ) {

						// Configure Current Site
						Yii::$app->core->site 		= $site;
						Yii::$app->core->siteId		= $site->id;
						Yii::$app->core->siteSlug 	= $site->slug;

						Yii::$app->urlManager->baseUrl	= Yii::$app->urlManager->baseUrl . "/" . $site->name;

						return parent::createController( $siteRoute );
					}
		        }
			}
			// Sub-Domain
			else {

				// Find Site
				$siteName 		= array_shift( ( explode( ".", $_SERVER[ 'HTTP_HOST' ] ) ) );

				if( !isset( $siteName ) || strcmp( $siteName, 'www' ) == 0 ) {

					$siteName	= 'main';
				}

				$site 			= SiteService::findBySlug( $siteName );

				// Site Found
				if( isset( $site ) ) {

					// Configure Current Site
					Yii::$app->core->site 		= $site;
					Yii::$app->core->siteId		= $site->id;
					Yii::$app->core->siteSlug 	= $site->slug;

					return parent::createController( $route );
				}
			}
		}
		else {

			$site 	= SiteService::findBySlug( 'main' );

			// Site Found
			if( isset( $site ) ) {

				// Configure Current Site
				Yii::$app->core->site 		= $site;
				Yii::$app->core->siteId		= $site->id;
			}
		}

       return parent::createController( $route );
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Application ---------------------------

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Application ---------------------------

}

?>