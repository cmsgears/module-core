<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Address;

/**
 * AddressTrait can be used to add address feature to relevant models. The model must define the member variable $addressType which is unique for the model.
 */
trait AddressTrait {

	/**
	 * @return array - Address associated with parent
	 */
	public function getAddresses() {

		$parentType	= $this->addressType;

    	return $this->hasMany( Address::className(), [ 'id' => 'addressId' ] )
					->viaTable( CoreTables::TABLE_MODEL_ADDRESS, [ 'parentId' => 'id' ] )
					->where( "parentType='$parentType'" );
	}

	/**
	 * @return Address - associated with parent for a particular type
	 */
	public function getAddressByType( $type ) {

    	return $this->hasMany( Address::className(), [ 'id' => 'addressId' ] )
					->viaTable( CoreTables::TABLE_MODEL_ADDRESS, [ 'parentId' => 'id' ], function( $query ) {

						$modelAddress	= CoreTables::TABLE_MODEL_ADDRESS;

                      	$query->onCondition( [ "$modelAddress.parentType" => $this->addressType ] );
					});
	}
}

?>