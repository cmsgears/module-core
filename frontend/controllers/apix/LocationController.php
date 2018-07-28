<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\frontend\controllers\base\Controller;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * LocationController provides actions to get options list and search results of province,
 * region and city.
 *
 * @since 1.0.0
 */
class LocationController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $provinceService;
	protected $regionService;
	protected $cityService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Services
		$this->provinceService	= Yii::$app->factory->get( 'provinceService' );
		$this->regionService	= Yii::$app->factory->get( 'regionService' );
		$this->cityService		= Yii::$app->factory->get( 'cityService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		return [
			'rbac' => [
				'class' => Yii::$app->core->getRbacFilterClass(),
				'actions' => [
					// add actions here
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'province-options' => [ 'post' ],
					'region-options' => [ 'post' ],
					'province-map' => [ 'post' ],
					'region-map' => [ 'post' ],
					'city-search' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'province-options' => [ 'class' => 'cmsgears\core\common\actions\location\ProvinceOptions' ],
			'region-options' => [ 'class' => 'cmsgears\core\common\actions\location\RegionOptions' ],
			'city-search' => [ 'class' => 'cmsgears\core\common\actions\location\CitySearch' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// LocationController --------------------

	public function actionProvinceMap() {

		$countryId	= Yii::$app->request->post( 'country-id' );

		if( isset( $countryId ) && $countryId > 0 ) {

			$provinces = $this->provinceService->getMapByCountryId( $countryId );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $provinces );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionRegionMap() {

		$provinceId	= Yii::$app->request->post( 'province-id' );

		if( isset( $provinceId ) && $provinceId > 0 ) {

			$regions = $this->regionService->getMapByProvinceId( $provinceId );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $regions );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
