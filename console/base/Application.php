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

			// TODO: Enable multi-site similar to web app

			$siteSlug = Yii::$app->core->siteSlug;

			$site = SiteService::findBySlug( $siteSlug );

			// Site Found
			if( isset( $site ) ) {

				// Configure Current Site
				Yii::$app->core->site		= $site;
				Yii::$app->core->siteId		= $site->id;

				$coreProperties	= CoreProperties::getInstance();

				Yii::$app->formatter->dateFormat		= $coreProperties->getDateFormat();
				Yii::$app->formatter->timeFormat		= $coreProperties->getTimeFormat();
				Yii::$app->formatter->datetimeFormat	= $coreProperties->getDateTimeFormat();
				Yii::$app->timeZone						= $coreProperties->getTimezone();
			}
		}
		catch( \yii\db\Exception $e ) {

			// do nothing for migrations
		}
	}
}
