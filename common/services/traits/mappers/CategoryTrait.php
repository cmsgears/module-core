<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\traits\mappers;

/**
 * Used by services with base model having [[\cmsgears\core\common\models\traits\mappers\CategoryTrait]].
 *
 * @since 1.0.0
 */
trait CategoryTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CategoryTrait -------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByCategoryId( $categoryId, $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$config[ 'page' ]	= isset( $config[ 'page' ] ) ? $config[ 'page' ] : false;
		$config[ 'query' ]	= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'limit' ]	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$config[ 'active' ]	= isset( $config[ 'active' ] ) ? $config[ 'active' ] : true;

		if( $config[ 'active' ] ) {

			$config[ 'query' ]->joinWith( 'activeCategories' )->andWhere( [ 'modelId' => $categoryId ] );
		}
		else {

			$config[ 'query' ]->joinWith( 'categories' )->andWhere( [ 'modelId' => $categoryId ] );
		}

		if( $config[ 'page' ] ) {

			$page	= $this->getPublicPage( $config );

			return $page->getModels();
		}

		return $config[ 'query' ]->limit( $config[ 'limit' ] )->all();
	}

	public function getByCategoryIds( $ids, $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$config[ 'page' ]	= isset( $config[ 'page' ] ) ? $config[ 'page' ] : false;
		$config[ 'query' ]	= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'limit' ]	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$config[ 'active' ]	= isset( $config[ 'active' ] ) ? $config[ 'active' ] : true;

		if( $config[ 'active' ] ) {

			$config[ 'query' ]->joinWith( 'activeCategories' )->andFilterWhere( [ 'in', 'modelId', $ids ] );
		}
		else {

			$config[ 'query' ]->joinWith( 'categories' )->andFilterWhere( [ 'in', 'modelId', $ids ] );
		}

		if( $config[ 'page' ] ) {

			$page	= $this->getPublicPage( $config );

			return $page->getModels();
		}

		return $config[ 'query' ]->limit( $config[ 'limit' ] )->all();
	}

	// Works only with models having pinned column
	public function getPinnedByCategoryId( $categoryId, $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$config[ 'page' ]	= isset( $config[ 'page' ] ) ? $config[ 'page' ] : false;
		$config[ 'query' ]	= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'limit' ]	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$config[ 'active' ]	= isset( $config[ 'active' ] ) ? $config[ 'active' ] : true;

		if( $config[ 'active' ] ) {

			$config[ 'query' ]->joinWith( 'activeCategories' )->andWhere( [ 'modelId' => $categoryId, "$modelTable.pinned" => true ] );
		}
		else {

			$config[ 'query' ]->joinWith( 'categories' )->andWhere( [ 'modelId' => $categoryId, "$modelTable.pinned" => true ] );
		}

		if( $config[ 'page' ] ) {

			$page	= $this->getPublicPage( $config );

			return $page->getModels();
		}

		return $config[ 'query' ]->limit( $config[ 'limit' ] )->all();
	}

	// Works only with models having featured column
	public function getFeaturedByCategoryId( $categoryId, $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$config[ 'page' ]	= isset( $config[ 'page' ] ) ? $config[ 'page' ] : false;
		$config[ 'query' ]	= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'limit' ]	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$config[ 'active' ]	= isset( $config[ 'active' ] ) ? $config[ 'active' ] : true;

		if( $config[ 'active' ] ) {

			$config[ 'query' ]->joinWith( 'activeCategories' )->andWhere( [ 'modelId' => $categoryId, "$modelTable.featured" => true ] );
		}
		else {

			$config[ 'query' ]->joinWith( 'categories' )->andWhere( [ 'modelId' => $categoryId, "$modelTable.featured" => true ] );
		}

		if( $config[ 'page' ] ) {

			$page	= $this->getPublicPage( $config );

			return $page->getModels();
		}

		return $config[ 'query' ]->limit( $config[ 'limit' ] )->all();
	}

	public function getByCategoryNodeId( $categoryId, $config = [] ) {

		$modelClass	= static::$modelClass;

		$config[ 'page' ]	= isset( $config[ 'page' ] ) ? $config[ 'page' ] : false;
		$config[ 'query' ]	= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'limit' ]	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$config[ 'active' ]	= isset( $config[ 'active' ] ) ? $config[ 'active' ] : true;

		if( $config[ 'active' ] ) {

			$config[ 'query' ]->joinWith( 'activeModelCategories' )->andWhere( "MATCH( nodes ) AGAINST('$categoryId' IN NATURAL LANGUAGE MODE)" );
		}
		else {

			$config[ 'query' ]->joinWith( 'modelCategories' )->andWhere( ['like', 'nodes', $categoryId ] );
		}

		if( $config[ 'page' ] ) {

			$page = $this->getPublicPage( $config );

			return $page->getModels();
		}

		return $config[ 'query' ]->limit( $config[ 'limit' ] )->all();
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

	// CategoryTrait -------------------------

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
