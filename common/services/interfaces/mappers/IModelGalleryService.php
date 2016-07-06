<?php
namespace cmsgears\core\common\services\interfaces\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface IModelGalleryService extends \cmsgears\core\common\services\interfaces\base\IMapperService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Create -------------

	public function createOrUpdate( $model, $config = [] );

	// Update -------------

	// Delete -------------

}
