<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\mappers;

// Yii Imports
use Yii;
use yii\db\Query;

// CMG Imports
use cmsgears\core\common\models\interfaces\base\IFollower;

use cmsgears\core\common\services\interfaces\mappers\IModelFollowerService;

/**
 * ModelFollowerService provide service methods of model follower.
 *
 * @since 1.0.0
 */
class ModelFollowerService extends \cmsgears\core\common\services\base\ModelMapperService implements IModelFollowerService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\mappers\ModelFollower';

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelFollowerService ------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByFollower( $parentId, $parentType, $config = [] ) {

		$type = $config[ 'type' ] ?? IFollower::TYPE_FOLLOW;

		$modelClass = static::$modelClass;

		$user = Yii::$app->core->getUser();

		return isset( $user ) ? $modelClass::findByParentModelIdType( $parentId, $parentType, $user->id, $type ) : null;
	}

	public function getFollowing( $parentType, $config = [] ) {

		$type	= $config[ 'type' ] ?? IFollower::TYPE_FOLLOW;
		$limit	= $config[ 'limit' ] ?? null;

		$modelClass = static::$modelClass;

		$user = Yii::$app->core->getUser();

		$query = $modelClass::find()->where( 'parentType=:ptype AND type=:type AND modelId=:mid', [
			':ptype' => $parentType, ':type' => $type, ':mid' => $user->id
		]);

		if( isset( $limit ) ) {

			$query->limit( $limit );
		}

		$query->all();
	}

	// Read - Lists ----

    public function getFollowersIdList( $parentId, $parentType, $config = [] ) {

		$type	= $config[ 'type' ] ?? IFollower::TYPE_FOLLOW;
		$active = $config[ 'active' ] ?? true;

        return self::findList([
			'column' => 'modelId',
			'conditions' => [ 'parentId' => $parentId, 'parentType' => $parentType, 'type' => $type, 'active' => $active ]
		]);
    }

    public function getFollowingIdList( $parentType, $config = [] ) {

		$type	= $config[ 'type' ] ?? IFollower::TYPE_FOLLOW;
		$active = $config[ 'active' ] ?? true;

		$user = Yii::$app->core->getUser();

        return isset( $user ) ? self::findList([
			'column' => 'parentId',
			'conditions' => [ 'parentType' => $parentType, 'type' => $type, 'active' => $active, 'modelId' => $user->id ]
		]) : [];
    }

    public function getLikeIdList( $parentType, $config = [] ) {

		$config[ 'type' ] = IFollower::TYPE_LIKE;

		return $this->getFollowingIdList( $parentType, $config );
    }

    public function getDisikeIdList( $parentType, $config = [] ) {

		$config[ 'type' ] = IFollower::TYPE_DISLIKE;

		return $this->getFollowingIdList( $parentType, $config );
    }

    public function getFollowIdList( $parentType, $config = [] ) {

		$config[ 'type' ] = IFollower::TYPE_FOLLOW;

		return $this->getFollowingIdList( $parentType, $config );
    }

    public function getWishlistIdList( $parentType, $config = [] ) {

		$config[ 'type' ] = IFollower::TYPE_WISHLIST;

		return $this->getFollowingIdList( $parentType, $config );
    }

	// Read - Maps -----

	// Read - Others ---

	public function getFollowCount( $parentType, $config = [] ) {

		$type	= $config[ 'type' ] ?? IFollower::TYPE_FOLLOW;
		$active = $config[ 'active' ] ?? true;

		$modelClass = static::$modelClass;

		$user = Yii::$app->core->getUser();

		return isset( $user ) ? $modelClass::queryByTypeParentTypeModelId( $type, $parentType, $user->id )->andWhere( [ 'active' => $active ] )->count() : 0;
	}

	public function getFollowersCount( $parentId, $parentType, $config = [] ) {

		$type	= isset( $config[ 'type' ] ) ? $config[ 'type' ] : IFollower::TYPE_FOLLOW;
		$active = isset( $config[ 'active' ] ) ? $config[ 'active' ] : true;

		$modelClass = static::$modelClass;

		return $modelClass::queryByType( $parentId, $parentType, $type )->andWhere( [ 'active' => $active ] )->count();
	}

    public function getStatusCounts( $parentId, $parentType, $config = [] ) {

		$type = isset( $config[ 'type' ] ) ? $config[ 'type' ] : IFollower::TYPE_FOLLOW;

        $followerTable = $this->getModelTable();

        $query = new Query();

        $query->select( [ 'active', 'count(id) as total' ] )
			->from( $followerTable )
			->where( [ 'parentId' => $parentId, 'parentType' => $parentType, 'type' => $type ] )
			->groupBy( 'active' );

        $counts     = $query->all();
        $returnArr  = [ 'all' => 0, 0 => 0, 1 => 0 ];
        $counter    = 0;

        foreach( $counts as $count ) {

            $returnArr[ $count[ 'active' ] ] = $count[ 'total' ];

            $counter = $counter + $count[ 'total' ];
        }

        $returnArr[ 'all' ] = $counter;

        return $returnArr;
    }

	// Create -------------

	// Update -------------

	public function updateByParams( $params = [], $config = [] ) {

		$modelClass = static::$modelClass;

		$user = Yii::$app->core->getUser();

		$params[ 'modelId' ] = $user->id;

		$parentId	= $params[ 'parentId' ];
		$parentType	= $params[ 'parentType' ];
		$type		= $params[ 'type' ];

		$follower = $modelClass::findByParentModelIdType( $parentId, $parentType, $user->id, $type );

		if( isset( $follower ) ) {

			return $this->toggleActive( $follower );
		}

		return $this->createByParams( $params, $config );
	}

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelFollowerService ------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
