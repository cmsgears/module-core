<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\CmgAddress;

trait AddressTrait {

	public function getAddresses() {

		$parentType	= $this->addressType;

    	return $this->hasMany( CmgAddress::className(), [ 'id' => 'addressId' ] )
					->viaTable( CoreTables::TABLE_MODEL_ADDRESS, [ 'parentId' => 'id' ] )
					->where( "parentType=$parentType" );
	}
	
	public function getAddressByType( $type ) {
		
		$parentType	= $this->addressType;

    	return $this->hasMany( CmgAddress::className(), [ 'id' => 'addressId' ] )
					->viaTable( CoreTables::TABLE_MODEL_ADDRESS, [ 'parentId' => 'id' ] )
					->where( "parentType=$parentType AND type=:type", [ ':type' => $type ] );
	}
}

?>