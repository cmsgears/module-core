<?php
namespace cmsgears\core\common\services\interfaces\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\interfaces\hierarchy\INestedSetService;
use cmsgears\core\common\services\interfaces\base\INameTypeService;
use cmsgears\core\common\services\interfaces\base\ISlugTypeService;

interface ICategoryService extends INestedSetService, INameTypeService, ISlugTypeService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getByParentId( $id );

	public function getFeaturedByType( $type );

    // Read - Lists ----

	public function getTopLevelIdNameListByType( $type, $config = [] );

	public function getTopLevelIdNameListById( $id, $config = [] );

	public function getLevelListByType( $type );

    // Read - Maps -----

	// Create -------------

	// Update -------------

	// Delete -------------

}
