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

use cmsgears\core\common\models\interfaces\base\IFeatured;
use cmsgears\core\common\models\interfaces\base\IFollower;
use cmsgears\core\common\models\interfaces\resources\IData;

use cmsgears\core\common\models\traits\base\FeaturedTrait;
use cmsgears\core\common\models\traits\base\FollowerTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;

/**
 * Follower represents interest of one model in another model.
 *
 * @property integer $id
 * @property integer $modelId
 * @property integer $parentId
 * @property integer $type
 * @property integer $order
 * @property boolean $active
 * @property boolean $pinned
 * @property boolean $featured
 * @property boolean $popular
 * @property integer $createdAt
 * @property integer $modifiedAt
 * @property string data
 *
 * @since 1.0.0
 */
abstract class Follower extends Mapper implements IData, IFeatured, IFollower {

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

	use DataTrait;
	use FeaturedTrait;
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
			[ [ 'modelId', 'parentId', 'type' ], 'required' ],
			[ [ 'id', 'data' ], 'safe' ],
			// Unique
			[ [ 'type' ], 'unique', 'targetAttribute' => [ 'modelId', 'parentId', 'type' ], 'comboNotUnique' => 'Follwer with the same type already exist.' ],
			// Text Limit
			[ 'type', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			// Other
			[ [ 'active', 'pinned', 'featured', 'popular' ], 'boolean' ],
			[ 'order', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'modelId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'modelId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FOLLOWER ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'order' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
			'pinned' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PINNED ),
			'featured' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FEATURED ),
			'popular' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_POPULAR ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Follower ------------------------------

	/**
	 * Returns the follower model using one-to-one(hasOne) relationship.
	 *
	 * @return \cmsgears\core\common\models\entities\User
	 */
	public function getModel() {

		$userTable = CoreTables::getTableName( CoreTables::TABLE_USER );

		return $this->hasOne( User::class, [ 'id' => 'modelId' ] )->from( "$userTable as follower" );
	}

	/**
	 * Return the parent model followed by users.
	 *
	 * @return ActiveRecord The parent model.
	 */
	abstract public function getParent();

	/**
	 * Checks whether the follower belong to given user.
	 *
	 * @return boolean
	 */
	public function belongsTo( $model ) {

		return $this->modelId == $model->id;
	}

	/**
	 * Checks whether the follower belong to given parent model.
	 *
	 * @return boolean
	 */
	public function belongsToParent( $model ) {

		return $this->parentId == $model->id;
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

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'model', 'parent' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the mapping with follower.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with follower.
	 */
	public static function queryWithModel( $config = [] ) {

		$config[ 'relations' ] = [ 'model' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the mapping with parent model.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with parent model.
	 */
	public static function queryWithParent( $config = [] ) {

		$config[ 'relations' ] = [ 'parent' ];

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
	 * Return query to find the mapping by type and follower id.
	 *
	 * @param integer $type
	 * @param integer $modelId
	 * @return \yii\db\ActiveQuery to query by type and follower id.
	 */
	public static function queryByTypeModelId( $type, $modelId ) {

		return self::find()->where( 'type=:type AND modelId=:mid', [ ':type' => $type, ':mid' => $modelId ] );
	}

	/**
	 * Return query to find the mapping by type and parent id.
	 *
	 * @param integer $type
	 * @param integer $parentId
	 * @return \yii\db\ActiveQuery to query by type and parent id.
	 */
	public static function queryByTypeParentId( $type, $parentId ) {

		return self::find()->where( 'type=:type AND parentId=:pid', [ ':type' => $type, ':pid' => $parentId ] );
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
	 * Find and return all the follower using given type and follower id.
	 *
	 * @param integer $type
	 * @param integer $modelId
	 * @return Follower[]
	 */
	public static function findByTypeModelId( $type, $modelId ) {

		return self::queryByTypeModelId( $type, $modelId )->all();
	}

	/**
	 * Find and return all the follower using given type and parent id.
	 *
	 * @param integer $type
	 * @param integer $parentId
	 * @return Follower[]
	 */
	public static function findByTypeParentId( $type, $parentId ) {

		return self::queryByTypeParentId( $type, $parentId )->all();
	}

	/**
	 * Find and return the follower using given follower id, parent id and type.
	 *
	 * @param integer $modelId
	 * @param integer $parentId
	 * @param integer $type
	 * @return Follower
	 */
	public static function findByFollower( $modelId, $parentId, $type = self::TYPE_FOLLOW ) {

		return self::find()->where( 'modelId=:mid AND parentId=:pid AND type =:type', [ ':mid' => $modelId, ':pid' => $parentId, ':type' => $type ] )->one();
	}

	/**
	 * Check whether follower exist using given follower id, parent id and type.
	 *
	 * @param integer $modelId
	 * @param integer $parentId
	 * @param integer $type
	 * @return boolean
	 */
	public static function isExistByFollower( $modelId, $parentId, $type = self::TYPE_FOLLOW ) {

		$follower = self::findByFollower( $modelId, $parentId, $type );

		return isset( $follower );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	/**
	 * Delete all mappings related to given follower id.
	 *
	 * @param integer $modelId
	 * @return integer Number of rows deleted.
	 */
	public static function deleteByModelId( $modelId ) {

		return self::deleteAll( 'modelId=:mid', [ ':mid' => $modelId ] );
	}

	/**
	 * Delete all mappings by given parent id and type.
	 *
	 * @param integer $parentId
	 * @return integer Number of rows deleted.
	 */
	public static function deleteByParentId( $parentId ) {

		return self::deleteAll( 'parentId=:pid', [ ':pid' => $parentId ] );
	}

}
