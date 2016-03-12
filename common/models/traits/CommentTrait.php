<?php
namespace cmsgears\core\common\models\traits;

// Yii Imports
use \Yii;
use \yii\db\Query;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\ModelComment;

/**
 * CommentTrait can be used to add comment feature to relevant models. The model must define the member variable $commentType which is unique for the model.
 */
trait CommentTrait {

	public function getModelComments() {

		return ModelComment::findByParent( $this->id, $this->parentType, ModelComment::TYPE_COMMENT );
	}

	public function getModelReviews() {

		return ModelComment::findByParent( $this->id, $this->parentType, ModelComment::TYPE_REVIEW );
	}

	// Average rating for all the reviews
	public function getAverageRating( $type = ModelComment::TYPE_REVIEW ) {

		$commentTable	= CoreTables::TABLE_MODEL_COMMENT;
		$query			= new Query();

    	$query->select( [ 'avg(rating) as average, count(id) as total' ] )
				->from( $commentTable )
				->where( [ 'parentId' => $this->id, 'parentType' => $this->parentType, 'type' => $type, 'status' => ModelComment::STATUS_APPROVED ] );

		$command 		= $query->createCommand();
		$average 		= $command->queryOne();

		if( isset( $average ) && count( $average ) < 2 ) {

			$average				= [];
			$average[ 'average' ]	= 0;
			$average[ 'total' ]		= 0;
		}
		else {

			$average[ 'average' ]	= round( $average[ 'average' ] );
		}

		return $average;
	}
    
    public function getRatingCounts() {
        
        $counts = [];
        
        for( $i = 1; $i <= 5; $i++ ) {
            
            $counts[ $i ]   = count( ModelComment::findAll( [ 'status' => ModelComment::STATUS_APPROVED, 'rating' => $i ] ) ); 
        }
        
        return $counts;
    }

	public function getReviewCounts() {

		$commentTable	= CoreTables::TABLE_MODEL_COMMENT;
		$query			= new Query();

    	$query->select( [ 'status', 'count(id) as total' ] )
				->from( $commentTable )
				->where( [ 'parentId' => $this->id, 'parentType' => $this->parentType, 'type' => ModelComment::TYPE_REVIEW ] )
				->groupBy( 'status' );

		$counts 	= $query->all();
		$returnArr	= [];
		$counter	= 0;

		foreach ( $counts as $count ) {

			$returnArr[ $count[ 'status' ] ] = $count[ 'total' ];

			$counter	= $counter + $count[ 'total' ];
		}

		$returnArr[ 'all' ] = $counter;

		if( !isset( $returnArr[ ModelComment::STATUS_NEW ] ) ) {

			$returnArr[ ModelComment::STATUS_NEW ]	= 0;
		}

		if( !isset( $returnArr[ ModelComment::STATUS_BLOCKED ] ) ) {

			$returnArr[ ModelComment::STATUS_BLOCKED ]	= 0;
		}

		if( !isset( $returnArr[ ModelComment::STATUS_APPROVED ] ) ) {

			$returnArr[ ModelComment::STATUS_APPROVED ]	= 0;
		}

		return $returnArr;
	}

	public function getApprovedReviewCount() {

		$commentTable	= CoreTables::TABLE_MODEL_COMMENT;
		$query			= new Query();

    	$query->select( [ 'count(id) as total' ] )
				->from( $commentTable )
				->where( [ 'parentId' => $this->id, 'parentType' => $this->parentType, 'type' => ModelComment::TYPE_REVIEW, 'status' => ModelComment::STATUS_APPROVED ] )
				->groupBy( 'status' );

		$count = $query->one();

		if( $count ) {

			return $count[ 'total' ];
		}

		return 0;
	}
}

?>