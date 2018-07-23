<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\location;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\base\Action;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * CitySearch action returns the search results for given country and province id.
 *
 * @since 1.0.0
 */
class CitySearch extends Action {

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

		$provinceId	= Yii::$app->request->post( 'province-id' );
		$regionId	= Yii::$app->request->post( 'region-id' );
		$name		= Yii::$app->request->post( 'name' );

		$conditions = [ 'provinceId' => $provinceId ];

		if( !empty( $regionId && $regionId > 0 ) ) {

			$conditions[ 'regionId' ] = $regionId;
		}

		$cities = $this->modelService->searchByName( $name, [
			'limit' => 5,
			'conditions' => $conditions,
			'columns' => [ 'id', 'name', 'latitude', 'longitude', 'postal' ]
		]);

		// Trigger Ajax Success
		return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $cities );
	}

}
