<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\traits\base;

/**
 * SlugTrait provide methods for models having sluggable behavior to support unique slug.
 *
 * @since 1.0.0
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

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

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
