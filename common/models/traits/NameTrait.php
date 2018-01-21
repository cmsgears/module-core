<?php
namespace cmsgears\core\common\models\traits;

// Yii Import
use Yii;

/**
 * The model using this trait must have name column. The model can also support unique
 * name. Use SlugTrait for more strict options.
 */
trait NameTrait {

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
	 * @return ActiveRecord - having matching name.
	 */
	public static function queryByName( $name ) {

		if( static::$multiSite ) {

			$siteId	= Yii::$app->core->siteId;

			return static::find()->where( 'name=:name AND siteId=:siteId', [ ':name' => $name, ':siteId' => $siteId ] );
		}
		else {

			return static::find()->where( 'name=:name', [ ':name' => $name ] );
		}
	}

	// Read - Find ------------

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
	 * @return boolean - check whether model exist for given name
	 */
	public static function isExistByName( $name ) {

		$model	= static::findByName( $name );

		return isset( $model );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
