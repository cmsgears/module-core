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

		$parentType	= static::$parentType;

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$modelCategoryTable = ModelCategory::tableName();

		$config[ 'page' ]	= isset( $config[ 'page' ] ) ? $config[ 'page' ] : false;
		$config[ 'query' ]	= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'limit' ]	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$config[ 'active' ]	= isset( $config[ 'active' ] ) ? $config[ 'active' ] : true;

		if( $config[ 'active' ] ) {

			$config[ 'query' ]
				->leftJoin( $modelCategoryTable, "$modelCategoryTable.parentId=$modelTable.id AND $modelCategoryTable.parentType=$parentType" )
				->where( "$modelCategoryTable.model=:cid AND $modelCategoryTable.active=1", [ ':cid' => $categoryId ] );
		}
		else {

			$config[ 'query' ]
				->leftJoin( $modelCategoryTable, "$modelCategoryTable.parentId=$modelTable.id AND $modelCategoryTable.parentType=$parentType" )
				->where( "$modelCategoryTable.model=:cid", [ ':cid' => $categoryId ] );
		}

		if( $config[ 'page' ] ) {

			$page = $this->getPublicPage( $config );

			return $page->getModels();
		}

		return $config[ 'query' ]->limit( $config[ 'limit' ] )->all();
	}

	public function getByCategoryIds( $ids, $config = [] ) {

		$parentType	= static::$parentType;

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$modelCategoryTable = ModelCategory::tableName();

		$config[ 'page' ]	= isset( $config[ 'page' ] ) ? $config[ 'page' ] : false;
		$config[ 'query' ]	= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'limit' ]	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$config[ 'active' ]	= isset( $config[ 'active' ] ) ? $config[ 'active' ] : true;

		if( $config[ 'active' ] ) {

			$config[ 'query' ]
				->leftJoin( $modelCategoryTable, "$modelCategoryTable.parentId=$modelTable.id AND $modelCategoryTable.parentType=$parentType" )
				->where( "$modelCategoryTable.active=1" )
				->andFilterWhere( [ 'in', "$modelCategoryTable.modelId", $ids ] );
		}
		else {

			$config[ 'query' ]
				->leftJoin( $modelCategoryTable, "$modelCategoryTable.parentId=$modelTable.id AND $modelCategoryTable.parentType=$parentType" )
				->filterWhere( [ 'in', "$modelCategoryTable.modelId", $ids ] );
		}

		if( $config[ 'page' ] ) {

			$page = $this->getPublicPage( $config );

			return $page->getModels();
		}

		return $config[ 'query' ]->limit( $config[ 'limit' ] )->all();
	}

	// Works only with models having pinned column
	public function getPinnedByCategoryId( $categoryId, $config = [] ) {

		$parentType	= static::$parentType;

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$modelCategoryTable = ModelCategory::tableName();

		$config[ 'page' ]	= isset( $config[ 'page' ] ) ? $config[ 'page' ] : false;
		$config[ 'query' ]	= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'limit' ]	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$config[ 'active' ]	= isset( $config[ 'active' ] ) ? $config[ 'active' ] : true;

		if( $config[ 'active' ] ) {

			$config[ 'query' ]
				->leftJoin( $modelCategoryTable, "$modelCategoryTable.parentId=$modelTable.id AND $modelCategoryTable.parentType=$parentType" )
				->where( "$modelTable.pinned=1 AND $modelCategoryTable.model=:cid AND $modelCategoryTable.active=1", [ ':cid' => $categoryId ] );
		}
		else {

			$config[ 'query' ]
				->leftJoin( $modelCategoryTable, "$modelCategoryTable.parentId=$modelTable.id AND $modelCategoryTable.parentType=$parentType" )
				->where( "$modelTable.pinned=1 AND $modelCategoryTable.model=:cid", [ ':cid' => $categoryId ] );
		}

		if( $config[ 'page' ] ) {

			$page = $this->getPublicPage( $config );

			return $page->getModels();
		}

		return $config[ 'query' ]->limit( $config[ 'limit' ] )->all();
	}

	// Works only with models having featured column
	public function getFeaturedByCategoryId( $categoryId, $config = [] ) {

		$parentType	= static::$parentType;

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$modelCategoryTable = ModelCategory::tableName();

		$config[ 'page' ]	= isset( $config[ 'page' ] ) ? $config[ 'page' ] : false;
		$config[ 'query' ]	= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'limit' ]	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$config[ 'active' ]	= isset( $config[ 'active' ] ) ? $config[ 'active' ] : true;

		if( $config[ 'active' ] ) {

			$config[ 'query' ]
				->leftJoin( $modelCategoryTable, "$modelCategoryTable.parentId=$modelTable.id AND $modelCategoryTable.parentType=$parentType" )
				->where( "$modelTable.featured=1 AND $modelCategoryTable.model=:cid AND $modelCategoryTable.active=1", [ ':cid' => $categoryId ] );
		}
		else {

			$config[ 'query' ]
				->leftJoin( $modelCategoryTable, "$modelCategoryTable.parentId=$modelTable.id AND $modelCategoryTable.parentType=$parentType" )
				->where( "$modelTable.featured=1 AND $modelCategoryTable.model=:cid", [ ':cid' => $categoryId ] );
		}

		if( $config[ 'page' ] ) {

			$page = $this->getPublicPage( $config );

			return $page->getModels();
		}

		return $config[ 'query' ]->limit( $config[ 'limit' ] )->all();
	}

	// Works only with models having popular column
	public function getPopularByCategoryId( $categoryId, $config = [] ) {

		$parentType	= static::$parentType;

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$modelCategoryTable = ModelCategory::tableName();

		$config[ 'page' ]	= isset( $config[ 'page' ] ) ? $config[ 'page' ] : false;
		$config[ 'query' ]	= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'limit' ]	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$config[ 'active' ]	= isset( $config[ 'active' ] ) ? $config[ 'active' ] : true;

		if( $config[ 'active' ] ) {

			$config[ 'query' ]
				->leftJoin( $modelCategoryTable, "$modelCategoryTable.parentId=$modelTable.id AND $modelCategoryTable.parentType=$parentType" )
				->where( "$modelTable.popular=1 AND $modelCategoryTable.model=:cid AND $modelCategoryTable.active=1", [ ':cid' => $categoryId ] );
		}
		else {

			$config[ 'query' ]
				->leftJoin( $modelCategoryTable, "$modelCategoryTable.parentId=$modelTable.id AND $modelCategoryTable.parentType=$parentType" )
				->where( "$modelTable.popular=1 AND $modelCategoryTable.model=:cid", [ ':cid' => $categoryId ] );
		}

		if( $config[ 'page' ] ) {

			$page = $this->getPublicPage( $config );

			return $page->getModels();
		}

		return $config[ 'query' ]->limit( $config[ 'limit' ] )->all();
	}

	public function getByCategoryNodeId( $categoryId, $config = [] ) {

		$parentType	= static::$parentType;

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$modelCategoryTable = ModelCategory::tableName();

		$config[ 'page' ]	= isset( $config[ 'page' ] ) ? $config[ 'page' ] : false;
		$config[ 'query' ]	= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'limit' ]	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$config[ 'active' ]	= isset( $config[ 'active' ] ) ? $config[ 'active' ] : true;

		if( $config[ 'active' ] ) {

			$config[ 'query' ]
				->leftJoin( $modelCategoryTable, "$modelCategoryTable.parentId=$modelTable.id AND $modelCategoryTable.parentType=$parentType" )
				->where( "MATCH( nodes ) AGAINST(:cid IN NATURAL LANGUAGE MODE) AND $modelCategoryTable.active=1", [ ':cid' => $categoryId ] );
		}
		else {

			$config[ 'query' ]
				->leftJoin( $modelCategoryTable, "$modelCategoryTable.parentId=$modelTable.id AND $modelCategoryTable.parentType=$parentType" )
				->where( [ 'like', 'nodes', $categoryId ] );
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
