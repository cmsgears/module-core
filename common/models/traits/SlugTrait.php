<?php
namespace cmsgears\core\common\models\traits;

// Yii Import
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * The model using this trait must have name and slug columns. It must also support unique name irrespective of site in mutisite environment.
 */
trait SlugTrait {

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// SlugTypeTrait -------------------------

	// Validators -------------

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// SlugTypeTrait -------------------------

	// Read - Query -----------

	/**
	 * @return ActiveRecord - having matching slug.
	 */
	public static function queryBySlug( $slug ) {

		if( static::$multiSite ) {

			$siteId	= Yii::$app->core->siteId;

			return static::find()->where( 'slug=:slug AND siteId=:siteId', [ ':slug' => $slug, ':siteId' => $siteId ] );
		}
		else {

			return static::find()->where( 'slug=:slug', [ ':slug' => $slug ] );
		}
	}

	// Read - Find ------------

	/**
	 * @return mixed - by slug
	 */
	public static function findBySlug( $slug ) {

		return static::queryBySlug( $slug )->one();
	}

	/**
	 * @return boolean - check whether model exist for given slug
	 */
	public static function isExistBySlug( $slug ) {

		$model	= static::findBySlug( $slug );

		return isset( $model );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
