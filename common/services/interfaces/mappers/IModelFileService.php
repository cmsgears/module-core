<?php
namespace cmsgears\core\common\services\interfaces\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface IModelFileService extends \cmsgears\core\common\services\interfaces\base\IMapperService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getByFileTitle( $parentId, $parentType, $fileTitle );

	public function getByFileTitleLike( $parentId, $parentType, $likeTitle );

    // Read - Lists ----

    // Read - Maps -----

	// Create -------------

	public static function createOrUpdateByTitle( $file, $config = [] );

	// Update -------------

	// Delete -------------

}

?>