<?php
namespace cmsgears\core\common\models\traits;

// CMG Imports
use cmsgears\core\common\models\entities\ModelComment;

/**
 * CommentTrait can be used to add comment feature to relevant models. The model must define the member variable $parentType which is unique for the model.
 */
trait CommentTrait { 
	
	public function getModelComments() {

		$parentId	= $this->id;
		$parentType	= $this->parentType;

		return ModelComment::findByParentIdType( $parentId, $parentType );
	}
	
	public function getRatingAvg() {
		
		$parentId	= $this->id;
		$parentType	= $this->parentType;
		
		$ratings	= ModelComment::findByParentIdType( $parentId, $parentType );
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
}

?>