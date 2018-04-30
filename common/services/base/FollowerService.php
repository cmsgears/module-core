<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\base;

// CMG Imports
use cmsgears\core\common\models\interfaces\base\IFollower;

use cmsgears\core\common\services\interfaces\mappers\IFollowerService;

use cmsgears\core\common\services\base\MapperService;

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

	public static function getByFollower( $parentId, $type = IFollower::TYPE_FOLLOW ) {

		$modelClass = static::$modelClass;
		$user		= Yii::$app->user->identity;

		return $modelClass::findByFollower( $user->id, $parentId, $type );
	}

	// Read - Lists ----

    public function getFollowersIdList( $parentId ) {

        return self::findList( [  'column' => 'modelId', 'conditions' => [ 'type' => IFollower::TYPE_FOLLOW, 'active' => true, 'parentId' => $parentId ] ] );
    }

    public function getFollowingIdList() {

		$user = Yii::$app->user->identity;

        return self::findList( [  'column' => 'parentId', 'conditions' => [ 'type' => IFollower::TYPE_FOLLOW, 'active' => true, 'modelId' => $user->id ] ] );
    }

	// Read - Maps -----

	// Read - Others ---

	public function getFollowCount( $type = IFollower::TYPE_FOLLOW ) {

		$modelClass = static::$modelClass;
		$user		= Yii::$app->user->identity;

		return $modelClass::queryByTypeModelId( $type, $user->id )->andWhere( [ 'active' => true ] )->count();
	}

	public function getFollowersCount( $parentId, $type = IFollower::TYPE_FOLLOW ) {

		$modelClass = static::$modelClass;

		return $modelClass::queryByTypeParentId( $type, $parentId )->andWhere( [ 'active' => true ] )->count();
	}

    public function getStatusCounts( $parentId, $type = IFollower::TYPE_FOLLOW ) {

        $followerTable = static::$modelTable;

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

		$params[ 'active' ]	= CoreGlobal::STATUS_ACTIVE;

		return parent::createByParams( $params, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		return parent::update( $model, [
			'attributes' => [ 'active' ]
		]);
	}

	public function updateByParams( $params = [], $config = [] ) {

		$modelClass = static::$modelClass;
		$user		= Yii::$app->user->identity;

		//$userId		= $params[ 'modelId' ];
		$parentId	= $params[ 'parentId' ];
		$type		= $params[ 'type' ];

		$follower	= $modelClass::findByFollower( $user->id, $parentId, $type );

		if( isset( $follower ) ) {

			return $this->toggleStatus( $follower );
		}

		return $this->createByParams( $params, $config );
	}

	public function toggleStatus( $model ) {

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
