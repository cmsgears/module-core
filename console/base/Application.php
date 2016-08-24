<?php
namespace cmsgears\core\console\base;

// Yii Imports
use \Yii;

// CMSGears Imports
use cmsgears\core\common\config\CoreProperties;

use cmsgears\core\common\services\entities\SiteService;

class Application extends \yii\console\Application {

    public function init() {

		parent::init();

		try {

			// site config
			$coreProperties	= CoreProperties::getInstance();

			Yii::$app->formatter->dateFormat		= $coreProperties->getDateFormat();
			Yii::$app->formatter->timeFormat		= $coreProperties->getTimeFormat();
			Yii::$app->formatter->datetimeFormat	= $coreProperties->getDateTimeFormat();
			Yii::$app->timeZone						= $coreProperties->getTimezone();

			// TODO: Enable multi-site similar to web app

			$site 	= SiteService::findBySlug( 'main' );

			// Site Found
			if( isset( $site ) ) {

				// Configure Current Site
				Yii::$app->core->site 		= $site;
				Yii::$app->core->siteId		= $site->id;
			}
		}
		catch( \yii\db\Exception $e ) {

			// do nothing for migrations
		}
    }
}
