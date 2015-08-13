<?php
namespace cmsgears\core\common\multisite;

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
					$site = SiteService::findByName( $site );
	
					// Site Found
					if( isset( $site ) ) {
						
						// Set Current Site in Session
						Yii::$app->session->set('siteName', $site->name );
	
						Yii::$app->cmgCore->siteName 	= $site->name;
	
						Yii::$app->urlManager->baseUrl	= Yii::$app->urlManager->baseUrl . "/" . $site->name; 
	
						return parent::createController( $siteRoute );	
					}
		        }
			}
			// Sub-Domain
			else {

				// Find Site
				$siteName 		= array_shift((explode(".",$_SERVER['HTTP_HOST'])));

				if( !isset( $siteName ) || strcmp( $siteName, 'www' ) == 0 ) {
				
					$siteName	= 'main';
				}

				$site 			= SiteService::findByName( $siteName );

				// Site Found
				if( isset( $site ) ) {

					// Set Current Site in Session
					Yii::$app->session->set('siteName', $site->name );

					Yii::$app->cmgCore->siteName 	= $site->name;

					return parent::createController( $route );	
				}
			}
		}

       return parent::createController( $route );
    }
}

?>