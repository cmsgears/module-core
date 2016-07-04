<?php
namespace cmsgears\core\common\services\interfaces\base;

interface INameSlugTypeService extends IEntityService {

	// Data Provider ------

	public function getPageByType( $type, $config = [] );

	// Read ---------------

    // Read - Models ---

	public function getByName( $name, $first = false );

	public function getBySlug( $slug, $first = false );

	public function getByNameType( $name, $type );

	public function getBySlugType( $slug, $type );

    // Read - Lists ----

	public function getIdListByType( $type, $config = [] );

	public function getIdNameListByType( $type, $options = [] );

    // Read - Maps -----

	public function getIdNameMapByType( $type, $options = [] );

	// Create -------------

	// Update -------------

	// Delete -------------

}

?>