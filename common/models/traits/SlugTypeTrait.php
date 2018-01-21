<?php
namespace cmsgears\core\common\models\traits;

// Yii Import
use Yii;

/**
 * The model using this trait must have name, slug and type columns. The model using this
 * trait must have unique slug for a particular type. Use NameTypeTrait for lenient options.
 */
trait SlugTypeTrait {

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
	public static function queryBySlug( $slug, $config = [] ) {

		if( static::$multiSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : (static::$multiSite ? Yii::$app->core->siteId : null );

			return static::find()->where( 'slug=:slug AND siteId=:siteId', [ ':slug' => $slug, ':siteId' => $siteId ] );
		}
		else {

			return static::find()->where( 'slug=:slug', [ ':slug' => $slug ] );
		}
	}

	/**
	 * @return ActiveRecord - having matching slug for a specific type.
	 */
	public static function queryBySlugType( $slug, $type, $config = [] ) {

		if( static::$multiSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : (static::$multiSite ? Yii::$app->core->siteId : null );

			return static::find()->where( 'slug=:slug AND type=:type AND siteId=:siteId', [ ':slug' => $slug, ':type' => $type, ':siteId' => $siteId ] );
		}
		else {

			return static::find()->where( 'slug=:slug AND type=:type', [ ':slug' => $slug, ':type' => $type ] );
		}
	}

	// Read - Find ------------

	/**
	 * @return mixed - by slug
	 */
	public static function findBySlug( $slug, $first = false, $config = [] ) {

		if( $first ) {

			return self::queryBySlug( $slug, $config )->one();
		}

		return self::queryBySlug( $slug, $config )->all();
	}

	/**
	 * @return ActiveRecord - by slug and type
	 */
	public static function findBySlugType( $slug, $type, $config = [] ) {

		return self::queryBySlugType( $slug, $type, $config )->one();
	}

	/**
	 * @return boolean - check whether model exist for given slug and type
	 */
	public static function isExistBySlugType( $slug, $type, $config = [] ) {

		$model	= static::findBySlugType( $slug, $type, $config );

		return isset( $model );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
