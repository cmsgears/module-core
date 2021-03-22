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
use cmsgears\core\common\utilities\CodeGenUtil;

/**
 * RegionOptions action returns the province options for given province id.
 *
 * @since 1.0.0
 */
class RegionOptions extends \cmsgears\core\common\base\Action {

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

		$provinceId	= Yii::$app->request->post( 'provinceId' );
		$regionId	= Yii::$app->request->post( 'regionId' );

		if( isset( $provinceId ) && $provinceId > 0 ) {

			$regions = $this->modelService->getIdNameMapByProvinceId( $provinceId, [ 'default' => true, 'defaultValue' => Yii::$app->core->regionLabel ] );

			$data = !empty( $regionId ) ? CodeGenUtil::generateSelectOptionsFromArray( $regions, intval( $regionId ) ) : CodeGenUtil::generateSelectOptionsFromArray( $regions );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
