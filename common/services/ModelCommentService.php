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
	
	public static function getAllByParentType( $type ) {
		
		return ModelComment::getAllByParentType( $type );
	}
	
	public static function findByBaseIdParentId( $baseId, $parentId ) {
		
		return ModelComment::findByBaseIdParentId( $baseId, $parentId );
	}
	
	public static function findByParentIdType( $parentId, $parentType ) {
		
		return ModelComment::findByParentIdType( $parentId, $parentType );
	}

	// Create -----------
 	
 	public static function create( $model ) {
 		
		$model->save();
 	}
 	
	// Update -----------
 
	// Delete -----------
	 
}

?>