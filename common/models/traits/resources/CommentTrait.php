<?php
namespace cmsgears\core\common\models\traits\resources;

// Yii Imports
use \Yii;
use \yii\db\Query;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\ModelComment;

/**
 * CommentTrait can be used to add comment feature to relevant models. The model must define the member variable $commentType which is unique for the model.
 */
trait CommentTrait {

	/**
	 * Query top level approved comments.
	 */
	public function getModelComments() {

		return ModelComment::queryL0Approved( $this->id, $this->mParentType, ModelComment::TYPE_COMMENT );
	}

	/**
	 * Query top level approved reviews.
	 */
	public function getModelReviews() {

		return ModelComment::queryL0Approved( $this->id, $this->mParentType, ModelComment::TYPE_REVIEW );
	}

	// Average rating for all the reviews
	public function getAverageRating( $type = ModelComment::TYPE_REVIEW ) {

		$commentTable	= CoreTables::TABLE_MODEL_COMMENT;
		$query			= new Query();

		$query->select( [ 'avg(rating) as average, count(id) as total' ] )
				->from( $commentTable )
				->where( [ 'parentId' => $this->id, 'parentType' => $this->mParentType, 'type' => $type, 'status' => ModelComment::STATUS_APPROVED ] );

		$command = $query->createCommand();
		$average = $command->queryOne();

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

	public function getRatingStars( $minStars = 0, $maxStars = 5, $config = [] ) {

		$returnArr		= [];

		for( $i = $minStars; $i <= $maxStars; $i++ ) {

			$returnArr[ $i ] = 0;
		}

		$commentTable	= CoreTables::TABLE_MODEL_COMMENT;
		$query			= new Query();

		$query->select( [ 'rating', 'count(id) as total' ] )
				->from( $commentTable )
				->where( [ 'parentId' => $this->id, 'parentType' => $this->mParentType, 'status' => ModelComment::STATUS_APPROVED ] )
				->andWhere( $config )
				->andFilterWhere( [ 'between', 'rating', $minStars, $maxStars ] )
				->groupBy( 'rating' );

		$counts		= $query->all();
		$counter	= 0;

		foreach ( $counts as $count ) {

			$returnArr[ $count[ 'rating' ] ] = $count[ 'total' ];
		}

		foreach( $returnArr as $val ) {

			$counter	+= $val;
		}

		$returnArr[ 'all' ] = $counter;

		return $returnArr;
	}

	public function getCommentStatusCounts( $type = ModelComment::TYPE_COMMENT ) {

		$returnArr		= [ ModelComment::STATUS_NEW => 0, ModelComment::STATUS_BLOCKED => 0, ModelComment::STATUS_APPROVED => 0 ];

		$commentTable	= CoreTables::TABLE_MODEL_COMMENT;
		$query			= new Query();

		$query->select( [ 'status', 'count(id) as total' ] )
				->from( $commentTable )
				->where( [ 'parentId' => $this->id, 'parentType' => $this->mParentType, 'type' => $type ] )
				->groupBy( 'status' );

		$counts		= $query->all();
		$counter	= 0;

		foreach ( $counts as $count ) {

			$returnArr[ $count[ 'status' ] ] = $count[ 'total' ];

			$counter	= $counter + $count[ 'total' ];
		}

		$returnArr[ 'all' ] = $counter;

		return $returnArr;
	}

	public function getApprovedCommentCount( $type = ModelComment::TYPE_COMMENT ) {

		$commentTable	= CoreTables::TABLE_MODEL_COMMENT;
		$query			= new Query();

		$query->select( [ 'count(id) as total' ] )
				->from( $commentTable )
				->where( [ 'parentId' => $this->id, 'parentType' => $this->mParentType, 'type' => $type, 'status' => ModelComment::STATUS_APPROVED ] )
				->groupBy( 'status' );

		$count = $query->one();

		if( $count ) {

			return $count[ 'total' ];
		}

		return 0;
	}

	public function getApprovedReviewCount() {

		return $this->getApprovedCommentCount( ModelComment::TYPE_REVIEW );
	}
}
