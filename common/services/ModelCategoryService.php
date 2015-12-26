<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\ModelCategory;

/**
 * The class ModelAttributeService is base class to perform database activities for ModelAttribute Entity.
 */
class ModelCategoryService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------
	
	public static function findByParentType( $parentType ) {
		
		return ModelCategory::findByParentType( $parentType );
	}
	
	public static function findByParentId( $parentId ) {
		
		return ModelCategory::findByParentId( $parentId );
	}
	 
	// Create -----------
	
	public static function create( $categoryId, $parentId, $parentType ) {
		
		$modelCategory	= new ModelCategory();
			
		$modelCategory->categoryId	= $categoryId;
		$modelCategory->parentId	= $parentId;
		$modelCategory->parentType	= $parentType;
		$modelCategory->save(); 
	}

	// Update -----------
  
	// Delete -----------
	
	public static function deleteByParentId( $parentId ) {
		
		return ModelCategory::deleteByParentId( $parentId );
	}
}

?>