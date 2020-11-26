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
 * The model using this trait must have name, slug and type columns. The model using this
 * trait must have unique slug for a particular type. Use NameTypeTrait for lenient options.
 *
 * @property string $slug
 * @property string $type
 *
 * @since 1.0.0
 */
trait SlugTypeTrait {

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

	// SlugTypeTrait -------------------------

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// SlugTypeTrait -------------------------

	// Read - Query -----------

	/**
	 * Return query to find the models by given slug.
	 *
	 * @param string $slug
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by slug.
	 */
	public static function queryBySlug( $slug, $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$limit	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$query	= null;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			$query = static::find()->where( 'slug=:slug AND siteId=:siteId', [ ':slug' => $slug, ':siteId' => $siteId ] );
		}
		else {

			$query = static::find()->where( 'slug=:slug', [ ':slug' => $slug ] );
		}

		if( $limit > 0 ) {

			$query->limit( $limit );
		}

		return $query;
	}

	/**
	 * Return query to find the models by given slug and type.
	 *
	 * @param string $slug
	 * @param string $type
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by slug and type.
	 */
	public static function queryBySlugType( $slug, $type, $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$limit	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$query	= null;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			$query = static::find()->where( 'slug=:slug AND type=:type AND siteId=:siteId', [ ':slug' => $slug, ':type' => $type, ':siteId' => $siteId ] );
		}
		else {

			$query = static::find()->where( 'slug=:slug AND type=:type', [ ':slug' => $slug, ':type' => $type ] );
		}

		if( $limit > 0 ) {

			$query->limit( $limit );
		}

		return $query;
	}

	// Read - Find ------------

	/**
	 * Find and return models using given slug.
	 *
	 * @param string $slug
	 * @param array $config
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public static function findBySlug( $slug, $config = [] ) {

		return self::queryBySlug( $slug, $config )->all();
	}

	/**
	 * Find and return first model using given slug.
	 *
	 * @param string $slug
	 * @param array $config
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public static function findFirstBySlug( $slug, $config = [] ) {

		return self::queryBySlug( $slug, $config )->one();
	}

	/**
	 * Find and return model using given slug and type.
	 *
	 * @param string $slug
	 * @param string $type
	 * @param array $config
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public static function findBySlugType( $slug, $type, $config = [] ) {

		return self::queryBySlugType( $slug, $type, $config )->one();
	}

	/**
	 * check whether model exist for given slug and type.
	 *
	 * @param string $slug
	 * @param string $type
	 * @param array $config
	 * @return boolean
	 */
	public static function isExistBySlugType( $slug, $type, $config = [] ) {

		$model = static::findBySlugType( $slug, $type, $config );

		return isset( $model );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
