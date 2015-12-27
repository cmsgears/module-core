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
	public static function findExisting( $tagId, $parentId, $parentType ) {
		
		return ModelTag::findExisting( $tagId, $parentId, $parentType );
	}
	
	public static function findByParentIdType( $parentId, $parentType ) {
		
		return ModelTag::findByParentIdType( $parentId, $parentType );
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
}

?>