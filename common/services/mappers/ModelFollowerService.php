<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\mappers;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\interfaces\base\IFollower;

use cmsgears\core\common\services\interfaces\mappers\IModelFollowerService;

use cmsgears\core\common\services\base\ModelMapperService;

/**
 * ModelFollowerService provide service methods of model follower.
 *
 * @since 1.0.0
 */
class ModelFollowerService extends ModelMapperService implements IModelFollowerService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\mappers\ModelFollower';

	public static $modelTable	= CoreTables::TABLE_MODEL_FOLLOWER;

	public static $parentType	= null;

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

	public static function getByFollower( $parentId, $parentType, $type = IFollower::TYPE_FOLLOW ) {

		$modelClass = static::$modelClass;
		$user		= Yii::$app->user->identity;

		return $modelClass::findByParentModelIdType( $parentId, $parentType, $user->id, $type );
	}

	// Read - Lists ----

    public function getFollowersIdList( $parentId, $parentType ) {

        return self::findList( [  'column' => 'modelId', 'conditions' => [ 'type' => IFollower::TYPE_FOLLOW, 'active' => true, 'parentId' => $parentId, 'parentType' => $parentType ] ] );
    }

    public function getFollowingIdList( $parentType ) {

		$user = Yii::$app->user->identity;

        return self::findList( [  'column' => 'parentId', 'conditions' => [ 'type' => IFollower::TYPE_FOLLOW, 'active' => true, 'parentType' => $parentType, 'modelId' => $user->id ] ] );
    }

	// Read - Maps -----

	// Read - Others ---

	public function getFollowCount( $parentType, $type = IFollower::TYPE_FOLLOW ) {

		$modelClass = static::$modelClass;
		$user		= Yii::$app->user->identity;

		return IFollower::queryByTypeParentTypeModelId( $type, $parentType, $user->id )->andWhere( [ 'active' => true ] )->count();
	}

	public function getFollowersCount( $parentId, $parentType, $type = IFollower::TYPE_FOLLOW ) {

		$modelClass = static::$modelClass;

		return $modelClass::queryByType( $parentId, $parentType, $type )->andWhere( [ 'active' => true ] )->count();
	}

    public function getStatusCounts( $parentId, $parentType, $type = IFollower::TYPE_FOLLOW ) {

        $followerTable = static::$modelTable;

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
		$parentType	= $params[ 'parentType' ];
		$type		= $params[ 'type' ];

		$follower	= $modelClass::findByParentModelIdType( $parentId, $parentType, $user->id, $type );

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
