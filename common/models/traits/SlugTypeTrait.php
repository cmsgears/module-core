<?php
namespace cmsgears\core\common\models\traits;

// Yii Import
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * The model using this trait must have name, slug and type columns. It must also support unique name and slug for a particular type.
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
    public static function queryBySlug( $slug ) {

		if( static::$multiSite ) {

			$siteId	= Yii::$app->core->siteId;

			return static::find()->where( 'slug=:slug AND siteId=:siteId', [ ':slug' => $slug, ':siteId' => $siteId ] );
		}
		else {

			return static::find()->where( 'slug=:slug', [ ':slug' => $slug ] );
		}
    }

	/**
	 * @return ActiveRecord - having matching slug for a specific type.
	 */
    public static function queryBySlugType( $slug, $type ) {

		if( static::$multiSite ) {

			$siteId	= Yii::$app->core->siteId;

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
	public static function findBySlug( $slug, $first = false ) {

		if( $first ) {

			return self::queryBySlug( $slug )->one();
		}

		return self::queryBySlug( $slug )->all();
	}

	/**
	 * @return ActiveRecord - by slug and type
	 */
	public static function findBySlugType( $slug, $type ) {

		return self::queryBySlugType( $slug, $type )->one();
	}

    /**
     * @return boolean - check whether model exist for given slug and type
     */
	public static function isExistBySlugType( $slug, $type ) {

		$model	= static::findBySlugType( $slug, $type );

		return isset( $model );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}

?>