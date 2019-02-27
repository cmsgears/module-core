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
 * The model using this trait must have name column. The model can also support unique name.
 * Use [[SlugTrait]] for more strict options in case same name is allowed for multiple models.
 *
 * @property string $name
 *
 * @since 1.0.0
 */
trait NameTrait {

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

	// NameTrait -----------------------------

	public function getDisplayName() {

		return !empty( $this->title ) ? $this->title : $this->name;
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// NameTrait -----------------------------

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

	// Read - Find ------------

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
	 * Find and return first model using given name.
	 *
	 * @param string $name
	 * @param array $config
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public static function findFirstByName( $name, $config = [] ) {

		return self::queryByName( $name, $config )->one();
	}

	/**
	 * Check whether model exist for given name.
	 *
	 * @return boolean
	 */
	public static function isExistByName( $name, $config = [] ) {

		$model	= static::findFirstByName( $name, $config );

		return isset( $model );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
