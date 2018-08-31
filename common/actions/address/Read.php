<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\address;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\actions\base\ModelAction;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * The Read action find model address for the given id and return the address.
 *
 * @since 1.0.0
 */
class Read extends ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $parent = true;

	// Attributes having UTF-8 Encoding
	public $returnAttributes = [
		'id', 'countryId', 'provinceId', 'regionId', 'cityId', 'title', 'line1', 'line2', 'line3',
		'countryName', 'provinceName', 'cityName', 'zip', 'subZip',
		'firstName', 'lastName', 'phone', 'email', 'fax', 'website',
		'latitude', 'longitude', 'zoomLevel' ];

	// Protected --------------

	protected $modelAddressService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->modelAddressService = Yii::$app->factory->get( 'modelAddressService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Read ----------------------------------

	public function run( $cid ) {

		if( isset( $this->model ) ) {

			$modelAddress = $this->modelAddressService->getById( $cid );

			if( isset( $modelAddress ) && $modelAddress->isParentValid( $this->model->id, $this->parentType ) ) {

				$address = $modelAddress->model;

				$adata	= $address->getAttributeArray( $this->returnAttributes );
				$data	= [ 'cid' => $cid, 'address' => $adata, 'type' => $modelAddress->type ];

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
