<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\Address;
use cmsgears\core\common\models\entities\ModelAddress;

/**
 * The class ModelAddressService is base class to perform database activities for ModelAddress Entity.
 */
class ModelAddressService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	/**
	 * @param integer $id
	 * @return Address
	 */
	public static function findById( $id ) {

		return ModelAddress::findById( $id );
	}

	public static function findByType( $parentId, $parentType, $type ) {

		return ModelAddress::findByType( $parentId, $parentType, $type );
	}

	// Data Provider ----

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new ModelAddress(), $config );
	}

	// Create -----------

	public static function create( $address, $parentId, $parentType, $type, $order = 0 ) {

		// Create Address
		$address->save();

		// Create Model Address
		$modelAddress				= new ModelAddress();

		$modelAddress->addressId 	= $address->id;
		$modelAddress->parentId 	= $parentId;
		$modelAddress->parentType 	= $parentType;
		$modelAddress->type			= $type;
		$modelAddress->order		= $order;

		$modelAddress->save();

		// Return Address
		return $modelAddress;
	}

	public static function createShipping( $address, $parentId, $parentType, $order = 0 ) {
		
		return self::create( $address, $parentId, $parentType, Address::TYPE_SHIPPING, $order );
	}

	public static function copyToShipping( $address, $parentId, $parentType, $order = 0 ) {

		$shippingAddress	= new Address();

		$shippingAddress->copyForUpdateFrom( $address, [ 'countryId', 'provinceId', 'line1', 'line2', 'line3', 'city', 'zip', 'firstName', 'lastName', 'phone', 'email', 'fax' ] );

		return self::create( $shippingAddress, $parentId, $parentType, Address::TYPE_SHIPPING, $order );
	}

	// Create -----------

	public static function update( $modelAddress, $address ) {

		// Update Address
		AddressService::update( $address );

		// Find existing Model Address
		$addressToUpdate	= self::findById( $modelAddress->id );

		// Copy Attributes
		$addressToUpdate->copyForUpdateFrom( $modelAddress, [ 'type', 'order' ] );

		// Update Model Address
		$addressToUpdate->update();

		// Return updated Model Address
		return $addressToUpdate;
	}

	// Delete -----------

	public static function deleteByParentIdType( $parentId, $parentType ) {

		ModelAddress::deleteByParentIdType( $parentId, $parentType );
	}
}

?>