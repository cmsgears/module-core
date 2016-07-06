<?php
namespace cmsgears\core\common\services\interfaces\base;

interface ISlugTypeService extends IEntityService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getBySlug( $slug, $first = false );

	public function getBySlugType( $slug, $type );

    // Read - Lists ----

    // Read - Maps -----

	// Create -------------

	// Update -------------

	// Delete -------------

}
