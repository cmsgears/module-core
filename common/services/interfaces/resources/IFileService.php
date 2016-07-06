<?php
namespace cmsgears\core\common\services\interfaces\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface IFileService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Create -------------

	// Update -------------

	public function updateData( $model, $config = [] );

	public function saveImage( $file, $args = [] );

	public function saveFile( $file, $args = [] );

	public function saveFiles( $model, $files = [] );

	// Delete -------------

	public function deleteFiles( $files = [] );
}
