<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\Address;
use cmsgears\core\common\models\entities\ModelAddress;

/**
 * The class AddressService is base class to perform database activities for Address Entity.
 */
class AddressService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	/**
	 * @param integer $id
	 * @return Address
	 */
	public static function findById( $id ) {

		return Address::findById( $id );
	}

	// Data Provider ----

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new Address(), $config );
	}

	// Create -----------

	public static function create( $address ) {

		// Create Address
		$address->save();
		
		// Return Address
		return $address;
	}

	// Update -----------

	public static function update( $address ) {

		// Find existing Address
		$addressToUpdate	= self::findById( $address->id );

		// Copy Attributes
		$addressToUpdate->copyForUpdateFrom( $address, [ 'countryId', 'provinceId', 'line1', 'line2', 'line3', 'city', 'zip', 
											'firstName', 'lastName', 'phone', 'email', 'fax', 'longitude', 'latitude' ] );

		// Update Category
		$addressToUpdate->update();

		// Return updated Category
		return $addressToUpdate;
	}

	// Delete -----------

	public static function delete( $address ) {

		// Find existing Address
		$addressToDelete	= self::findById( $address->id );

		// Delete dependency
		ModelAddress::deleteByAddressId( $address->id );

		// Delete Address
		$addressToDelete->delete();

		return true;
	}
}

?>