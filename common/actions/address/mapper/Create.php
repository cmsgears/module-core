<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\address\mapper;

// Yii Imports
use Yii;
use yii\db\Expression;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * The Create action creates model address, address and associate the model address to parent.
 *
 * @since 1.0.0
 */
class Create extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $parent = true;

	public $scenario = 'location';

	// Protected --------------

	protected $addressService;
	protected $modelAddressService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->addressService = Yii::$app->factory->get( 'addressService' );

		$this->modelAddressService = Yii::$app->factory->get( 'modelAddressService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Create --------------------------------

	public function run() {

		$scenario = Yii::$app->request->post( 'scenario' );

		$this->scenario = !empty( $scenario ) ? $scenario : $this->scenario;

		if( isset( $this->model ) ) {

			$address = $this->addressService->getModelObject();

			if( isset( $this->scenario ) ) {

				$address->setScenario( $this->scenario );

				if( $this->scenario == 'location' && $address->hasAttribute( 'geoPoint' ) && empty( $address->geoPoint ) ) {

					$address->geoPoint = new Expression( "ST_GeometryFromText( CONCAT( 'POINT(', 0, ' ', 0, ')' ) )" );
				}
			}

			if( $address->load( Yii::$app->request->post(), $address->getClassName() ) && $address->validate() ) {

				// Create Address
				$address = $this->addressService->create( $address );

				// Create Mapping
				$modelAddress = $this->modelAddressService->activateByParentModelId( $this->model->id, $this->parentType, $address->id, $this->modelType );

				$data = [
					'cid' => $modelAddress->id, 'ctype' => $modelAddress->type,
					'title' => $address->title, 'line1' => $address->line1, 'line2' => $address->line2,
					'country' => $address->countryName, 'province' => $address->provinceName,
					'region' => $address->regionName, 'city' => $address->cityName,
					'latitude' => $address->latitutde, 'longitude' => $address->longitude, 'zoomLevel' => $address->zoomLevel,
					'zip' => $address->zip, 'value' => $address->toString()
				];

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
			}

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $address );

			// Trigger Ajax Failure
			return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
