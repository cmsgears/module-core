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
 * The model using this trait must have name and type columns. The model can support
 * unique name for a particular type. Use SlugTypeTrait for more strict options in case
 * same name is allowed for multiple models having same type.
 *
 * @property string $name
 * @property string $type
 *
 * @since 1.0.0
 */
trait NameTypeTrait {

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

	// NameTypeTrait -------------------------

	public function getDisplayName() {

		return !empty( $this->title ) ? $this->title : $this->name;
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// NameTypeTrait -------------------------

	// Read - Query -----------

	/**
	 * Return query to find the models by given name.
	 *
	 * @param string $name
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by name.
	 */
	public static function queryByName( $name, $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			return static::find()->where( 'name=:name AND siteId=:siteId', [ ':name' => $name, ':siteId' => $siteId ] );
		}
		else {

			return static::find()->where( 'name=:name', [ ':name' => $name ] );
		}
	}

	/**
	 * Return query to find the models by given type.
	 *
	 * @param string $type
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by type.
	 */
	public static function queryByType( $type, $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			return static::find()->where( 'type=:type AND siteId=:siteId', [ ':type' => $type, ':siteId' => $siteId ] );
		}
		else {

			return static::find()->where( 'type=:type', [ ':type' => $type ] );
		}
	}

	/**
	 * Return query to find the models by given name and type.
	 *
	 * @param string $name
	 * @param string $type
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by name and type.
	 */
	public static function queryByNameType( $name, $type, $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			return static::find()->where( 'name=:name AND type=:type AND siteId=:siteId', [ ':name' => $name, ':type' => $type, ':siteId' => $siteId ] );
		}
		else {

			return static::find()->where( 'name=:name AND type=:type', [ ':name' => $name, ':type' => $type ] );
		}
	}

	// Read - Find ------------

	/**
	 * Find and return models using given type.
	 *
	 * @param string $type
	 * @param array $config
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public static function findByType( $type, $config = [] ) {

		return self::queryByType( $type, $config )->all();
	}

	/**
	 * Find and return first model using given type. It can be useful in cases where only
	 * one model is allowed for a type.
	 *
	 * @param string $type
	 * @param array $config
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public static function findFirstByType( $type, $config = [] ) {

		return self::queryByType( $type, $config )->one();
	}

	/**
	 * Find and return models using given name.
	 *
	 * @param string $name
	 * @param array $config
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public static function findByName( $name, $config = [] ) {

		return self::queryByName( $name, $config )->all();
	}

	/**
	 * Find and return first model using given name. It can be useful in cases where only
	 * one model is allowed for a name.
	 *
	 * @param string $name
	 * @param array $config
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public static function findFirstByName( $name, $config = [] ) {

		return self::queryByName( $name, $config )->one();
	}

	/**
	 * Find and return models using given name and type.
	 *
	 * @param string $name
	 * @param string $type
	 * @param array $config
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public static function findByNameType( $name, $type, $config = [] ) {

		return self::queryByNameType( $name, $type, $config )->all();
	}

	/**
	 * Find and return first model using given name and type.
	 *
	 * @param string $name
	 * @param string $type
	 * @param array $config
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public static function findFirstByNameType( $name, $type, $config = [] ) {

		return self::queryByNameType( $name, $type, $config )->one();
	}

	/**
	 * Check whether model exist for given name and type.
	 *
	 * @param string $name
	 * @param string $type
	 * @param array $config
	 * @return boolean
	 */
	public static function isExistByNameType( $name, $type, $config = [] ) {

		$model	= self::findByNameType( $name, $type, $config );

		return isset( $model );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
