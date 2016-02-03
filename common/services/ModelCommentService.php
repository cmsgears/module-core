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
	
	public static function findByBaseIdParentId( $baseId, $parentId ) {
		
		return ModelComment::findByBaseIdParentId( $baseId, $parentId );
	}
	
	public static function findByParentIdType( $parentId, $parentType ) {
		
		return ModelComment::findByParentIdType( $parentId, $parentType );
	}
	
	public static function getRatingAvgByParentIdType( $parentId, $parentType ) {
		
		$ratings	= self::findByParentIdType( $parentId, $parentType );
		$average	= 0;
		
		if( isset( $ratings ) && count( $ratings ) > 0 ) {
			
			$ratingsCount	= count( $ratings );
			$sum			= 0;
			
			foreach( $ratings as $rating ) {
				
				$sum	+= $rating->rating;
			}
			
			$average	= $sum/$ratingsCount;
			
			if( is_float( $average ) ) {
				
				$average	= round( $average );
			}
		}
		
		return $average;
	}
 
	// Create -----------
 	
 	public static function create( $model ) {
 		
		$model->save();
 	}
 	
	// Update -----------
 
	// Delete -----------
	 
}

?>