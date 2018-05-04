<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\traits\base;

// Yii Imports
use Yii;

/**
 * NameTrait provide methods for models having name attribute.
 *
 * @since 1.0.0
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

	public function getByName( $name, $config = [] ) {

		return self::findByName( $name, $config );
	}

	public function searchByName( $name, $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$config[ 'query' ]		= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'columns' ]	= isset( $config[ 'columns' ] ) ? $config[ 'columns' ] : [ "$modelTable.id", "$modelTable.name" ];
		$config[ 'array' ]		= isset( $config[ 'array' ] ) ? $config[ 'array' ] : true;
		$config[ 'siteId' ]		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : ($modelClass::isMultiSite() ? Yii::$app->core->siteId : null );

		$config[ 'query' ]->andWhere( "$modelTable.name like :name", [ ':name' => "$name%" ] );

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		if( $modelClass::isMultiSite() && !$ignoreSite ) {

			$config[ 'conditions' ][ "$modelTable.siteId" ]	= $config[ 'siteId' ];
		}

		return static::searchModels( $config );
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

	public static function findByName( $name, $config = [] ) {

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
