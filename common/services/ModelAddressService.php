<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
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

	public static function create( $address, $parentId, $parentType, $type ) {

		// Create Address
		$address->save();

		// Create Model Address
		$modelAddress				= new ModelAddress();

		$modelAddress->addressId 	= $address->id;
		$modelAddress->parentId 	= $parentId;
		$modelAddress->parentType 	= $parentType;
		$modelAddress->type			= $type;

		$modelAddress->save();

		// Return Address
		return $modelAddress;
	}
}

?>