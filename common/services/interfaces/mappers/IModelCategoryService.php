<?php
namespace cmsgears\core\common\services\interfaces\mappers;

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

	// Update -------------

	public function bindCategories( $parentId, $parentType, $config = [] );

	// Delete -------------

}
