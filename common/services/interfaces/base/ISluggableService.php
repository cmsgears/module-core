<?php
namespace cmsgears\core\common\services\interfaces\base;

interface ISluggableService extends IEntityService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getByName( $name );

	public function getBySlug( $slug );

    // Read - Lists ----

    // Read - Maps -----

	// Create -------------

	// Update -------------

	// Delete -------------

}

?>