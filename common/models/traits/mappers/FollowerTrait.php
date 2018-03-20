<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\mappers;

// CMG Imports
use cmsgears\core\common\models\interfaces\base\IFollower;

use cmsgears\core\common\models\entities\User;

/**
 * FollowerTrait can be used to add follow, like, wish features to relevant models.
 *
 * @since 1.0.0
 */
trait FollowerTrait {

	private $followCounts;

	private $userFollows;

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

	// FollowerTrait -------------------------

	/**
	 * @inheritdoc
	 */
	public function getFollowers() {

		return $this->hasMany( User::class, [ 'id' => 'followerId' ] )
			->viaTable( $this->followerTable, [ 'modelId' => 'id' ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveFollowers() {

		return $this->hasMany( User::class, [ 'id' => 'followerId' ] )
			->viaTable( $this->followerTable, [ 'modelId' => 'id' ],
				function( $query ) {
					$followerTable = $this->followerTable;
					$query->onCondition( [ "$followerTable.active" => true ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getFollowersByType( $type, $active = true ) {

		$users = $this->hasMany( User::class, [ 'id' => 'followerId' ] )
			->viaTable( $this->followerTable, [ 'modelId' => 'id' ],
				function( $query ) use( &$type, &$active ) {
					$followerTable = $this->followerTable;
					$query->onCondition( [ "$followerTable.type" => $type, "$followerTable.active" => $active ] );
				}
			)->all();

		return $users;
	}

	/**
	 * Generate and return the follow counts.
	 *
	 * @return array
	 */
	protected function generateFollowCounts() {

		$returnArr = [
			IFollower::TYPE_LIKE => [ 0 => 0, 1 => 0 ],
			IFollower::TYPE_DISLIKE => [ 0 => 0, 1 => 0 ],
			IFollower::TYPE_FOLLOW => [ 0 => 0, 1 => 0 ],
			IFollower::TYPE_WISHLIST => [ 0 => 0, 1 => 0 ]
		];

		$query = new Query();

    	$query->select( [ 'type', 'active', 'count(id) as total' ] )
				->from( $this->followerTable )
				->where( [ 'modelId' => $this->id, 'active' => true ] )
				->groupBy( [ 'type', 'active' ] );

		$counts = $query->all();

		foreach( $counts as $count ) {

			$returnArr[ $count[ 'type' ] ][ $count[ 'active' ] ] = $count[ 'total' ];
		}

		return $returnArr;
	}

	/**
	 * @inheritdoc
	 */
	public function getLikesCount( $active = true ) {

		if( !isset( $this->followCounts ) ) {

			$this->followCounts	= $this->generateFollowCounts();
		}

		return $active ? $this->followCounts[ IFollower::TYPE_LIKE ][ 1 ] : $this->followCounts[ IFollower::TYPE_LIKE ][ 0 ];
	}

	/**
	 * @inheritdoc
	 */
	public function getDislikesCount( $active = true ) {

		if( !isset( $this->followCounts ) ) {

			$this->followCounts	= $this->generateFollowCounts();
		}

		return $active ? $this->followCounts[ IFollower::TYPE_DISLIKE ][ 1 ] : $this->followCounts[ IFollower::TYPE_DISLIKE ][ 0 ];
	}

	/**
	 * @inheritdoc
	 */
	public function getFollowersCount( $active = true ) {

		if( !isset( $this->followCounts ) ) {

			$this->followCounts	= $this->generateFollowCounts();
		}

		return $active ? $this->followCounts[ IFollower::TYPE_FOLLOW ][ 1 ] : $this->followCounts[ IFollower::TYPE_FOLLOW ][ 0 ];
	}

	/**
	 * @inheritdoc
	 */
	public function getWishersCount( $active = true ) {

		if( !isset( $this->followCounts ) ) {

			$this->followCounts	= $this->generateFollowCounts();
		}

		return $active ? $this->followCounts[ IFollower::TYPE_WISHLIST ][ 1 ] : $this->followCounts[ IFollower::TYPE_WISHLIST ][ 0 ];
	}

	// User Follow Tests

	/**
	 * Generate and return the user follows.
	 *
	 * @return array
	 */
	protected function generateUserFollows() {

		$user		= Yii::$app->user->identity;
		$returnArr	= [ IFollower::TYPE_LIKE => false, IFollower::TYPE_DISLIKE => false, IFollower::TYPE_FOLLOW => false, IFollower::TYPE_WISHLIST => false ];

		if( isset( $user ) ) {

			$query = new Query();

	    	$query->select( [ 'type', 'active' ] )
					->from( $this->followerTable )
					->where( [ 'parentId' => $this->id, 'parentType' => $this->modelType, 'modelId' => $user->id ] );

			$follows = $query->all();

			foreach ( $follows as $follow ) {

				$returnArr[ $follow[ 'type' ] ] = $follow[ 'active' ];
			}
		}

		return $returnArr;
	}

	/**
	 * @inheritdoc
	 */
	public function isLiked() {

		if( !isset( $this->userFollows ) ) {

			$this->userFollows = $this->generateUserFollows();
		}

		return $this->userFollows[ IFollower::TYPE_LIKE ];
	}

	/**
	 * @inheritdoc
	 */
	public function isDisliked() {

		if( !isset( $this->userFollows ) ) {

			$this->userFollows = $this->generateUserFollows();
		}

		return $this->userFollows[ IFollower::TYPE_DISLIKE ];
	}

	/**
	 * @inheritdoc
	 */
	public function isFollowing() {

		if( !isset( $this->userFollows ) ) {

			$this->userFollows = $this->generateUserFollows();
		}

		return $this->userFollows[ IFollower::TYPE_FOLLOW ];
	}

	/**
	 * @inheritdoc
	 */
	public function isWished() {

		if( !isset( $this->userFollows ) ) {

			$this->userFollows = $this->generateUserFollows();
		}

		return $this->userFollows[ IFollower::TYPE_WISHLIST ];
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
