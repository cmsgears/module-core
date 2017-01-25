<?php
namespace cmsgears\core\common\services\traits;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Used by services with base model having sluggable behaviour.
 */
trait SlugTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SlugTrait -----------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getBySlug( $slug ) {

		return self::findBySlug( $slug );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// SlugTrait -----------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public static function findBySlug( $slug ) {

		$modelClass	= static::$modelClass;

		return $modelClass::queryBySlug( $slug )->one();
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
