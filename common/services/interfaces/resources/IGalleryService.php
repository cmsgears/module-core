<?php
namespace cmsgears\core\common\services\interfaces\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface IGalleryService extends \cmsgears\core\common\services\interfaces\base\ISluggableService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Create -------------

	public function createItem( $gallery, $item );

	// Update -------------

	public function updateItem( $item );

	// Delete -------------

}

?>