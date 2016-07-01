<?php
namespace cmsgears\core\common\services\interfaces\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface IModelCategoryService extends \cmsgears\core\common\services\interfaces\base\IMapperService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getModelCounts( $parentType, $categoryType );

    // Read - Lists ----

	public function getActiveCategoryIdList( $categoryId, $parentType );

    public function getActiveCategoryIdListByParent( $parentId, $parentType );

    // Read - Maps -----

	// Create -------------

	public function createByParams( $categoryId, $parentId, $parentType );

	// Update -------------

	public function updateByParams( $parentId, $parentType, $categoryId );

	public function bindCategories( $binder, $parentType );

	// Delete -------------

}

?>