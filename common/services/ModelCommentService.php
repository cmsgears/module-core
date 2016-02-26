<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\ModelComment;
 
/**
 * The class ModelCommentService is base class to perform database activities for ModelComment Entity.
 */
class ModelCommentService extends Service {

	// Static Methods ----------------------------------------------

	// Read ---------------- 
	
	public static function findById( $id ) {
	    
        return ModelComment::findOne( $id );
	}
	
	public static function getAllByParentType( $type, $commentType = ModelComment::TYPE_COMMENT ) {
		
		return ModelComment::getAllByParentType( $type, $commentType );
	}
	
	public static function findByBaseIdParentId( $baseId, $parentId, $commentType = ModelComment::TYPE_COMMENT ) {
		
		return ModelComment::findByBaseIdParentId( $baseId, $parentId, $commentType );
	}
	
	public static function findByParentIdType( $parentId, $parentType, $commentType = ModelComment::TYPE_COMMENT ) {
		
		return ModelComment::findByParentIdType( $parentId, $parentType, $commentType );
	}

	// Create -----------
 	
 	public static function create( $model ) {
 		
		$model->save();
 	}
 	
	// Update -----------
	
	public static function update( $model ) {
	    
        $model->update();
	}
 
	// Delete -----------
	
	public static function delete( $model ) {
        
        $model->delete();
    }
	 
}

?>