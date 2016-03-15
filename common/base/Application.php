<?php
namespace cmsgears\core\common\base;

// Yii Imports
use \Yii;

// CMSGears Imports
use cmsgears\core\common\services\SiteService;

class Application extends \yii\web\Application {

    public function createController( $route ) {

		// find whether multisite is enabled
		if( Yii::$app->cmgCore->multiSite ) {

	        if( $route === '' ) {
	
	            $route = $this->defaultRoute;
	        }
	
	        // double slashes or leading/ending slashes may cause substr problem
	        $route = trim( $route, '/' );

	        if( strpos( $route, '//' ) !== false ) {

	            return false;
	        }
			
			// Sub-Directory
			if( Yii::$app->cmgCore->subDirectory ) {

		        if( strpos( $route, '/' ) !== false ) {
	
					list ( $site, $siteRoute ) = explode( '/', $route, 2 );
	
					// Find Site
					$site = SiteService::findBySlug( $site );
	
					// Site Found
					if( isset( $site ) ) {
						
						// Configure Current Site
						Yii::$app->cmgCore->site 		= $site;
						Yii::$app->cmgCore->siteId		= $site->id;
						Yii::$app->cmgCore->siteSlug 	= $site->slug;
	
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
					Yii::$app->cmgCore->site 		= $site;
					Yii::$app->cmgCore->siteId		= $site->id;
					Yii::$app->cmgCore->siteSlug 	= $site->slug;

					return parent::createController( $route );	
				}
			}
		}
		else {

			$site 	= SiteService::findBySlug( 'main' );

			// Site Found
			if( isset( $site ) ) {

				// Configure Current Site
				Yii::$app->cmgCore->site 		= $site;
				Yii::$app->cmgCore->siteId		= $site->id;
			}
		}

       return parent::createController( $route );
    }
}

?>