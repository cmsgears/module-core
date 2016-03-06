<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Category;
use cmsgears\core\common\models\entities\ModelCategory;

/**
 * CategoryTrait can be used to categories relevant models. The model must define the member variable $categoryType which is unique for the model.
 */
trait CategoryTrait {

	private $categoryLimit 	= 0;

	/**
	 * @return array - ModelCategory associated with parent
	 */
	public function getModelCategories() {

    	return $this->hasMany( ModelCategory::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$this->parentType'" );
	}

	/**
	 * @return array - Category associated with parent
	 */
	public function getCategories() {

		$category	= CoreTables::TABLE_CATEGORY;

    	$query = $this->hasMany( Category::className(), [ 'id' => 'categoryId' ] )
					->viaTable( CoreTables::TABLE_MODEL_CATEGORY, [ 'parentId' => 'id' ], function( $query ) {

						$modelCategory	= CoreTables::TABLE_MODEL_CATEGORY;

                      	$query->onCondition( [ "$modelCategory.parentType" => $this->parentType ] );
					})
					->where( "$category.type='$this->categoryType'" );

		if( $this->categoryLimit > 0 ) {

			$query = $query->limit( $this->categoryLimit );
		}

		return $query;
	}

	public function getActiveCategories() {

		$category	= CoreTables::TABLE_CATEGORY;

    	$query = $this->hasMany( Category::className(), [ 'id' => 'categoryId' ] )
					->viaTable( CoreTables::TABLE_MODEL_CATEGORY, [ 'parentId' => 'id' ], function( $query ) {

						$modelCategory	= CoreTables::TABLE_MODEL_CATEGORY;

                      	$query->onCondition( [ "$modelCategory.parentType" => $this->parentType, "$modelCategory.active" => true ] );
					})
					->where( "$category.type='$this->categoryType'" );

		if( $this->categoryLimit > 0 ) {

			$query = $query->limit( $this->categoryLimit );
		}

		return $query;
	}

	public function getCategoriesByType( $type ) {

		$category	= CoreTables::TABLE_CATEGORY;

		$categories = $this->hasMany( Category::className(), [ 'id' => 'categoryId' ] )
							->viaTable( CoreTables::TABLE_MODEL_CATEGORY, [ 'parentId' => 'id' ], function( $query ) {

								$modelCategory	= CoreTables::TABLE_MODEL_CATEGORY;

                      			$query->onCondition( [ "$modelCategory.parentType" => $this->parentType, "$modelCategory.active" => true ] );
							})
							->where( "$category.type='$type'" )
							->all();

		return $categories;
	}

	/**
	 * @return array - list of category id associated with parent
	 */
	public function getCategoryIdList( $active = false ) {

    	$categories 		= [];
		$categoriesList		= [];

		if( isset( $type ) ) {

			$this->categoryType	= $type;
		}

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

	public function getCategoryIdListByType( $type ) {

		$categories 		= $this->getCategoriesByType( $type );
		$categoriesList		= [];

		foreach ( $categories as $category ) {

			array_push( $categoriesList, $category->id );
		}

		return $categoriesList;
	}

	public function getCategoryNameList( $active = false ) {

    	$categories 		= [];
		$categoriesList		= [];

		if( $active ) {

			$categories = $this->activeCategories;
		}
		else {

			$categories = $this->categories;
		}

		foreach ( $categories as $category ) {

			array_push( $categoriesList, $category->name );
		}

		return $categoriesList;
	}

	public function getCategoryNameListByType( $type ) {

		$categories 		= $this->getCategoriesByType( $type );
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
		
		$this->categoryLimit	= $limit;
    	$categories 			= $this->categories;
		$categoriesCsv			= [];

		foreach ( $categories as $category ) {

			$categoriesCsv[] = $category->name;
		}

		return implode( ", ", $categoriesCsv );
	} 
	
	public function getCategoryLinks( $baseUrl, $limit = 0 ) {
		
		$categories 	= $this->categories;
		$categoryLinks	= null;
		$count			= 1;

		foreach ( $categories as $category ) {
			 
			$categoryLinks	.= "<li><a href='$baseUrl?slug=$category->slug'>$category->name</a></li>";

			if( $limit > 0 && $count >= $limit ) {

				break;
			}

			$count++;
		}

		return $categoryLinks;
	}
}

?>