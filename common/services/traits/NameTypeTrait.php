<?php
namespace cmsgears\core\common\services\traits;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Used by services with base model having name, slug and type columns with sluggable behaviour which allows unique name for a type.
 */
trait NameTypeTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// NameSlugTrait -------------------------

	// Data Provider ------

	public function getPageByType( $type, $config = [] ) {

		$modelTable	= self::$modelTable;

		$config[ 'conditions' ][ "$modelTable.type" ] 	= $type;

		return $this->getPage( $config );
	}

	// Read ---------------

    // Read - Models ---

	public function getByName( $name, $first = false ) {

		$modelClass = static::$modelClass;

		return $modelClass::findByName( $name, $first );
	}

	public function getByType( $type, $first = false ) {

		$modelClass = static::$modelClass;

		return $modelClass::findByType( $type, $first );
	}

	public function getByNameType( $name, $type ) {

		$modelClass = static::$modelClass;

		return $modelClass::findByNameType( $name, $type );
	}

	public function searchByName( $name, $config = [] ) {

		$modelClass					= self::$modelClass;
		$modelTable					= self::$modelTable;

		$config[ 'query' ] 			= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelTable::find();
		$config[ 'columns' ]		= isset( $config[ 'columns' ] ) ? $config[ 'columns' ] : [ "$modelTable.id", "$modelTable.name" ];
		$config[ 'array' ]			= isset( $config[ 'array' ] ) ? $config[ 'array' ] : true;

		$config[ 'filters' ][]		= [ 'like', "$modelTable.name", $name ];

		if( $modelClass::$multiSite ) {

			$config[ 'conditions' ][ "$modelTable.siteId" ]	= Yii::$app->core->siteId;
		}

		return self::searchModels( $config );
	}

	public function searchByNameType( $name, $type, $config = [] ) {

		$modelTable		= self::$modelTable;

		$config[ 'conditions' ][ "$modelTable.type" ]	= $type;

		return $this->searchByName( $name, $config );
	}

    // Read - Lists ----

   	public function getIdListByType( $type, $config = [] ) {

		$config[ 'conditions' ][ 'type' ] = $type;

		return self::findIdList( $config );
	}

	public function getIdNameListByType( $type, $config = [] ) {

		$config[ 'conditions' ][ 'type' ] = $type;

		return $this->getIdNameList( $config );
	}

    // Read - Maps -----

	public function getIdNameMapByType( $type, $config = [] ) {

		$config[ 'conditions' ][ 'type' ] = $type;

		return $this->getIdNameMap( $config );
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
