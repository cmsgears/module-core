<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\base;

// Yii Imports
use Yii;

/**
 * FeaturedTrait can be used to mark a model as featured or pinned.
 *
 * @property integer $createdBy
 * @property integer $modifiedBy
 *
 * @since 1.0.0
 */
trait FeaturedTrait {

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

	// FeaturedTrait -------------------------

	/**
	 * @inheritdoc
	 */
	public function getPinnedStr() {

		return Yii::$app->formatter->asBoolean( $this->pinned );
	}

	/**
	 * @inheritdoc
	 */
	public function getFeaturedStr() {

		return Yii::$app->formatter->asBoolean( $this->featured );
	}

	/**
	 * @inheritdoc
	 */
	public function getPopularStr() {

		return Yii::$app->formatter->asBoolean( $this->popular );
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// FeaturedTrait -------------------------

	// Read - Query -----------

	/**
	 * Generate and return the query to filter pinned models based on multi-site configuration.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery
	 */
	public static function queryByPinned( $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;
		$conditions = $config[ 'conditions' ] ?? [];

		$limit	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$query	= null;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			$query = static::find()->where( 'pinned=:pinned AND siteId=:siteId', [ ':pinned' => true, ':siteId' => $siteId ] );
		}
		else {

			$query = static::find()->where( 'pinned=:pinned', [ ':pinned' => true ] );
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

		// Limit ---------------

		if( $limit > 0 ) {

			$query->limit( $limit );
		}

		return $query;
	}

	/**
	 * Generate and return the query to filter pinned models based on multi-site configuration.
	 *
	 * It's useful for models having type column.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery
	 */
	public static function queryByPinnedType( $type, $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;
		$conditions = $config[ 'conditions' ] ?? [];

		$limit	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$query	= null;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			$query = static::find()->where( 'type=:type AND pinned=:pinned AND siteId=:siteId', [ ':type' => $type, ':pinned' => true, ':siteId' => $siteId ] );
		}
		else {

			$query = static::find()->where( 'type=:type AND pinned=:pinned', [ ':type' => $type, ':pinned' => true ] );
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

		// Limit ---------------

		if( $limit > 0 ) {

			$query->limit( $limit );
		}

		return $query;
	}

	/**
	 * Generate and return the query to filter featured models based on multi-site configuration.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery
	 */
	public static function queryByFeatured( $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;
		$conditions = $config[ 'conditions' ] ?? [];

		$limit	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$query	= null;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			$query = static::find()->where( 'featured=:featured AND siteId=:siteId', [ ':featured' => true, ':siteId' => $siteId ] );
		}
		else {

			$query = static::find()->where( 'featured=:featured', [ ':featured' => true ] );
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

		// Limit ---------------

		if( $limit > 0 ) {

			$query->limit( $limit );
		}

		return $query;
	}

	/**
	 * Generate and return the query to filter featured models based on multi-site configuration.
	 *
	 * It's useful for models having type column.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery
	 */
	public static function queryByFeaturedType( $type, $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;
		$conditions = $config[ 'conditions' ] ?? [];

		$limit	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$query	= null;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			$query = static::find()->where( 'type=:type AND featured=:featured AND siteId=:siteId', [ ':type' => $type, ':featured' => true, ':siteId' => $siteId ] );
		}
		else {

			$query = static::find()->where( 'type=:type AND featured=:featured', [ ':type' => $type, ':featured' => true ] );
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

		// Limit ---------------

		if( $limit > 0 ) {

			$query->limit( $limit );
		}

		return $query;
	}

	/**
	 * Generate and return the query to filter popular models based on multi-site configuration.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery
	 */
	public static function queryByPopular( $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;
		$conditions = $config[ 'conditions' ] ?? [];

		$limit	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$query	= null;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			$query = static::find()->where( 'popular=:popular AND siteId=:siteId', [ ':popular' => true, ':siteId' => $siteId ] );
		}
		else {

			$query = static::find()->where( 'popular=:popular', [ ':popular' => true ] );
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

		// Limit ---------------

		if( $limit > 0 ) {

			$query->limit( $limit );
		}

		return $query;
	}

	/**
	 * Generate and return the query to filter popular models based on multi-site configuration.
	 *
	 * It's useful for models having type column.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery
	 */
	public static function queryByPopularType( $type, $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;
		$conditions = $config[ 'conditions' ] ?? [];

		$limit	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$query	= null;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			$query = static::find()->where( 'type=:type AND popular=:popular AND siteId=:siteId', [ ':type' => $type, ':popular' => true, ':siteId' => $siteId ] );
		}
		else {

			$query = static::find()->where( 'type=:type AND popular=:popular', [ ':type' => $type, ':popular' => true ] );
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

		// Limit ---------------

		if( $limit > 0 ) {

			$query->limit( $limit );
		}

		return $query;
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
