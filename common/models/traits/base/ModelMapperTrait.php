<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\base;

// Yii Import
use Yii;

/**
 * It provide methods to map a parent model to another model.
 *
 * The model using this trait must have modelId, parentId and parentType columns. These columns
 * map the source i.e. parent to the target using modelId column via mapper table. It allows only
 * one model mapping for same parentId and parentType combination.
 *
 * It also provide few additional methods for models to mark the mapping as deleted by setting
 * [[$active]] to false to avoid allocating a new row each time a mapping is created. The mapping
 * can be simply de-activated in such cases.
 *
 * Examples: [[\cmsgears\core\common\models\mappers\ModelCategory]], [[\cmsgears\core\common\models\mappers\ModelTag]],
 * [[\cmsgears\core\common\models\mappers\ModelFile]]
 *
 * @property integer $modelId
 * @property integer $parentId
 * @property integer $parentType
 * @property boolean $type
 * @property boolean $active
 *
 * @since 1.0.0
 */
trait ModelMapperTrait {

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

	// ModelMapperTrait ----------------------

	/**
	 * @inheritdoc
	 */
	public function isParentValid( $parentId, $parentType ) {

		return $this->parentId == $parentId && $this->parentType == $parentType;
	}

	/**
	 * @inheritdoc
	 */
	public function isMappingActive( $parentId, $parentType ) {

		return $this->active && $this->parentId == $parentId && $this->parentType == $parentType;
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// ModelMapperTrait ----------------------

	// Read - Query -----------

	/**
	 * Return query to find the mapping with corresponding mapped model.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with mapped model.
	 */
	public static function queryWithModel( $config = [] ) {

		$config[ 'relations' ]	= [ 'model' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the mapping for given mapped model id.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $modelId
	 * @return \yii\db\ActiveQuery to find with mapped model id.
	 */
	public static function queryByModelId( $parentId, $parentType, $modelId ) {

		$tableName = self::tableName();

		return self::queryWithModel()->where( "$tableName.parentId=:pid AND $tableName.parentType=:ptype AND $tableName.modelId=:mid", [ ':pid' => $parentId, ':ptype' => $parentType, ':mid' => $modelId ] );
	}

	/**
	 * Return query to find the mapping for given parent id and parent type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $modelId
	 * @return \yii\db\ActiveQuery to find with parent id and parent type.
	 */
	public static function queryByParent( $parentId, $parentType ) {

		$tableName = self::tableName();

		return self::queryWithModel()->where( "$tableName.parentId=:pid AND $tableName.parentType=:ptype", [ ':pid' => $parentId, ':ptype' => $parentType ] );
	}

	// Read - Find ------------

	/**
	 * Find and return the mapping for given parent id, parent type and mapped model id.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $modelId
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public static function findByModelId( $parentId, $parentType, $modelId ) {

		return self::queryByModelId( $parentId, $parentType, $modelId )->one();
	}

	/**
	 * Find and return the mappings for given mapped model id.
	 *
	 * @param integer $modelId
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public static function findAllByModelId( $modelId ) {

		return self::find()->where( 'modelId=:id', [ ':id' => $modelId ] )->all();
	}

	/**
	 * Find and return the mappings for given parent id and parent type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public static function findByParent( $parentId, $parentType ) {

		return self::queryByParent( $parentId, $parentType )->all();
	}

	/**
	 * Find and return the mappings for given parent id. It's useful in cases where only
	 * single parent type is allowed.
	 *
	 * @param integer $parentId
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public static function findByParentId( $parentId ) {

		return self::find()->where( 'parentId=:id', [ ':id' => $parentId ] )->all();
	}

	/**
	 * Find and return the mappings for given parent type.
	 *
	 * @param string $parentType
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public static function findByParentType( $parentType ) {

		return self::find()->where( 'parentType=:ptype', [ ':ptype' => $parentType ] )->all();
	}

	/**
	 * Find and return the mappings for type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $type
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public static function findByType( $parentId, $parentType, $type ) {

		return self::queryByParent( $parentId, $parentType )->andFilterWhere( [ 'like', 'type', $type ] )->all();
	}

	/**
	 * Find and return the active mappings for given parent id and parent type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public static function findActiveByParent( $parentId, $parentType ) {

		return self::queryByParent( $parentId, $parentType )->andWhere( 'active=1' )->all();
	}

	/**
	 * Find and return the active mappings for given parent id. It's useful in cases where only
	 * single parent type is allowed.
	 *
	 * @param integer $parentId
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public static function findActiveByParentId( $parentId ) {

		return self::find()->where( 'parentId=:pid AND active=1', [ ':pid' => $parentId ] )->all();
	}

	/**
	 * Find and return the active mappings for given parent type.
	 *
	 * @param string $parentType
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public static function findActiveByParentType( $parentType ) {

		return self::find()->where( 'parentType=:ptype AND active=1', [ ':ptype' => $parentType ] )->all();
	}

	/**
	 * Find and return the mappings for type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $type
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public static function findActiveByType( $parentId, $parentType, $type ) {

		return self::queryByParent( $parentId, $parentType )->andWhere( 'active=1' )->andFilterWhere( [ 'like', 'type', $type ] )->all();
	}

	/**
	 * Find and return the active mappings for given mapped model id and parent type.
	 *
	 * @param integer $modelId
	 * @param string $parentType
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public static function findActiveByModelIdParentType( $modelId, $parentType ) {

		return self::find()->where( 'modelId=:mid AND parentType=:ptype AND active=1',	[ ':mid' => $modelId, ':ptype' => $parentType] )->all();
	}

	// Create -----------------

	// Update -----------------

	/**
	 * Disable all the mappings for given parent id and parent type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
     * @return integer number of rows.
     * @throws Exception in case query failed.
	 */
	public static function disableByParent( $parentId, $parentType ) {

		$tableName = self::tableName();

		// Disable all mappings
		$query		= "UPDATE $tableName SET `active`=0 WHERE `parentType`='$parentType' AND `parentId`=$parentId";
		$command	= Yii::$app->db->createCommand( $query );

		$command->execute();
	}

	// Delete -----------------

	/**
	 * Delete all mappings related to given parent id. It's useful in cases where only
	 * single parent type is allowed.
	 *
	 * @param integer $parentId
	 * @return integer number of rows.
	 */
	public static function deleteByParentId( $parentId ) {

		return self::deleteAll( 'parentId=:id', [ ':id' => $parentId ] );
	}

	/**
	 * Delete all mappings related to given parent type.
	 *
	 * @param string $parentType
	 * @return integer number of rows.
	 */
	public static function deleteByParentType( $parentType ) {

		return self::deleteAll( 'parentType=:id', [ ':id' => $parentType ] );
	}

	/**
	 * Delete all mappings by given parent id and type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @return integer number of rows.
	 */
	public static function deleteByParent( $parentId, $parentType ) {

		return self::deleteAll( 'parentId=:pid AND parentType=:type', [ ':pid' => $parentId, ':type' => $parentType ] );
	}

	/**
	 * Delete all mappings related to given model id.
	 *
	 * @param integer $modelId
	 * @return integer number of rows.
	 */
	public static function deleteByModelId( $modelId ) {

		return self::deleteAll( 'modelId=:mid', [ ':mid' => $modelId ] );
	}

}
