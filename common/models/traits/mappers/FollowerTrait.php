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

use cmsgears\core\common\models\base\CoreTables;

/**
 * FollowerTrait can be used to add like, dislike, follow, wish features to relevant models.
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

		$userTable		= CoreTables::getTableName( CoreTables::TABLE_USER );
		$followerClass	= $this->followerClass;
		$followerTable	= $followerClass::tableName();

		/*
		return $this->hasMany( User::class, [ 'id' => 'modelId' ] )
			->viaTable( $followerTable, [ 'modelId' => 'id' ] );
		*/

		return $followerClass::find()
			->leftJoin( $userTable, "$followerTable.modelId=$userTable.id" )
			->where( "$followerTable.parentId=$this->id" )
			->orderBy( [ "$followerTable.order" => SORT_DESC, "$followerTable.id" => SORT_ASC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveFollowers() {

		$userTable		= CoreTables::getTableName( CoreTables::TABLE_USER );
		$followerClass	= $this->followerClass;
		$followerTable	= $followerClass::tableName();

		/*
		return $this->hasMany( User::class, [ 'id' => 'modelId' ] )
			->viaTable( $followerTable, [ 'modelId' => 'id' ],
				function( $query ) use( $followerTable ) {
					$query->onCondition( [ "$followerTable.active" => true ] );
				}
			);
		*/

		return $followerClass::find()
			->leftJoin( $userTable, "$followerTable.modelId=$userTable.id" )
			->where( "$followerTable.parentId=$this->id AND $followerTable.active=1" )
			->orderBy( [ "$followerTable.order" => SORT_DESC, "$followerTable.id" => SORT_ASC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getFollowersByType( $type, $active = true ) {

		$userTable		= CoreTables::getTableName( CoreTables::TABLE_USER );
		$followerClass	= $this->followerClass;
		$followerTable	= $followerClass::tableName();

		/*
		return $this->hasMany( User::class, [ 'id' => 'modelId' ] )
			->viaTable( $followerTable, [ 'modelId' => 'id' ],
				function( $query ) use( $followerTable, $type, $active ) {
					$query->onCondition( [ "$followerTable.type" => $type, "$followerTable.active" => $active ] );
				}
			)->all();
		*/

		return $followerClass::find()
			->leftJoin( $userTable, "$followerTable.modelId=$userTable.id" )
			->where( "$followerTable.parentId=$this->id AND $followerTable.type='$type' AND $followerTable.active=$active" )
			->orderBy( [ "$followerTable.order" => SORT_DESC, "$followerTable.id" => SORT_ASC ] )
			->all();
	}

	/**
	 * Generate and return the follow counts.
	 *
	 * @return array
	 */
	protected function generateFollowCounts() {

		$followerClass = $this->followerClass;
		$followerTable = $followerClass::tableName();

		$returnArr = [
			IFollower::TYPE_LIKE => [ 0 => 0, 1 => 0 ],
			IFollower::TYPE_DISLIKE => [ 0 => 0, 1 => 0 ],
			IFollower::TYPE_FOLLOW => [ 0 => 0, 1 => 0 ],
			IFollower::TYPE_WISHLIST => [ 0 => 0, 1 => 0 ]
		];

		$query = new Query();

    	$query->select( [ 'type', 'active', 'count(id) as total' ] )
				->from( $followerTable )
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

		$followerClass = $this->followerClass;
		$followerTable = $followerClass::tableName();

		$user		= Yii::$app->core->getUser();
		$returnArr	= [ IFollower::TYPE_LIKE => false, IFollower::TYPE_DISLIKE => false, IFollower::TYPE_FOLLOW => false, IFollower::TYPE_WISHLIST => false ];

		if( isset( $user ) ) {

			$query = new Query();

	    	$query->select( [ 'type', 'active' ] )
					->from( $followerTable )
					->where( [ 'parentId' => $this->id, 'modelId' => $user->id ] );

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

	/**
	 * Return query to find the model with followers.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with followers.
	 */
	public static function queryWithFollowers( $config = [] ) {

		$config[ 'relations' ][] = [ 'followers' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
