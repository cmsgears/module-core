<?php
namespace cmsgears\core\common\services\interfaces\resources;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\INameTypeService;
use cmsgears\core\common\services\interfaces\base\ISlugTypeService;

interface IGalleryService extends INameTypeService, ISlugTypeService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	public function createItem( $gallery, $item );

	// Update -------------

	public function updateItem( $item );

	public function switchActive( $model, $config = [] );

	// Delete -------------

}
