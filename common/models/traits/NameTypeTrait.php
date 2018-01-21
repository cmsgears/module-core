<?php
namespace cmsgears\core\common\models\traits;

// Yii Import
use Yii;

/**
 * The model using this trait must have name and type columns. The model can support
 * unique name for a particular type. Use SlugTypeTrait for more strict options.
 */
trait NameTypeTrait {

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// NameTypeTrait -------------------------

	// Validators -------------

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// NameTypeTrait -------------------------

	// Read - Query -----------

	public static function queryByName( $name ) {

		if( static::$multiSite ) {

			$siteId	= Yii::$app->core->siteId;

			return static::find()->where( 'name=:name AND siteId=:siteId', [ ':name' => $name, ':siteId' => $siteId ] );
		}
		else {

			return static::find()->where( 'name=:name', [ ':name' => $name ] );
		}
	}

	public static function queryByType( $type ) {

		if( static::$multiSite ) {

			$siteId	= Yii::$app->core->siteId;

			return static::find()->where( 'type=:type AND siteId=:siteId', [ ':type' => $type, ':siteId' => $siteId ] );
		}
		else {

			return static::find()->where( 'type=:type', [ ':type' => $type ] );
		}
	}

	public static function queryByNameType( $name, $type ) {

		if( static::$multiSite ) {

			$siteId	= Yii::$app->core->siteId;

			return static::find()->where( 'name=:name AND type=:type AND siteId=:siteId', [ ':name' => $name, ':type' => $type, ':siteId' => $siteId ] );
		}
		else {

			return static::find()->where( 'name=:name AND type=:type', [ ':name' => $name, ':type' => $type ] );
		}
	}

	// Read - Find ------------

	/**
	 * @return array - ActiveRecord by type
	 */
	public static function findByType( $type, $first = false ) {

		if( $first ) {

			return self::queryByType( $type )->one();
		}

		return self::queryByType( $type )->all();
	}

	/**
	 * @return ActiveRecord - by name
	 */
	public static function findByName( $name, $first = false ) {

		if( $first ) {

			return self::queryByName( $name )->one();
		}

		return self::queryByName( $name )->all();
	}

	/**
	 * @return ActiveRecord - by name and type
	 */
	public static function findByNameType( $name, $type, $first = false ) {

		if( $first ) {

			return self::queryByNameType( $name, $type )->one();
		}

		return self::queryByNameType( $name, $type )->all();
	}

	/**
	 * @return boolean - check whether model exist for given name and type
	 */
	public static function isExistByNameType( $name, $type ) {

		$model	= self::findByNameType( $name, $type );

		return isset( $model );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
