<?php
namespace cmsgears\core\common\services\interfaces\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface IProvinceService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByCode( $code );

	public function getByCountryIdCode( $countryId, $code );

	// Read - Lists ----

	public function getListByCountryId( $countryId );

	// Read - Maps -----

	public function getMapByCountryId( $countryId );

	public function getIsoNameMapByCountryId( $countryId );

	// Create -------------

	// Update -------------

	// Delete -------------

}
