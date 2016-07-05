<?php
namespace cmsgears\core\common\services\interfaces\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\interfaces\hierarchy\INestedSetService;
use cmsgears\core\common\services\interfaces\base\INameSlugTypeService;

interface ICategoryService extends INestedSetService, INameSlugTypeService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getByParentId( $id );

	public function getFeaturedByType( $type );

	public function searchByName( $name, $config = [] );

    // Read - Lists ----

	public function getTopLevelIdNameListByType( $type, $config = [] );

	public function getTopLevelIdNameListById( $id, $config = [] );

	public function getLevelListByType( $type );

    // Read - Maps -----

	// Create -------------

	// Update -------------

	// Delete -------------

}

?>