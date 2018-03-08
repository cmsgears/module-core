<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

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

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// CommentTrait --------------------------

	/**
	 * @inheritdoc
	 */
	public function getModelComments( $config = [] ) {

		return ModelComment::queryL0Approved( $this->id, $this->modelType, ModelComment::TYPE_COMMENT, $config );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelReviews( $config = [] ) {

		return ModelComment::queryL0Approved( $this->id, $this->modelType, ModelComment::TYPE_REVIEW, $config );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelFeedbacks( $config = [] ) {

		return ModelComment::queryL0Approved( $this->id, $this->modelType, ModelComment::TYPE_FEEDBACK, $config );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelTestimonials( $config = [] ) {

		return ModelComment::queryL0Approved( $this->id, $this->modelType, ModelComment::TYPE_TESTIMONIAL, $config );
	}

	/**
	 * @inheritdoc
	 */
	public function getAverageRating( $type, $topLevel = true ) {

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

	/**
	 * @inheritdoc
	 */
	public function getCommentsAverageRating( $topLevel = true ) {

		return $this->getAverageRating( ModelComment::TYPE_COMMENT, $topLevel );
	}

	/**
	 * @inheritdoc
	 */
	public function getReviewsAverageRating( $topLevel = true ) {

		return $this->getAverageRating( ModelComment::TYPE_REVIEW, $topLevel );
	}

	/**
	 * @inheritdoc
	 */
	public function getFeedbacksAverageRating( $topLevel = true ) {

		return $this->getAverageRating( ModelComment::TYPE_FEEDBACK, $topLevel );
	}

	/**
	 * @inheritdoc
	 */
	public function getTestimonialsAverageRating( $topLevel = true ) {

		return $this->getAverageRating( ModelComment::TYPE_TESTIMONIAL, $topLevel );
	}

	/**
	 * @inheritdoc
	 */
	public function getRatingStars( $type, $minStars = 0, $maxStars = 5, $topLevel = true ) {

		$returnArr		= [];

		for( $i = $minStars; $i <= $maxStars; $i++ ) {

			$returnArr[ $i ] = 0;
		}

		$commentTable	= CoreTables::TABLE_MODEL_COMMENT;
		$query			= new Query();

		$query->select( [ 'rating', 'count(id) as total' ] )
				->from( $commentTable )
				->where( [ 'parentId' => $this->id, 'parentType' => $this->modelType, 'type' => $type, 'status' => ModelComment::STATUS_APPROVED ] )
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

	/**
	 * @inheritdoc
	 */
	public function getCommentsRatingStars( $minStars = 0, $maxStars = 5, $topLevel = true ) {

		return $this->getRatingStars( ModelComment::TYPE_COMMENT, $minStars, $maxStars, $topLevel );
	}

	/**
	 * @inheritdoc
	 */
	public function getReviewsRatingStars( $minStars = 0, $maxStars = 5, $topLevel = true ) {

		return $this->getRatingStars( ModelComment::TYPE_REVIEW, $minStars, $maxStars, $topLevel );
	}

	/**
	 * @inheritdoc
	 */
	public function getFeedbacksRatingStars( $minStars = 0, $maxStars = 5, $topLevel = true ) {

		return $this->getRatingStars( ModelComment::TYPE_FEEDBACK, $minStars, $maxStars, $topLevel );
	}

	/**
	 * @inheritdoc
	 */
	public function getTestimonialsRatingStars( $minStars = 0, $maxStars = 5, $topLevel = true ) {

		return $this->getRatingStars( ModelComment::TYPE_TESTIMONIAL, $minStars, $maxStars, $topLevel );
	}

	/**
	 * @inheritdoc
	 */
	public function getCommentStatusCounts( $type = ModelComment::TYPE_COMMENT, $topLevel = true ) {

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

	/**
	 * @inheritdoc
	 */
	public function getApprovedCommentCount( $type = ModelComment::TYPE_COMMENT, $topLevel = true ) {

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

	/**
	 * @inheritdoc
	 */
	public function getApprovedReviewCount( $topLevel = true ) {

		return $this->getApprovedCommentCount( ModelComment::TYPE_REVIEW, $topLevel );
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// CommentTrait --------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
