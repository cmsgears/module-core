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
 * RegionMap action returns the region map for given province id.
 *
 * @since 1.0.0
 */
class RegionMap extends \cmsgears\core\common\base\Action {

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

		$this->modelService = Yii::$app->factory->get( 'regionService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ProvinceOptions -----------------------

	public function run() {

		$provinceId	= Yii::$app->request->post( 'province-id' );

		if( isset( $provinceId ) && $provinceId > 0 ) {

			$regions = $this->modelService->getMapByProvinceId( $provinceId );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $regions );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
