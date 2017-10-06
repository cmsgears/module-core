<?php
namespace cmsgears\core\common\services\traits;

// Yii Imports
use \Yii;

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

		return self::findByName( $name );
	}

	public function searchByName( $name, $config = [] ) {

		$modelClass					= static::$modelClass;
		$modelTable					= static::$modelTable;

		$config[ 'query' ]			= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'columns' ]		= isset( $config[ 'columns' ] ) ? $config[ 'columns' ] : [ "$modelTable.id", "$modelTable.name" ];
		$config[ 'array' ]			= isset( $config[ 'array' ] ) ? $config[ 'array' ] : true;

		$config[ 'query' ]->andWhere( "$modelTable.name like '$name%'" );

		if( $modelClass::$multiSite ) {

			$config[ 'conditions' ][ "$modelTable.siteId" ]	= Yii::$app->core->siteId;
		}

		return static::searchModels( $config );
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
