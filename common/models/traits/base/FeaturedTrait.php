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

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			return static::find()->where( 'pinned=:pinned AND siteId=:siteId', [ ':pinned' => true, ':siteId' => $siteId ] );
		}
		else {

			return static::find()->where( 'pinned=:pinned', [ ':pinned' => true ] );
		}
	}

	/**
	 * Generate and return the query to filter pinned models based on multi-site configuration.
	 *
	 * It's useful for models having type column.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery
	 */
	public static function queryByTypePinned( $type, $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			return static::find()->where( 'type=:type AND pinned=:pinned AND siteId=:siteId', [ ':type' => $type, ':pinned' => true, ':siteId' => $siteId ] );
		}
		else {

			return static::find()->where( 'type=:type AND pinned=:pinned', [ ':type' => $type, ':pinned' => true ] );
		}
	}

	/**
	 * Generate and return the query to filter featured models based on multi-site configuration.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery
	 */
	public static function queryByFeatured( $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			return static::find()->where( 'featured=:featured AND siteId=:siteId', [ ':featured' => true, ':siteId' => $siteId ] );
		}
		else {

			return static::find()->where( 'featured=:featured', [ ':featured' => true ] );
		}
	}

	/**
	 * Generate and return the query to filter featured models based on multi-site configuration.
	 *
	 * It's useful for models having type column.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery
	 */
	public static function queryByTypeFeatured( $type, $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			return static::find()->where( 'type=:type AND featured=:featured AND siteId=:siteId', [ ':type' => $type, ':featured' => true, ':siteId' => $siteId ] );
		}
		else {

			return static::find()->where( 'type=:type AND featured=:featured', [ ':type' => $type, ':featured' => true ] );
		}
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
