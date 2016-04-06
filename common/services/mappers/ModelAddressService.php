<?php
namespace cmsgears\core\common\services\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\resources\Address;
use cmsgears\core\common\models\mappers\ModelAddress;

/**
 * The class ModelAddressService is base class to perform database activities for ModelAddress Entity.
 */
class ModelAddressService extends \cmsgears\core\common\services\base\Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	/**
	 * @param integer $id
	 * @return Address
	 */
	public static function findById( $id ) {

		return ModelAddress::findById( $id );
	}

	public static function findByParentId( $parentId ) {

		return ModelAddress::findByParentId( $parentId );
	}

	/**
	 * Models Supporting Multiple address for same type.
	 */
	public static function findByType( $parentId, $parentType, $type ) {

		return ModelAddress::findByType( $parentId, $parentType, $type );
	}

	/**
	 * Model Supporting one address for same type.
	 */
	public static function findFirstByType( $parentId, $parentType, $type ) {

		return ModelAddress::findFirstByType( $parentId, $parentType, $type );
	}

	public static function findByParent( $parentId, $parentType ) {

		return ModelAddress::findByParent( $parentId, $parentType );
	}

	public static function findByAddressId( $parentId, $parentType, $addressId ) {

		return ModelAddress::findByAddressId( $parentId, $parentType, $addressId );
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

	public static function createOrUpdate( $address, $parentId, $parentType, $type ) {

		if( isset( $address->id ) && !empty( $address->id ) ) {

			$existingAddress	= self::findByAddressId( $parentId, $parentType, $address->id );

			if( isset( $existingAddress ) ) {

				self::update( $existingAddress, $address );
			}
		}
		else {

			self::create( $address, $parentId, $parentType, $type );
		}
	}

	public static function createOrUpdateByType( $address, $parentId, $parentType, $type ) {

		$existingAddress	= self::findFirstByType( $parentId, $parentType, $type );

		if( isset( $existingAddress ) ) {

			$addressToUpdate	= $existingAddress->address;

			$addressToUpdate->copyForUpdateFrom( $address, [ 'countryId', 'provinceId', 'line1', 'line2', 'line3', 'city', 'zip',
											'firstName', 'lastName', 'phone', 'email', 'fax', 'longitude', 'latitude', 'zoomLevel' ] );

			$addressToUpdate->update();

			return $existingAddress;
		}
		else {

			return self::create( $address, $parentId, $parentType, $type );
		}
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

	public static function delete( $model ) {

		return $model->delete();
	}
}

?>