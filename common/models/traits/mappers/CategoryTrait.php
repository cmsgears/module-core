<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\mappers;

// CMG Imports
use cmsgears\core\common\models\resources\Category;
use cmsgears\core\common\models\mappers\ModelCategory;

/**
 * It provide methods specific to managing category mappings of respective models.
 */
trait CategoryTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// CategoryTrait -------------------------

	/**
	 * @inheritdoc
	 */
	public function getModelCategories() {

		$modelCategoryTable = ModelCategory::tableName();

		return $this->hasMany( ModelCategory::class, [ 'parentId' => 'id' ] )
			->where( "$modelCategoryTable.parentType='$this->modelType'" );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveModelCategories() {

		$modelCategoryTable = ModelCategory::tableName();

		return $this->hasMany( ModelCategory::class, [ 'parentId' => 'id' ] )
			->where( "$modelCategoryTable.parentType='$this->modelType' AND $modelCategoryTable.active=1" );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelCategoriesByType( $type, $active = true ) {

		$modelCategoryTable = ModelCategory::tableName();

		return $this->hasOne( ModelCategory::class, [ 'parentId' => 'id' ] )
			->where( "$modelCategoryTable.parentType=:ptype AND $modelCategoryTable.type=:type AND $modelCategoryTable.active=:active", [ ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getCategories() {

		$modelCategoryTable = ModelCategory::tableName();

		return $this->hasMany( Category::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelCategoryTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelCategoryTable ) {

					$query->onCondition( [ "$modelCategoryTable.parentType" => $this->modelType ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveCategories() {

		$modelCategoryTable = ModelCategory::tableName();

		return $this->hasMany( Category::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelCategoryTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelCategoryTable ) {

					$query->onCondition( [ "$modelCategoryTable.parentType" => $this->modelType, "$modelCategoryTable.active" => true ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getCategoriesByType( $type, $active = true ) {

		$modelCategoryTable = ModelCategory::tableName();

		$categories = $this->hasMany( Category::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelCategoryTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$type, &$active, &$modelCategoryTable ) {

					$query->onCondition( [ "$modelCategoryTable.parentType" => $this->modelType, "$modelCategoryTable.type" => $type, "$modelCategoryTable.active" => $active ] );
				}
			)->all();

		return $categories;
	}

	/**
	 * @inheritdoc
	 */
	public function getCategoryIdList( $active = true ) {

		$categories		= $active ? $this->activeCategories : $this->categories;
		$categoriesList	= [];

		foreach( $categories as $category ) {

			array_push( $categoriesList, $category->id );
		}

		return $categoriesList;
	}

	/**
	 * @inheritdoc
	 */
	public function getCategoryIdListByType( $type, $active = true ) {

		$categories			= $this->getCategoriesByType( $type, $active );
		$categoriesList		= [];

		foreach( $categories as $category ) {

			array_push( $categoriesList, $category->id );
		}

		return $categoriesList;
	}

	/**
	 * @inheritdoc
	 */
	public function getCategoryNameList( $active = true, $l1 = false ) {

		$categories		= $active ? $this->activeCategories : $this->categories;
		$categoriesList	= [];

		foreach( $categories as $category ) {

			if( $l1 ) {

				if( $category->lValue == 1 ) {

					array_push( $categoriesList, $category->name );
				}
			}
			else {

				array_push( $categoriesList, $category->name );
			}
		}

		return $categoriesList;
	}

	/**
	 * @inheritdoc
	 */
	public function getCategoryNameListByType( $type, $active = true, $l1 = false ) {

		$categories		= $this->getCategoriesByType( $type, $active );
		$categoriesList	= [];

		foreach( $categories as $category ) {

			if( $l1 ) {

				if( $category->lValue == 1 ) {

					array_push( $categoriesList, $category->name );
				}
			}
			else {

				array_push( $categoriesList, $category->name );
			}
		}

		return $categoriesList;
	}

	/**
	 * @inheritdoc
	 */
	public function getCategoryIdNameList( $active = true ) {

		$categories		= $active ? $this->activeCategories : $this->categories;
		$categoriesList	= [];

		foreach( $categories as $category ) {

			$categoriesList[] = [ 'id' => $category->id, 'name' => $category->name ];
		}

		return $categoriesList;
	}

	/**
	 * @inheritdoc
	 */
	public function getCategoryIdNameMap( $active = true ) {

		$categories		= $active ? $this->activeCategories : $this->categories;
		$categoriesMap	= [];

		foreach( $categories as $category ) {

			$categoriesMap[ $category->id ] = $category->name;
		}

		return $categoriesMap;
	}

	/**
	 * @inheritdoc
	 */
	public function getCategorySlugNameMap( $active = true ) {

		$categories		= $active ? $this->activeCategories : $this->categories;
		$categoriesMap	= [];

		foreach( $categories as $category ) {

			$categoriesMap[ $category->slug ] = $category->name;
		}

		return $categoriesMap;
	}

	/**
	 * @inheritdoc
	 */
	public function getCategoryCsv( $limit = 0, $active = true, $l1 = false ) {

		$categories		= $active ? $this->activeCategories : $this->categories;
		$categoriesCsv	= [];

		foreach ( $categories as $category ) {

			if( $l1 ) {

				if( $category->lValue == 1 ) {

					$categoriesCsv[] = $category->name;
				}
			}
			else {

				$categoriesCsv[] = $category->name;
			}
		}

		if( $limit > 0 ) {

			$categoriesCsv = array_splice( $categoriesCsv, $limit );
		}

		return implode( ", ", $categoriesCsv );
	}

	/**
	 * @inheritdoc
	 */
	public function getCategoryLinks( $baseUrl, $config = [] ) {

		$wrapper	= isset( $config[ 'wrapper' ] ) ? $config[ 'wrapper' ] : true;
		$wrapperTag	= isset( $config[ 'wrapperTag' ] ) ? $config[ 'wrapperTag' ] : 'li';
		$limit		= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 0;
		$active		= isset( $config[ 'active' ] ) ? $config[ 'active' ] : true;
		$l1			= isset( $config[ 'l1' ] ) ? $config[ 'l1' ] : false;
		$csv		= isset( $config[ 'csv' ] ) ? $config[ 'csv' ] : false;

		$categories		= $active ? $this->activeCategories : $this->categories;
		$categoryLinks	= [];

		foreach( $categories as $category ) {

			$link = null;

			if( $l1 ) {

				if( $category->lValue == 1 ) {

					$link = "<a href='$baseUrl/$category->slug'>$category->name</a>";
				}
			}
			else {

				$link = "<a href='$baseUrl/$category->slug'>$category->name</a>";
			}

			if( $wrapper ) {

				$categoryLinks[] = "<$wrapperTag>$link</$wrapperTag>";
			}
			else {

				$categoryLinks[] = $link;
			}
		}

		if( $limit > 0 ) {

			$categoryLinks = array_splice( $categoryLinks, $limit );
		}

		if( $csv ) {

			$categoryLinks = join( ', ', $categoryLinks );
		}
		else {

			$categoryLinks = join( '', $categoryLinks );
		}

		return $categoryLinks;
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// CategoryTrait -------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
