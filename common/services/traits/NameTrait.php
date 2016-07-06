<?php
namespace cmsgears\core\common\services\traits;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Used by services with base model having unique name.
 */
trait NameTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SlugTrait -----------------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getByName( $name ) {

		return self::findByName( $name )->one();
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

	public static function findByName( $name ) {

		$modelClass	= static::$modelClass;

		return $modelClass::queryByName( $name )->one();
    }

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
