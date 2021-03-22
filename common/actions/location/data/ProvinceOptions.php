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
 * ProvinceOptions action returns the province options for given country id.
 *
 * @since 1.0.0
 */
class ProvinceOptions extends \cmsgears\core\common\base\Action {

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

		$this->modelService = Yii::$app->factory->get( 'provinceService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ProvinceOptions -----------------------

	public function run() {

		$countryId	= Yii::$app->request->post( 'countryId' );
		$provinceId	= Yii::$app->request->post( 'provinceId' );

		if( isset( $countryId ) && $countryId > 0 ) {

			$provinces = $this->modelService->getIdNameMapByCountryId( $countryId, [ 'default' => true, 'defaultValue' => Yii::$app->core->provinceLabel ] );

			$data = !empty( $provinceId ) ? CodeGenUtil::generateSelectOptionsFromArray( $provinces, intval( $provinceId ) ) : CodeGenUtil::generateSelectOptionsFromArray( $provinces );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
