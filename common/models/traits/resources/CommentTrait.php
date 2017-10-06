<?php
namespace cmsgears\core\common\models\traits\resources;

// Yii Imports
use yii\db\Query;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\ModelComment;

/**
 * CommentTrait can be used to add comment feature to relevant models.
 */
trait CommentTrait {

	/**
	 * Query top level approved comments.
	 */
	public function getModelComments() {

		return ModelComment::queryL0Approved( $this->id, $this->modelType, ModelComment::TYPE_COMMENT );
	}

	/**
	 * Query top level approved reviews.
	 */
	public function getModelReviews() {

		return ModelComment::queryL0Approved( $this->id, $this->modelType, ModelComment::TYPE_REVIEW );
	}

	/**
	 * Query top level approved feedbacks.
	 */
	public function getModelFeedbacks() {

		return ModelComment::queryL0Approved( $this->id, $this->modelType, ModelComment::TYPE_FEEDBACK );
	}

	/**
	 * Query top level approved testimonials.
	 */
	public function getModelTestimonials() {

		return ModelComment::queryL0Approved( $this->id, $this->modelType, ModelComment::TYPE_TESTIMONIAL );
	}

	// Average rating from all the approved reviews
	public function getAverageRating( $type = ModelComment::TYPE_REVIEW, $topLevel = true ) {

		$commentTable	= CoreTables::TABLE_MODEL_COMMENT;
		$query			= new Query();

		$query->select( [ 'avg(rating) as average, count(id) as total' ] )
				->from( $commentTable )
				->where( [ 'parentId' => $this->id, 'parentType' => $this->modelType, 'type' => $type, 'status' => ModelComment::STATUS_APPROVED ] );

		if( $topLevel ) {

			$query->andWhere( 'baseId IS NULL' );
		}

		$command = $query->createCommand();
		$average = $command->queryOne();

		if( isset( $average ) && count( $average ) < 2 ) {

			$average				= [];
			$average[ 'average' ]	= 0;
			$average[ 'total' ]		= 0;
		}
		else {

			$average[ 'average' ]	= round( $average[ 'average' ] );
			$average[ 'averaged' ]	= round( $average[ 'average' ], 2 );
			$average[ 'total' ]		= $average[ 'total' ];
		}

		return $average;
	}

	public function getRatingStars( $minStars = 0, $maxStars = 5, $topLevel = true ) {

		$returnArr		= [];

		for( $i = $minStars; $i <= $maxStars; $i++ ) {

			$returnArr[ $i ] = 0;
		}

		$commentTable	= CoreTables::TABLE_MODEL_COMMENT;
		$query			= new Query();

		$query->select( [ 'rating', 'count(id) as total' ] )
				->from( $commentTable )
				->where( [ 'parentId' => $this->id, 'parentType' => $this->modelType, 'status' => ModelComment::STATUS_APPROVED ] )
				->andFilterWhere( [ 'between', 'rating', $minStars, $maxStars ] );

		if( $topLevel ) {

			$query->andWhere( 'baseId IS NULL' );
		}

		$query->groupBy( 'rating' );

		$counts		= $query->all();
		$counter	= 0;

		foreach ( $counts as $count ) {

			$returnArr[ $count[ 'rating' ] ] = $count[ 'total' ];
		}

		foreach( $returnArr as $val ) {

			$counter += $val;
		}

		$returnArr[ 'all' ] = $counter;

		return $returnArr;
	}

	public function getCommentStatusCounts( $config = [] ) {

		$type			= isset( $config[ 'type' ] ) ? $config[ 'type' ] : ModelComment::TYPE_COMMENT;
		$topLevel		= isset( $config[ 'topLevel' ] ) ? $config[ 'topLevel' ] : true;

		$returnArr		= [ ModelComment::STATUS_NEW => 0, ModelComment::STATUS_BLOCKED => 0, ModelComment::STATUS_APPROVED => 0 ];

		$commentTable	= CoreTables::TABLE_MODEL_COMMENT;
		$query			= new Query();

		$query->select( [ 'status', 'count(id) as total' ] )
				->from( $commentTable )
				->where( [ 'parentId' => $this->id, 'parentType' => $this->modelType, 'type' => $type ] )
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

	public function getApprovedCommentCount( $config = [] ) {

		$type			= isset( $config[ 'type' ] ) ? $config[ 'type' ] : ModelComment::TYPE_COMMENT;
		$topLevel		= isset( $config[ 'topLevel' ] ) ? $config[ 'topLevel' ] : true;

		$commentTable	= CoreTables::TABLE_MODEL_COMMENT;
		$query			= new Query();

		$query->select( [ 'count(id) as total' ] )
				->from( $commentTable )
				->where( [ 'parentId' => $this->id, 'parentType' => $this->modelType, 'type' => $type, 'status' => ModelComment::STATUS_APPROVED ] )
				->groupBy( 'status' );

		$count = $query->one();

		if( $count ) {

			return $count[ 'total' ];
		}

		return 0;
	}

	public function getApprovedReviewCount( $config = [] ) {

		$config[ 'type' ] = ModelComment::TYPE_REVIEW;

		return $this->getApprovedCommentCount( $config );
	}
}
