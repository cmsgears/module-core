<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\base;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\base\IFollower;

use cmsgears\core\common\models\traits\base\FollowerTrait;

/**
 * Follower represents interest of one model in another model.
 *
 * @property int $id
 * @property int $modelId
 * @property int $followerId
 * @property string $type
 * @property boolean $active
 * @property int $createdAt
 * @property int $modifiedAt
 *
 * @since 1.0.0
 */
abstract class Follower extends Mapper implements IFollower {

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

	use FollowerTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	/**
	 * @inheritdoc
	 */
	public function behaviors() {

		return [
			'timestampBehavior' => [
				'class' => TimestampBehavior::class,
				'createdAtAttribute' => 'createdAt',
				'updatedAtAttribute' => 'modifiedAt',
				'value' => new Expression('NOW()')
			]
		];
	}

	// yii\base\Model ---------

	/**
	 * @inheritdoc
	 */
	public function rules() {

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'userId', 'modelId' ], 'required' ],
			[ [ 'id', 'value' ], 'safe' ],
			// Unique
			[ [ 'userId', 'modelId', 'type' ], 'unique', 'targetAttribute' => [ 'userId', 'modelId', 'type' ], 'comboNotUnique' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) ],
			// Other
			[ 'type', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'userId', 'modelId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'modelId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'followerId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FOLLOWER ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Follower ------------------------------

	/**
	 * Returns the parent model using one-to-one(hasOne) relationship.
	 *
	 * @return ActiveRecord The parent model.
	 */
	abstract public function getModel();

	/**
	 * Return the user who follows model.
	 *
	 * @return \cmsgears\core\common\models\entities\User
	 */
	public function getFollower() {

		$userTable = CoreTables::getTableName( CoreTables::TABLE_USER );

		return $this->hasOne( User::class, [ 'id' => 'followerId' ] )->from( "$userTable as follower" );
	}

	/**
	 * Checks whether the follower belong to given parent model.
	 *
	 * @return boolean
	 */
	public function belongsTo( $model ) {

		return $this->modelId == $model->id;
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	// CMG parent classes --------------------

	// Follower ------------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'model', 'follower' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the mapping with parent model.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with parent model.
	 */
	public static function queryWithModel( $config = [] ) {

		$config[ 'relations' ]	= [ 'model' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the mapping with follower.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with follower.
	 */
	public static function queryWithFollower( $config = [] ) {

		$config[ 'relations' ]	= [ 'follower' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the mapping by type.
	 *
	 * @param integer $type
	 * @return \yii\db\ActiveQuery to query by type.
	 */
	public static function queryByType( $type ) {

        return self::find()->where( 'type=:type', [ ':type' => $type ] );
	}

	/**
	 * Return query to find the mapping by type and model id.
	 *
	 * @param integer $type
	 * @param integer $modelId
	 * @return \yii\db\ActiveQuery to query by type and model id.
	 */
	public static function queryByTypeModelId( $type, $modelId ) {

		return self::find()->where( 'type=:type AND modelId=:mid', [ ':type' => $type, ':mid' => $modelId ] );
	}

	/**
	 * Return query to find the mapping by type and follower id.
	 *
	 * @param integer $type
	 * @param integer $followerId
	 * @return \yii\db\ActiveQuery to query by type and follower id.
	 */
	public static function queryByTypeFollowerId( $type, $followerId ) {

		return self::find()->where( 'type=:type AND followerId=:fid', [ ':type' => $type, ':fid' => $followerId ] );
	}

	// Read - Find ------------

	/**
	 * Find and return all the follower using given type.
	 *
	 * @param integer $type
	 * @return Follower[]
	 */
	public static function findByType( $type ) {

		return self::queryByType( $type )->all();
	}

	/**
	 * Find and return all the follower using given type and model id.
	 *
	 * @param integer $type
	 * @param integer $modelId
	 * @return Follower[]
	 */
	public static function findByTypeModelId( $type, $modelId ) {

		return self::queryByTypeModelId( $type, $modelId )->all();
	}

	/**
	 * Find and return all the follower using given type and follower id.
	 *
	 * @param integer $type
	 * @param integer $followerId
	 * @return Follower[]
	 */
	public static function findByTypeFollowerId( $type, $followerId ) {

		return self::queryByTypeFollowerId( $type, $followerId )->all();
	}

	/**
	 * Find and return the follower using given model id, follower id and type.
	 *
	 * @param integer $modelId
	 * @param integer $followerId
	 * @param integer $type
	 * @return Follower
	 */
	public static function findByFollower( $modelId, $followerId, $type = self::TYPE_FOLLOW ) {

		return self::find()->where( 'modelId=:mid AND followerId=:fid AND type =:type', [ ':mid' => $modelId, ':fid' => $followerId, ':type' => $type ] )->one();
	}

	/**
	 * Check whether follower exist using given model id, follower id and type.
	 *
	 * @param integer $modelId
	 * @param integer $followerId
	 * @param integer $type
	 * @return boolean
	 */
	public static function isExistByFollower( $modelId, $followerId, $type = self::TYPE_FOLLOW ) {

		$follower = self::findByFollower( $modelId, $followerId, $type );

		return isset( $follower );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
