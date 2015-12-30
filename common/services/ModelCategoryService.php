<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\ModelCategory;

/**
 * The class ModelCategoryService is base class to perform database activities for ModelCategory Entity.
 */
class ModelCategoryService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function findByParentType( $parentType ) {
		
		return ModelCategory::findByParentType( $parentType );
	}
	
	public static function findByParentId( $parentId, $flag = 1 ) {
		
		return ModelCategory::findByParentId( $parentId, $flag );
	}
	
	public static function findByCategoryId( $parentId, $parentType, $categoryId ) {
		
		return ModelCategory::findByCategoryId( $parentId, $parentType, $categoryId );
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
	
	public static function updateActiveByParentId( $parentId, $flag ) {
		
		$model	= self::findByParentId( $parentId );
		
		if( isset( $model ) ) {
			
			foreach( $model as $modelToUpdate ) {			
			 
				self::updateActive( $modelToUpdate, $flag );
			}	
		}
	}
	
	public static function updateActive( $model, $flag ) {
		
		$model->active	= $flag;
		$model->update();
	}
  
	// Delete -----------
	
	public static function deleteByCategoryId( $categoryId ) {
		
		return ModelCategory::deleteByCategoryId( $categoryId );
	}
}

?>