<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\base;

// Yii Imports
use Yii;
use yii\db\Query;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\base\IFollower;

use cmsgears\core\common\services\interfaces\mappers\IFollowerService;

/**
 * FollowerService defines commonly used methods specific to follower models.
 *
 * @since 1.0.0
 */
abstract class FollowerService extends MapperService implements IFollowerService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

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

	// FollowerService -----------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByFollower( $parentId, $config = [] ) {

		$type = $config[ 'type' ] ?? IFollower::TYPE_FOLLOW;

		$modelClass = static::$modelClass;

		$user = Yii::$app->core->getUser();

		return isset( $user ) ? $modelClass::findByFollower( $user->id, $parentId, $type ) : null;
	}

	public function getFollowing( $config = [] ) {

		$type	= $config[ 'type' ] ?? IFollower::TYPE_FOLLOW;
		$limit	= $config[ 'limit' ] ?? null;

		$modelClass = static::$modelClass;

		$user = Yii::$app->core->getUser();

		$query = $modelClass::find()->where( 'type=:type AND modelId=:mid', [ ':type' => $type, ':mid' => $user->id ] );

		if( isset( $limit ) ) {

			$query->limit( $limit );
		}

		$query->all();
	}

	// Read - Lists ----

    public function getFollowersIdList( $parentId, $config = [] ) {

		$type	= $config[ 'type' ] ?? IFollower::TYPE_FOLLOW;
		$active = $config[ 'active' ] ?? true;

        return self::findList([
			'column' => 'modelId',
			'conditions' => [ 'type' => $type, 'active' => $active, 'parentId' => $parentId ]
		]);
    }

    public function getFollowingIdList( $config = [] ) {

		$type	= $config[ 'type' ] ?? IFollower::TYPE_FOLLOW;
		$active = $config[ 'active' ] ?? true;

		$user = Yii::$app->core->getUser();

		return isset( $user ) ? self::findList([
			'column' => 'parentId',
			'conditions' => [ 'type' => $type, 'active' => $active, 'modelId' => $user->id ]
		]) : [];
    }

    public function getLikeIdList( $config = [] ) {

		$config[ 'type' ] = IFollower::TYPE_LIKE;

		return $this->getFollowingIdList( $config );
    }

    public function getDisikeIdList( $config = [] ) {

		$config[ 'type' ] = IFollower::TYPE_DISLIKE;

		return $this->getFollowingIdList( $config );
    }

    public function getFollowIdList( $config = [] ) {

		$config[ 'type' ] = IFollower::TYPE_FOLLOW;

		return $this->getFollowingIdList( $config );
    }

    public function getWishlistIdList( $config = [] ) {

		$config[ 'type' ] = IFollower::TYPE_WISHLIST;

		return $this->getFollowingIdList( $config );
    }

	// Read - Maps -----

	// Read - Others ---

	public function getFollowCount( $config = [] ) {

		$type	= $config[ 'type' ] ?? IFollower::TYPE_FOLLOW;
		$active = $config[ 'active' ] ?? true;

		$modelClass = static::$modelClass;

		$user = Yii::$app->core->getUser();

		return isset( $user ) ? $modelClass::queryByTypeModelId( $type, $user->id )->andWhere( [ 'active' => $active ] )->count() : 0;
	}

	public function getFollowersCount( $parentId, $config = [] ) {

		$type	= $config[ 'type' ] ?? IFollower::TYPE_FOLLOW;
		$active = $config[ 'active' ] ?? true;

		$modelClass = static::$modelClass;

		return $modelClass::queryByTypeParentId( $type, $parentId )->andWhere( [ 'active' => $active ] )->count();
	}

    public function getStatusCounts( $parentId, $config = [] ) {

		$type = isset( $config[ 'type' ] ) ? $config[ 'type' ] : IFollower::TYPE_FOLLOW;

        $followerTable = $this->getModelTable();

        $query = new Query();

        $query->select( [ 'active', 'count(id) as total' ] )
			->from( $followerTable )
			->where( [ 'parentId' => $parentId, 'type' => $type ] )
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

	public function createByParams( $params = [], $config = [] ) {

		$params[ 'active' ]	= isset( $params[ 'active' ] ) ? $params[ 'active' ] : CoreGlobal::STATUS_ACTIVE;

		return parent::createByParams( $params, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'order', 'active' ];

        $config[ 'attributes' ] = $attributes;

		return parent::update( $model, $config );
	}

	public function updateByParams( $params = [], $config = [] ) {

		$modelClass = static::$modelClass;

		$user = Yii::$app->core->getUser();

		$params[ 'modelId' ] = $user->id;

		$parentId	= $params[ 'parentId' ];
		$type		= $params[ 'type' ];

		$follower = $modelClass::findByFollower( $user->id, $parentId, $type );

		if( isset( $follower ) ) {

			return $this->toggleActive( $follower );
		}

		return $this->createByParams( $params, $config );
	}

	public function activate( $model ) {

		$model->active = true;

		$model->update();

		return $model;
	}

	public function disable( $model ) {

		$model->active = false;

		$model->update();

		return $model;
	}

	public function toggleActive( $model ) {

		if( $model->active ) {

			$model->active = false;
		}
		else {

			$model->active = true;
		}

		return parent::update( $model, [
			'attributes' => [ 'active' ]
		]);
 	}

	// Delete -------------

	public function deleteByModelId( $modelId ) {

		$modelClass = static::$modelClass;

		return $modelClass::deleteByModelId( $modelId );
	}

	public function deleteByParentId( $parentId ) {

		$modelClass = static::$modelClass;

		return $modelClass::deleteByParentId( $parentId );
	}

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// FollowerService -----------------------

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
