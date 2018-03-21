<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\base;

/**
 * Base model of all the resources specific to model.
 *
 * It provide methods to map parent and model resource. The columns parentId and parentType map the
 * source i.e. parent to the multiple rows of resource table. It allows to map multiple models for
 * same parentId and parentType combination.
 *
 * Examples: [[\cmsgears\core\common\models\resources\ModelComment]], [[\cmsgears\core\common\models\resources\ModelMeta]]
 *
 * @property integer $id
 * @property integer $parentId
 * @property string $parentType
 *
 * @since 1.0.0
 */
abstract class ModelResource extends Resource {

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

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ModelResource -------------------------

	/**
	 * Check parent using given parent id and type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @return boolean
	 */
	public function isParentValid( $parentId, $parentType ) {

		return $this->parentId == $parentId && $this->parentType == $parentType;
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	// CMG parent classes --------------------

	// ModelResource -------------------------

	// Read - Query -----------

	/**
	 * Return query to find the models by given parent id and parent type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by parent id and parent type.
	 */
	public static function queryByParent( $parentId, $parentType, $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			return static::find()->where( 'parentId=:pid AND parentType=:ptype AND siteId=:siteId', [ ':pid' => $parentId, ':ptype' => $parentType, ':siteId' => $siteId ] );
		}
		else {

			return static::find()->where( 'parentId=:pid AND parentType=:ptype', [ ':pid' => $parentId, ':ptype' => $parentType ] );
		}
	}

	// Read - Find ------------

	/**
	 * Find and return models using given parent id and parent type.
	 *
	 * @param string $parentId
	 * @param string $parentType
	 * @param array $config
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public static function findByParent( $parentId, $parentType, $config = [] ) {

		return self::queryByParent( $parentId, $parentType, $config )->all();
	}

	/**
	 * Find and return first model using given parent id and parent type.
	 *
	 * @param string $parentId
	 * @param string $parentType
	 * @param array $config
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public static function findFirstByParent( $parentId, $parentType, $config = [] ) {

		return self::queryByParent( $parentId, $parentType, $config )->one();
	}

	/**
	 * Find and return models using given parent id. It's useful in cases where only
	 * single parent type is allowed.
	 *
	 * @param string $parentId
	 * @param array $config
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public static function findByParentId( $parentId, $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			return static::find()->where( 'parentId=:pid AND siteId=:siteId', [ ':pid' => $parentId, ':siteId' => $siteId ] )->all();
		}
		else {

			return static::find()->where( 'parentId=:pid', [ ':pid' => $parentId ] )->all();
		}
	}

	/**
	 * Find and return models using given parent type.
	 *
	 * @param string $parentType
	 * @param array $config
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public static function findByParentType( $parentType, $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			return static::find()->where( 'parentType=:ptype AND siteId=:siteId', [ ':ptype' => $parentType, ':siteId' => $siteId ] )->all();
		}
		else {

			return static::find()->where( 'parentType=:ptype', [ ':ptype' => $parentType ] )->all();
		}
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	/**
	 * Delete all resources related to given parent id. It's useful in cases where only
	 * single parent type is allowed.
	 *
	 * @param integer $parentId
	 * @return integer number of rows.
	 */
	public static function deleteByParentId( $parentId ) {

		return self::deleteAll( 'parentId=:id', [ ':id' => $parentId ] );
	}

	/**
	 * Delete all resources related to given parent type.
	 *
	 * @param string $parentType
	 * @return integer number of rows.
	 */
	public static function deleteByParentType( $parentType ) {

		return self::deleteAll( 'parentType=:id', [ ':id' => $parentType ] );
	}

	/**
	 * Delete all resources by given parent id and type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @return integer number of rows.
	 */
	public static function deleteByParent( $parentId, $parentType ) {

		return self::deleteAll( 'parentId=:pid AND parentType=:type', [ ':pid' => $parentId, ':type' => $parentType ] );
	}

}
