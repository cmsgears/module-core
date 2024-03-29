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
 * The model using this trait must have name and slug columns. The model must also support
 * unique slug irrespective of site in multi-site environment. Use NameTrait for lenient options.
 *
 * @property string $slug
 *
 * @since 1.0.0
 */
trait SlugTrait {

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

	// SlugTrait -----------------------------

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// SlugTrait -----------------------------

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
		$conditions = $config[ 'conditions' ] ?? [];

		$limit	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$offset	= isset( $config[ 'offset' ] ) ? $config[ 'offset' ] : 0;
		$query	= null;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			$query = static::find()->where( 'slug=:slug AND siteId=:siteId', [ ':slug' => $slug, ':siteId' => $siteId ] );
		}
		else {

			$query = static::find()->where( 'slug=:slug', [ ':slug' => $slug ] );
		}

		// Conditions ----------

		if( isset( $conditions ) ) {

			foreach( $conditions as $ckey => $condition ) {

				if( is_numeric( $ckey ) ) {

					$query->andWhere( $condition );

					unset( $conditions[ $ckey ] );
				}
			}

			$query->andWhere( $conditions );
		}

		// Offset --------------

		if( $offset > 0 ) {

			$query->offset( $offset );
		}

		// Limit ---------------

		if( $limit > 0 ) {

			$query->limit( $limit );
		}

		return $query;
	}

	// Read - Find ------------

	/**
	 * Find and return model using given slug.
	 *
	 * @param string $slug
	 * @param array $config
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public static function findBySlug( $slug, $config = [] ) {

		return static::queryBySlug( $slug, $config )->one();
	}

	/**
	 * Check whether model exist for given slug.
	 *
	 * @return boolean
	 */
	public static function isExistBySlug( $slug, $config = [] ) {

		$model = static::findBySlug( $slug, $config );

		return isset( $model );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
