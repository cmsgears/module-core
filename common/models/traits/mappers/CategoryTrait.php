<?php
namespace cmsgears\core\common\models\traits\mappers;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Category;
use cmsgears\core\common\models\mappers\ModelCategory;

/**
 * CategoryTrait can be used to categories relevant models. The model must define the member variable $categoryType which is unique for the model.
 */
trait CategoryTrait {

	/**
	 * @return array - ModelCategory associated with parent
	 */
	public function getModelCategories() {

		return $this->hasMany( ModelCategory::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$this->modelType'" );
	}

	public function getActiveModelCategories() {

		$modelCategoryTable	= CoreTables::TABLE_MODEL_CATEGORY;

		return $this->hasMany( ModelCategory::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$this->modelType' AND $modelCategoryTable.active=1" );
	}

	/**
	 * @return array - Category associated with parent
	 */
	public function getCategories() {

		$categoryTable = CoreTables::TABLE_CATEGORY;

		$query = $this->hasMany( Category::className(), [ 'id' => 'modelId' ] )
					->viaTable( CoreTables::TABLE_MODEL_CATEGORY, [ 'parentId' => 'id' ], function( $query ) {

						$modelCategoryTable	= CoreTables::TABLE_MODEL_CATEGORY;

						$query->onCondition( [ "$modelCategoryTable.parentType" => $this->modelType ] );
					})
					->where( "$categoryTable.type='$this->categoryType'" );

		return $query;
	}

	public function getActiveCategories() {

		$categoryTable = CoreTables::TABLE_CATEGORY;

		$query = $this->hasMany( Category::className(), [ 'id' => 'modelId' ] )
					->viaTable( CoreTables::TABLE_MODEL_CATEGORY, [ 'parentId' => 'id' ], function( $query ) {

						$modelCategoryTable	= CoreTables::TABLE_MODEL_CATEGORY;

						$query->onCondition( [ "$modelCategoryTable.parentType" => $this->modelType, "$modelCategoryTable.active" => true ] );
					})
					->where( "$categoryTable.type='$this->categoryType'" );

		return $query;
	}

	public function getCategoriesByType( $type, $active = true ) {

		$categoryTable = CoreTables::TABLE_CATEGORY;

		$categories = $this->hasMany( Category::className(), [ 'id' => 'modelId' ] )
							->viaTable( CoreTables::TABLE_MODEL_CATEGORY, [ 'parentId' => 'id' ], function( $query ) use( &$active ) {

								$modelCategory	= CoreTables::TABLE_MODEL_CATEGORY;

								$query->onCondition( [ "$modelCategory.parentType" => $this->modelType, "$modelCategory.active" => $active ] );
							})
							->where( "$categoryTable.type='$type'" )
							->all();

		return $categories;
	}

	/**
	 * @return array - list of category id associated with parent
	 */
	public function getCategoryIdList( $active = true ) {

		$categories		= null;
		$categoriesList	= [];

		if( $active ) {

			$categories = $this->activeCategories;
		}
		else {

			$categories = $this->categories;
		}

		foreach ( $categories as $category ) {

			array_push( $categoriesList, $category->id );
		}

		return $categoriesList;
	}

	public function getCategoryIdListByType( $type, $active = true ) {

		$categories			= $this->getCategoriesByType( $type, $active );
		$categoriesList		= [];

		foreach ( $categories as $category ) {

			array_push( $categoriesList, $category->id );
		}

		return $categoriesList;
	}

	public function getCategoryNameList( $active = true, $l1 = false ) {

		$categories			= null;
		$categoriesList		= [];

		if( $active ) {

			$categories = $this->activeCategories;
		}
		else {

			$categories = $this->categories;
		}

		foreach ( $categories as $category ) {

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

	public function getCategoryNameListByType( $type, $active = true ) {

		$categories			= $this->getCategoriesByType( $type, $active );
		$categoriesList		= [];

		foreach ( $categories as $category ) {

			array_push( $categoriesList, $category->name );
		}

		return $categoriesList;
	}

	/**
	 * @return array - list of category id and name associated with parent
	 */
	public function getCategoryIdNameList( $active = true ) {

		$categories			= null;
		$categoriesList		= [];

		if( $active ) {

			$categories = $this->activeCategories;
		}
		else {

			$categories = $this->categories;
		}

		foreach ( $categories as $category ) {

			$categoriesList[] = [ 'id' => $category->id, 'name' => $category->name ];
		}

		return $categoriesList;
	}

	/**
	 * @return array - map of category id and name associated with parent
	 */
	public function getCategoryIdNameMap( $active = true ) {

		$categories		= null;
		$categoriesMap	= [];

		if( $active ) {

			$categories		= $this->activeCategories;
		}
		else {

			$categories		= $this->categories;
		}

		foreach ( $categories as $category ) {

			$categoriesMap[ $category->id ] = $category->name;
		}

		return $categoriesMap;
	}

	public function getCategorySlugNameMap( $active = true ) {

		$categories		= null;
		$categoriesMap	= [];

		if( $active ) {

			$categories		= $this->activeCategories;
		}
		else {

			$categories		= $this->categories;
		}

		foreach ( $categories as $category ) {

			$categoriesMap[ $category->slug ] = $category->name;
		}

		return $categoriesMap;
	}

	public function getCategoryCsv( $limit = 0, $active = true, $l1 = false ) {

		$categories		= null;
		$categoriesCsv	= [];

		if( $active ) {

			$categories	= $this->activeCategories;
		}
		else {

			$categories	= $this->categories;
		}

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

		return implode( ", ", $categoriesCsv );
	}

	public function getCategoryLinks( $baseUrl, $limit = 0, $wrapper = 'li', $active = true ) {

		$categories		= null;
		$categoryLinks	= null;
		$count			= 1;

		if( $active ) {

			$categories	= $this->activeCategories;
		}
		else {

			$categories	= $this->categories;
		}

		foreach ( $categories as $category ) {

			if( isset( $wrapper ) ) {

				$categoryLinks	.= "<$wrapper><a href='$baseUrl/$category->slug'>$category->name</a></$wrapper>";
			}
			else {

				$categoryLinks	.= "<a href='$baseUrl/$category->slug'>$category->name</a>";
			}

			if( $limit > 0 && $count >= $limit ) {

				break;
			}

			$count++;
		}

		return $categoryLinks;
	}
}
