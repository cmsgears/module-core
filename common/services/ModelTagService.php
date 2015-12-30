<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii; 

// CMG Imports
use cmsgears\core\common\models\entities\ModelTag;

/**
 * The class ModelTagService is base class to perform database activities for ModelTag Entity.
 */
class ModelTagService extends Service {

	// Static Methods ----------------------------------------------
	
	// Read ----------------	
	public static function findByTagId( $parentId, $parentType, $tagId ) {
		
		return ModelTag::findByTagId( $parentId, $parentType, $tagId );
	}
	
	public static function findByParentIdType( $parentId, $parentType, $flag = 1 ) {
		
		return ModelTag::findByParentIdType( $parentId, $parentType, $flag );
	}
 
	// Create ---------------- 
	 public static function create( $model ) {
	 	
		$model->save(); 	
	 }
	 
	 // Delete ----------------	 
	 public static function delete( $model ) {
	 	
		$model->delete();
		
		return true;
	 }
	 
	 public static function updateActive( $model, $flag ) {
		
		$model->active	= $flag;
		$model->update();
		
		return $model;
	}
	 
	public static function update( $parentId, $type, $tagId ) {
		
		$existingModelTag	= self::findByTagId( $parentId, $type, $tagId );
				
		if( isset( $existingModelTag) && $existingModelTag->active == 0 ) {
			
			self::updateActive( $existingModelTag, 1 );
		}
		
		if( !$existingModelTag ) {
		
			$modelTag				= new ModelTag();	 
			$modelTag->tagId		= $tagId;
			$modelTag->parentId		= $parentId;
			$modelTag->parentType	= $type;
						
			self::create( $modelTag ); 
		}
	} 
}

?>