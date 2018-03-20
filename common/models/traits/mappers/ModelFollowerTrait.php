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
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\mappers\ModelFollower;

/**
 * ModelFollowerTrait can be used to add follow, like, wish features to relevant models.
 *
 * @since 1.0.0
 */
trait ModelFollowerTrait {

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

	// ModelFollowerTrait --------------------

	/**
	 * @inheritdoc
	 */
	public function getModelFollowers() {

		$modelFollowerTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_FOLLOWER );

		return $this->hasMany( ModelFollower::class, [ 'parentId' => 'id' ] )
			->where( "$modelFollowerTable.parentType='$this->modelType'" );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveModelFollowers() {

		$modelFollowerTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_FOLLOWER );

		return $this->hasMany( ModelFollower::class, [ 'parentId' => 'id' ] )
			->where( "$modelFollowerTable.parentType='$this->modelType' AND $modelFollowerTable.active=1" );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelFollowersByType( $type, $active = true ) {

		$modelFollowerTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_FOLLOWER );

		return $this->hasOne( ModelFollower::class, [ 'parentId' => 'id' ] )
			->where( "$modelFollowerTable.parentType=:ptype AND $modelFollowerTable.type=:type AND $modelFollowerTable.active=:active", [ ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getFollowers() {

		$modelFollowerTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_FOLLOWER );

		return $this->hasMany( User::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelFollowerTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelFollowerTable ) {

					$query->onCondition( [ "$modelFollowerTable.parentType" => $this->modelType ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveFollowers() {

		$modelFollowerTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_FOLLOWER );

		return $this->hasMany( User::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelFollowerTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelFollowerTable ) {

					$query->onCondition( [ "$modelFollowerTable.parentType" => $this->modelType, "$modelFollowerTable.active" => true ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getFollowersByType( $type, $active = true ) {

		$modelFollowerTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_FOLLOWER );

		$users = $this->hasMany( User::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelFollowerTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$type, &$active, &$modelFollowerTable ) {

					$query->onCondition( [ "$modelFollowerTable.parentType" => $this->modelType, "$modelFollowerTable.type" => $type, "$modelFollowerTable.active" => $active ] );
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

		$followerTable	= CoreTables::getTableName( CoreTables::TABLE_MODEL_FOLLOWER );
		$query			= new Query();

    	$query->select( [ 'type', 'active', 'count(id) as total' ] )
				->from( $followerTable )
				->where( [ 'parentId' => $this->id, 'parentType' => $this->modelType, 'active' => true ] )
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

			$followerTable	= CoreTables::getTableName( CoreTables::TABLE_MODEL_FOLLOWER );
			$query			= new Query();

	    	$query->select( [ 'type', 'active' ] )
					->from( $followerTable )
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

			$this->userFollows	= $this->generateUserFollows();
		}

		return $this->userFollows[ IFollower::TYPE_LIKE ];
	}

	/**
	 * @inheritdoc
	 */
	public function isDisliked() {

		if( !isset( $this->userFollows ) ) {

			$this->userFollows	= $this->generateUserFollows();
		}

		return $this->userFollows[ IFollower::TYPE_DISLIKE ];
	}

	/**
	 * @inheritdoc
	 */
	public function isFollowing() {

		if( !isset( $this->userFollows ) ) {

			$this->userFollows	= $this->generateUserFollows();
		}

		return $this->userFollows[ IFollower::TYPE_FOLLOW ];
	}

	/**
	 * @inheritdoc
	 */
	public function isWished() {

		if( !isset( $this->userFollows ) ) {

			$this->userFollows	= $this->generateUserFollows();
		}

		return $this->userFollows[ IFollower::TYPE_WISHLIST ];
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// ModelFollowerTrait --------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
