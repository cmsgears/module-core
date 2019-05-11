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

		$model = $modelClass::findBySlugType( $slug, $type, $config );

		// Retrieve global model as fallback in case of multisite
		if( empty( $model ) && $modelClass::isMultiSite() ) {

			$config[ 'ignoreSite' ] = true;

			$model = $modelClass::findBySlugType( $slug, $type, $config );
		}

		return $model;
	}

	// Read - Lists ----

	// Read - Maps -----

	public function getSlugNameMapByType( $type, $config = [] ) {

		$config[ 'conditions' ][ 'type' ] = $type;

		$config[ 'nameColumn' ]		= $config[ 'nameColumn' ] ?? 'slug';
		$config[ 'valueColumn' ]	= $config[ 'valueColumn' ] ?? 'name';
		$config[ 'nameAlias' ]		= 'slug';
		$config[ 'valueAlias' ]		= 'name';

		return static::generateMap( $config );
	}

	public function getSlugModelMap( $config = [] ) {

		$config[ 'key' ] = 'slug';

		return $this->generateObjectMap( $config );
	}

	public function getSlugModelMapByType( $type, $config = [] ) {

		$config[ 'key' ] = 'slug';

		$config[ 'conditions' ][ 'type' ] = $type;

		return $this->generateObjectMap( $config );
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
