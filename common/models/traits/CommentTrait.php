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

		return ModelComment::findApprovedByParent( $this->id, $this->parentType, $this->commentType );
	}

	public function getAverageRating() {

		$commentTable	= CoreTables::TABLE_MODEL_COMMENT;
		$query			= new Query();

    	$query->select( [ 'avg(rating) as average' ] )
				->from( $commentTable )
				->where( [ 'parentId' => $this->id, 'parentType' => $this->commentType, 'status' => ModelComment::STATUS_APPROVED ] );

		$command 		= $query->createCommand();
		$average 		= $command->queryOne();

		return $average[ 'average' ];
	}

	public function getReviewCounts() {

		$commentTable	= CoreTables::TABLE_MODEL_COMMENT;
		$query			= new Query();

    	$query->select( [ 'status', 'count(id) as total' ] )
				->from( $commentTable )
				->where( [ 'parentId' => $this->id, 'parentType' => $this->commentType ] )
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
}

?>