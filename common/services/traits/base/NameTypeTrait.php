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
 * NameTypeTrait provide methods for models having name, slug and type columns with sluggable
 * behavior which allows unique name for a type.
 *
 * There might be relaxation in having unique name for a type in several models. These models
 * will be uniquely identified by their slug.
 *
 * @since 1.0.0
 */
trait NameTypeTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// NameTypeTrait -------------------------

	// Data Provider ------

	public function getPageByType( $type, $config = [] ) {

		$modelTable	= static::$modelTable;

		$config[ 'conditions' ][ "$modelTable.type" ] = $type;

		return $this->getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getByName( $name ) {

		$modelClass = static::$modelClass;

		return $modelClass::findByName( $name );
	}

	/**
	 * Useful for models having unique name irrespective of their type.
	 */
	public function getFirstByName( $name ) {

		$modelClass = static::$modelClass;

		return $modelClass::FindFirstByName( $name );
	}

	public function getByType( $type ) {

		$modelClass = static::$modelClass;

		return $modelClass::findByType( $type );
	}

	/**
	 * Useful for models having only one row for a type.
	 */
	public function getFirstByType( $type ) {

		$modelClass = static::$modelClass;

		return $modelClass::findFirstByType( $type );
	}

	public function getByNameType( $name, $type ) {

		$modelClass = static::$modelClass;

		return $modelClass::findByNameType( $name, $type );
	}

	/**
	 * Useful for models having unique name for type.
	 */
	public function getFirstByNameType( $name, $type ) {

		$modelClass = static::$modelClass;

		return $modelClass::findFirstByNameType( $name, $type );
	}

	public function searchByName( $name, $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= static::$modelTable;

		$config[ 'query' ]		= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'columns' ]	= isset( $config[ 'columns' ] ) ? $config[ 'columns' ] : [ "$modelTable.id", "$modelTable.name" ];
		$config[ 'array' ]		= isset( $config[ 'array' ] ) ? $config[ 'array' ] : true;
		$config[ 'siteId' ]		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : ($modelClass::$multiSite ? Yii::$app->core->siteId : null );

		$config[ 'query' ]->andWhere( "$modelTable.name like '$name%'" );

		if( $modelClass::$multiSite ) {

			$config[ 'conditions' ][ "$modelTable.siteId" ]	= $config[ 'siteId' ];
		}

		return static::searchModels( $config );
	}

	public function searchByNameType( $name, $type, $config = [] ) {

		$modelTable		= static::$modelTable;

		$config[ 'conditions' ][ "$modelTable.type" ] = $type;

		return $this->searchByName( $name, $config );
	}

	// Read - Lists ----

	public function getIdListByType( $type, $config = [] ) {

		$config[ 'conditions' ][ 'type' ] = $type;

		return static::findIdList( $config );
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

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// NameTypeTrait -------------------------

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
