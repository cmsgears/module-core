<?php
namespace cmsgears\core\common\services\interfaces\base;

interface INameService extends IEntityService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByName( $name );

	public function searchByName( $name, $config = [] );

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	// Update -------------

	// Delete -------------

}
