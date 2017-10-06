<?php
namespace cmsgears\core\common\services\traits;

/**
 * Used by services with base model having name, slug and type columns with sluggable behaviour which allows unique name for a type.
 */
trait SlugTypeTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// NameSlugTrait -------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getBySlug( $slug, $first = false ) {

		$modelClass = static::$modelClass;

		return $modelClass::findBySlug( $slug, $first );
	}

	public function getBySlugType( $slug, $type ) {

		$modelClass = static::$modelClass;

		return $modelClass::findBySlugType( $slug, $type );
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

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// NameSlugTrait -------------------------

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
