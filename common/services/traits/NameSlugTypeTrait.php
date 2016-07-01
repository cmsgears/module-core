<?php
namespace cmsgears\core\common\services\traits;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Used by services with base model having name, slug and type columns with sluggable behaviour which allows unique name for a type.
 */
trait NameSlugTypeTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// NameSlugTrait -------------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getByName( $name, $first = false ) {

		$modelClass = static::$modelClass;

		$modelClass::findByName( $name, $first );
	}

	public function getBySlug( $slug, $first = false ) {

		$modelClass = static::$modelClass;

		$modelClass::findBySlug( $slug, $first );
	}

	public function getByNameType( $name, $type ) {

		$modelClass = static::$modelClass;

		$modelClass::findByNameType( $name, $type );
	}

	public function getBySlugType( $slug, $type ) {

		$modelClass = static::$modelClass;

		$modelClass::findBySlugType( $slug, $type );
	}

    // Read - Lists ----

   	public function getIdListByType( $type, $config = [] ) {

		$config[ 'conditions' ][ 'type' ] = $type;

		return self::findIdList( $config );
	}

	public function getIdNameListByType( $type, $options = [] ) {

		$options[ 'conditions' ][ 'type' ] = $type;

		return $this->getIdNameList( $options );
	}

    // Read - Maps -----

	public function getIdNameMapByType( $type, $options = [] ) {

		$options[ 'conditions' ][ 'type' ] = $type;

		return $this->getIdNameMap( $options );
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

?>