<?php
namespace cmsgears\core\common\services\interfaces\entities;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IEntityService;

interface IProvinceService extends IEntityService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByCode( $code );

	public function getAllByCode( $code );

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
