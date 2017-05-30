<?php
namespace cmsgears\core\common\services\traits;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\Meta;

/**
 * Used by services with base model having ModelCategory trait.
 */
trait CategoryTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// DataTrait -----------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByCategoryId( $categoryId, $config = [] ) {

		$modelClass		= static::$modelClass;
		$modelTable		= static::$modelTable;

		$config[ 'page' ]	= isset( $config[ 'page' ] ) ? $config[ 'page' ] : false;
		$config[ 'query' ]	= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'limit' ]	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$config[ 'active' ]	= isset( $config[ 'active' ] ) ? $config[ 'active' ] : true;

		if( $config[ 'active' ] ) {

			$config[ 'query' ]->joinWith( 'activeCategories' )->where( [ 'modelId' => $categoryId ] );
		}
		else {

			$config[ 'query' ]->joinWith( 'categories' )->where( [ 'modelId' => $categoryId ] );
		}

		if( $config[ 'page' ] ) {

			$page	= $this->getPublicPage( $config );

			return $page->getModels();
		}

		return $config[ 'query' ]->all();
	}

	public function getByCategoryIds( $ids, $config = [] ) {

		$modelClass		= static::$modelClass;
		$modelTable		= static::$modelTable;

		$config[ 'page' ]	= isset( $config[ 'page' ] ) ? $config[ 'page' ] : false;
		$config[ 'query' ]	= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'limit' ]	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$config[ 'active' ]	= isset( $config[ 'active' ] ) ? $config[ 'active' ] : true;

		if( $config[ 'active' ] ) {

			$config[ 'query' ]->joinWith( 'activeCategories' )->filterWhere( [ 'in', 'modelId', $ids ] );
		}
		else {

			$config[ 'query' ]->joinWith( 'categories' )->filterWhere( [ 'in', 'modelId', $ids ] );
		}

		if( $config[ 'page' ] ) {

			$page	= $this->getPublicPage( $config );

			return $page->getModels();
		}

		return $config[ 'query' ]->all();
	}

	// Works only with models having featured column
	public function getFeaturedByCategoryId( $categoryId, $config = [] ) {

		$modelClass		= static::$modelClass;
		$modelTable		= static::$modelTable;

		$config[ 'page' ]	= isset( $config[ 'page' ] ) ? $config[ 'page' ] : false;
		$config[ 'query' ]	= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'limit' ]	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$config[ 'active' ]	= isset( $config[ 'active' ] ) ? $config[ 'active' ] : true;

		if( $config[ 'active' ] ) {

			$config[ 'query' ]->joinWith( 'activeCategories' )->where( [ 'modelId' => $categoryId, "$modelTable.featured" => true ] );
		}
		else {

			$config[ 'query' ]->joinWith( 'categories' )->where( [ 'modelId' => $categoryId, "$modelTable.featured" => true ] );
		}

		if( $config[ 'page' ] ) {

			$page	= $this->getPublicPage( $config );

			return $page->getModels();
		}

		return $config[ 'query' ]->all();
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// DataTrait -----------------------------

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
