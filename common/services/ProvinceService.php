<?php
namespace cmsgears\core\common\services;

use cmsgears\core\common\models\entities\CoreTables;

class ProvinceService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function getListByCountryId( $countryId ) {

		return self::findIdNameList( 'id', 'name', CoreTables::TABLE_PROVINCE, [ 'countryId' => $countryId ] );
	}

	public static function getMapByCountryId( $countryId ) {

		return self::findMap( 'id', 'name', CoreTables::TABLE_PROVINCE, [ 'countryId' => $countryId ] );
	}
}

?>