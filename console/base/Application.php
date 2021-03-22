<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\console\base;

// Yii Imports
use Yii;
use yii\base\Exception;

// CMSGears Imports
use cmsgears\core\common\config\CoreProperties;

use cmsgears\core\common\models\entities\Site;

/**
 * The base application for Console based job execution.
 *
 * @since 1.0.0
 */
class Application extends \yii\console\Application {

	public function init() {

		parent::init();

		try {

			// TODO: Enable multi-site similar to web app

			$site = Site::findBySlug( Yii::$app->core->siteSlug );

			// Site Found
			if( isset( $site ) ) {

				// Configure Current Site
				Yii::$app->core->site	= $site;
				Yii::$app->core->siteId	= $site->id;

				$coreProperties	= CoreProperties::getInstance();

				Yii::$app->formatter->dateFormat		= $coreProperties->getDateFormat();
				Yii::$app->formatter->timeFormat		= $coreProperties->getTimeFormat();
				Yii::$app->formatter->datetimeFormat	= $coreProperties->getDateTimeFormat();
				Yii::$app->timeZone						= $coreProperties->getTimezone();
			}
		}
		catch( Exception $e ) {

			// do nothing for migrations
		}
	}

}
