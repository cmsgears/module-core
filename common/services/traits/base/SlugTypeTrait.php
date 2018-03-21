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
 * SlugTypeTrait provide methods for models having name, slug and type columns with sluggable
 * behavior which allows unique slug for a type.
 *
 * There might be models strict on slug to allow unique slug irrespective of type.
 *
 * @since 1.0.0
 */
trait SlugTypeTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SlugTypeTrait -------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getBySlug( $slug, $config = [] ) {

		$modelClass = static::$modelClass;

		return $modelClass::findBySlug( $slug, $config );
	}

	public function getFirstBySlug( $slug, $config = [] ) {

		$modelClass = static::$modelClass;

		return $modelClass::findFirstBySlug( $slug, $config );
	}

	public function getBySlugType( $slug, $type, $config = [] ) {

		$modelClass = static::$modelClass;

		return $modelClass::findBySlugType( $slug, $type, $config );
	}

	// Read - Lists ----

	// Read - Maps -----

	public function getSlugModelMap() {

		return $this->generateObjectMap( [ 'key' => 'slug' ] );
	}

	public function getSlugModelMapByType( $type ) {

		return $this->generateObjectMap( [ 'key' => 'slug', 'conditions' => [ 'type' => $type ] ] );
	}

	// Create -------------

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// SlugTypeTrait -------------------------

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
