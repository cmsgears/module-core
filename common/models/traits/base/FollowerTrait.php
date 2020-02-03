<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\base;

/**
 * FollowerTrait can be used to add follow, like, wish features to relevant models.
 *
 * @since 1.0.0
 */
trait FollowerTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	/**
	 * Stores the map of types having follower type as key and value as text representation of key.
	 *
	 * @var array
	 */
	public static $typeMap	= [
		self::TYPE_LIKE => 'Like',
		self::TYPE_DISLIKE => 'Dislike',
		self::TYPE_FOLLOW => 'Follow',
		self::TYPE_WISHLIST => 'Wish'
	];

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// FollowerTrait -------------------------

	/**
	 * @inheritdoc
	 */
	public function isLike() {

		return $this->type == self::TYPE_LIKE;
	}

	/**
	 * @inheritdoc
	 */
	public function isDislike() {

		return $this->type == self::TYPE_DISLIKE;
	}

	/**
	 * @inheritdoc
	 */
	public function isFollow() {

		return $this->type == self::TYPE_FOLLOW;
	}

	/**
	 * @inheritdoc
	 */
	public function isWish() {

		return $this->type == self::TYPE_WISHLIST;
	}

	/**
	 * @inheritdoc
	 */
	public function getTypeStr() {

		return static::$typeMap[ $this->type ];
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// FollowerTrait -------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
