<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\api\controllers\base;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\frontend\config\SiteProperties;

use cmsgears\core\api\controllers\base\RestTrait;

abstract class Controller extends \yii\rest\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	/**
	 * The model service for primary model if applicable. It can be obtained either via
	 * factory component or instantiated within controller constructor or init method.
	 *
	 * @var \cmsgears\core\common\services\interfaces\base\IActiveRecordService
	 */
	public $modelService;

	/**
	 * The primary model in action. It can be discovered while applying a filter and other
	 * filters and action can use it directly.
	 *
	 * @var \cmsgears\core\common\models\base\ActiveRecord
	 */
	public $model;

	// Protected --------------

	/**
	 * The service used to identify the user.
	 *
	 * @var \cmsgears\core\common\services\entities\UserService
	 */
	protected $userService;

	// Private ----------------

	public $siteProperties;

	// Constructor and Initialisation ------------------------------

	public function init() {

		$this->userService = Yii::$app->factory->get( 'userService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public static function allowedDomains() {

		return [ '*' ];
	}

	// yii\base\Controller ----

	// Traits -----------------

	use RestTrait;

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Controller ----------------------------

	public function getSiteProperties() {

		if( !isset( $this->siteProperties ) ) {

			$this->siteProperties = SiteProperties::getInstance();
		}

		return $this->siteProperties;
	}

	protected function getUser() {

		$token	= json_encode( getallheaders()[ 'accessToken' ] );
		$token	= trim( $token, "'" ); // Remove single quotes if any
		$token	= trim( $token, '"' ); // Remove double quotes if any
		$model	= $this->userService->getByAccessToken( $token );

		if( isset( $model ) ) {

			Yii::$app->user->setIdentity( $model );
		}

		return $model;
	}

}
