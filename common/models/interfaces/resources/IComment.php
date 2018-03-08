<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\interfaces\resources;

/**
 * The IComment declare the methods specific to comments feature.
 *
 * @since 1.0.0
 */
interface IComment {

	/**
	 * Generate and return the query to get top level approved comments.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery
	 */
	public function getModelComments( $config = [] );

	/**
	 * Generate and return the query to get top level approved reviews.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery
	 */
	public function getModelReviews( $config = [] );

	/**
	 * Generate and return the query to get top level approved feedbacks.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery
	 */
	public function getModelFeedbacks( $config = [] );

	/**
	 * Generate and return the query to get top level approved testimonials.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery
	 */
	public function getModelTestimonials( $config = [] );

	/**
	 * Returns average rating of approved comments.
	 *
	 * @param string $type
	 * @param boolean $topLevel
	 * @return float
	 */
	public function getAverageRating( $type, $topLevel = true );

	/**
	 * Returns average rating of approved comments.
	 *
	 * @param boolean $topLevel
	 * @return float
	 */
	public function getCommentsAverageRating( $topLevel = true );

	/**
	 * Returns average rating of approved reviews.
	 *
	 * @param boolean $topLevel
	 * @return float
	 */
	public function getReviewsAverageRating( $topLevel = true );

	/**
	 * Returns average rating of approved feedbacks.
	 *
	 * @param boolean $topLevel
	 * @return float
	 */
	public function getFeedbacksAverageRating( $topLevel = true );

	/**
	 * Returns average rating of approved testimonials.
	 *
	 * @param boolean $topLevel
	 * @return float
	 */
	public function getTestimonialsAverageRating( $topLevel = true );

	/**
	 * Returns rating stars count based on star count.
	 *
	 * @param string $type
	 * @param integer $minStars
	 * @param integer $maxStars
	 * @param boolean $topLevel
	 * @return array
	 */
	public function getRatingStars( $type, $minStars = 0, $maxStars = 5, $topLevel = true );

	/**
	 * Returns rating stars count based on star count of comments.
	 *
	 * @param integer $minStars
	 * @param integer $maxStars
	 * @param boolean $topLevel
	 * @return array
	 */
	public function getCommentsRatingStars( $minStars = 0, $maxStars = 5, $topLevel = true );

	/**
	 * Returns rating stars count based on star count of reviews.
	 *
	 * @param integer $minStars
	 * @param integer $maxStars
	 * @param boolean $topLevel
	 * @return array
	 */
	public function getReviewsRatingStars( $minStars = 0, $maxStars = 5, $topLevel = true );

	/**
	 * Returns rating stars count based on star count of feedback.
	 *
	 * @param integer $minStars
	 * @param integer $maxStars
	 * @param boolean $topLevel
	 * @return array
	 */
	public function getFeedbacksRatingStars( $minStars = 0, $maxStars = 5, $topLevel = true );

	/**
	 * Returns rating stars count based on star count of testimonials.
	 *
	 * @param integer $minStars
	 * @param integer $maxStars
	 * @param boolean $topLevel
	 * @return array
	 */
	public function getTestimonialsRatingStars( $minStars = 0, $maxStars = 5, $topLevel = true );

	/**
	 * Returns status counts based on comment status.
	 *
	 * @param string $type
	 * @param boolean $topLevel
	 * @return array
	 */
	public function getCommentStatusCounts( $type = ModelComment::TYPE_COMMENT, $topLevel = true );

	/**
	 * Returns count of approved comments.
	 *
	 * @param string $type
	 * @param boolean $topLevel
	 * @return array
	 */
	public function getApprovedCommentCount( $type = ModelComment::TYPE_COMMENT, $topLevel = true );

	/**
	 * Returns count of approved reviews.
	 *
	 * @param string $type
	 * @param boolean $topLevel
	 * @return array
	 */
	public function getApprovedReviewCount( $topLevel = true );
}
