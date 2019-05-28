<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\location\data;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * CitySearch action returns the search results for given country and province id.
 *
 * @since 1.0.0
 */
class CitySearch extends \cmsgears\core\common\base\Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $modelService;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->modelService = Yii::$app->factory->get( 'cityService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ProvinceOptions -----------------------

	public function run() {

		$provinceId	= Yii::$app->request->post( 'provinceId' );
		$regionId	= Yii::$app->request->post( 'regionId' );
		$name		= Yii::$app->request->post( 'name' );

		$limit	= Yii::$app->request->post( 'limit' );
		$limit	= isset( $limit ) ? $limit : 5;

		$autoCache	= Yii::$app->request->post( 'autoCache' );
		$autoCache	= isset( $autoCache ) ? $autoCache : false;

		$conditions = [];

		if( !empty( $provinceId && $provinceId > 0 ) ) {

			$conditions[ 'provinceId' ] = $provinceId;
		}

		if( !empty( $regionId && $regionId > 0 ) ) {

			$conditions[ 'regionId' ] = $regionId;
		}

		$cities = [];

		if( $autoCache ) {

			$cities = $this->modelService->searchByName( $name, [
				'limit' => $limit, 'conditions' => $conditions, 'autoCache' => $autoCache,
				'columns' => [ 'id', 'autoCache AS name', 'latitude', 'longitude', 'postal' ]
			]);
		}
		else {

			$cities = $this->modelService->searchByName( $name, [
				'limit' => $limit, 'conditions' => $conditions, 'autoCache' => $autoCache,
				'columns' => [ 'id', 'name', 'latitude', 'longitude', 'postal' ]
			]);
		}

		// Trigger Ajax Success
		return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $cities );
	}

}
