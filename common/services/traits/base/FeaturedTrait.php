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
 * FeaturedTrait provide methods specific to featured models.
 *
 * @since 1.0.0
 */
trait FeaturedTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// FeaturedTrait -------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getPinned( $config = [] ) {

		$modelClass	= static::$modelClass;

		return $modelClass::find()->where( 'pinned=:pinned', [ ':pinned' => true ] )->all();
	}

	public function getFeatured( $config = [] ) {

		$modelClass	= static::$modelClass;

		return $modelClass::find()->where( 'featured=:featured', [ ':featured' => true ] )->all();
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

	// FeaturedTrait -------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
