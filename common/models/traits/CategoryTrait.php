<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Category;
use cmsgears\core\common\models\entities\ModelCategory;

/**
 * CategoryTrait can be used to categories relevant models. The model must define the member variable $categoryType which is unique for the model.
 */
trait CategoryTrait {

	/**
	 * @return array - ModelCategory associated with parent
	 */
	public function getModelCategories() {

		$parentType	= $this->categoryType;

    	return $this->hasMany( ModelCategory::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$parentType'" );
	}

	/**
	 * @return array - Category associated with parent
	 */
	public function getCategories() {

    	return $this->hasMany( Category::className(), [ 'id' => 'categoryId' ] )
					->viaTable( CoreTables::TABLE_MODEL_CATEGORY, [ 'parentId' => 'id' ], function( $query ) {

						$modelCategory	= CoreTables::TABLE_MODEL_CATEGORY;
	
                      	$query->onCondition( [ "$modelCategory.parentType" => $this->categoryType ] );
					});
	}

	/**
	 * @return array - list of category id associated with parent
	 */
	public function getCategoryIdList() {

    	$categories 		= $this->categories;
		$categoriesList		= [];

		foreach ( $categories as $category ) {

			array_push( $categoriesList, $category->id );
		}

		return $categoriesList;
	}

	public function getCategoryNameList() {

    	$categories 		= $this->categories;
		$categoriesList		= [];

		foreach ( $categories as $category ) {

			array_push( $categoriesList, $category->name );
		}

		return $categoriesList;
	}

	/**
	 * @return array - list of category id and name associated with parent
	 */
	public function getCategoryIdNameList() {

		$categories 	= $this->categories;
		$categoriesList	= [];

		foreach ( $categories as $category ) {

			$categoriesList[] = [ 'id' => $category->id, 'name' => $category->name ];
		}

		return $categoriesList;
	}

	/**
	 * @return array - map of category id and name associated with parent
	 */
	public function getCategoryIdNameMap() {

		$categories 	= $this->categories;
		$categoriesMap	= [];

		foreach ( $categories as $category ) {

			$categoriesMap[ $category->id ] = $category->name;
		}

		return $categoriesMap;
	}

	public function getCategoryCsv( $limit = 0 ) {

    	$categories 	= $this->categories;
		$categoriesCsv	= [];
		$count			= 1;

		foreach ( $categories as $category ) {

			$categoriesCsv[] = $category->name;

			if( $limit > 0 && $count >= $limit ) {

				break;
			}

			$count++;
		}

		return implode( ", ", $categoriesCsv );
	}
}

?>