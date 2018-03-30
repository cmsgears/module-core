<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\mappers;

//Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\models\resources\Address;

use cmsgears\core\common\services\interfaces\mappers\IModelAddressService;

use cmsgears\core\common\services\base\ModelMapperService;

/**
 * ModelAddressService provide service methods of address mapper.
 *
 * @since 1.0.0
 */
class ModelAddressService extends ModelMapperService implements IModelAddressService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\mappers\ModelAddress';

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $addressService;

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->addressService = Yii::$app->factory->get( 'addressService' );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelFormService ----------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByType( $parentId, $parentType, $type, $first = false ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByType( $parentId, $parentType, $type, $first );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $address, $config = [] ) {

		$parentId	= $config[ 'parentId' ];
		$parentType = $config[ 'parentType' ];
		$type		= isset( $config[ 'type' ] ) ? $config[ 'type' ] : null;
		$order		= isset( $config[ 'order' ] ) ? $config[ 'order' ] : 0;

		// Create Address
		$address = $this->addressService->create( $address );

		// Create Model Address
		$modelAddress = $this->getModelObject();

		$modelAddress->modelId		= $address->id;
		$modelAddress->parentId		= $parentId;
		$modelAddress->parentType	= $parentType;
		$modelAddress->type			= $type;
		$modelAddress->order		= $order;

		$modelAddress->save();

		// Return Address
		return $modelAddress;
	}

	public function createOrUpdate( $address, $config = [] ) {

		$parentId	= $config[ 'parentId' ];
		$parentType = $config[ 'parentType' ];

		$type	= isset( $config[ 'type' ] ) ? $config[ 'type' ] : null;
		$order	= isset( $config[ 'order' ] ) ? $config[ 'order' ] : 0;

		if( isset( $address->id ) && !empty( $address->id ) ) {

			$existingAddress = $this->getByModelId( $parentId, $parentType, $address->id );

			if( isset( $existingAddress ) ) {

				return $this->update( $existingAddress, [ 'address' => $address ] );
			}
		}
		else {

			return $this->create( $address, $config );
		}
	}

	public function createOrUpdateByType( $address, $config = [] ) {

		$parentId	= $config[ 'parentId' ];
		$parentType = $config[ 'parentType' ];

		$type	= isset( $config[ 'type' ] ) ? $config[ 'type' ] : null;
		$order	= isset( $config[ 'order' ] ) ? $config[ 'order' ] : 0;

		$existingAddress = $this->getByType( $parentId, $parentType, $type, true );

		if( isset( $existingAddress ) ) {

			$addressToUpdate = $existingAddress->model;

			$addressToUpdate->copyForUpdateFrom( $address, [ 'countryId', 'provinceId', 'line1', 'line2', 'line3', 'cityName', 'zip',
								'firstName', 'lastName', 'phone', 'email', 'fax', 'longitude', 'latitude', 'zoomLevel' ] );

			$this->addressService->update( $addressToUpdate, $config );

			return $existingAddress;
		}
		else {

			return $this->create( $address, $config );
		}
	}

	public function createShipping( $address, $config = [] ) {

		$config[ 'type' ] = Address::TYPE_SHIPPING;

		return $this->create( $address, $config );
	}

	public function copyToShipping( $address, $config = [] ) {

		$config[ 'type' ]	= Address::TYPE_SHIPPING;

		$shippingAddress	= Yii::$app->get( 'addressService' )->getModelObject();

		$shippingAddress->copyForUpdateFrom( $address, [ 'countryId', 'provinceId', 'line1', 'line2', 'line3', 'cityName', 'zip', 'firstName', 'lastName', 'phone', 'email', 'fax' ] );

		return $this->create( $address, $config );
	}

	// Update -------------

	public function update( $modelAddress, $config = [] ) {

		// Update Address
		$this->addressService->update( $config[ 'address' ] );

		// Find existing Model Address
		$addressToUpdate	= $this->getById( $modelAddress->id );

		// Copy Attributes
		$addressToUpdate->copyForUpdateFrom( $modelAddress, [ 'type', 'order' ] );

		// Update Model Address
		$addressToUpdate->update();

		// Return updated Model Address
		return $addressToUpdate;
	}

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelFormService ----------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
