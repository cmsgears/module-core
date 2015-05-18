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
	 * @return array - Category associated with parent
	 */
	public function getCategories() {
		
		$parentType	= $this->categoryType;

    	return $this->hasMany( Category::className(), [ 'id' => 'categoryId' ] )
					->viaTable( CoreTables::TABLE_MODEL_CATEGORY, [ 'parentId' => 'id' ] )
					->where( "parentType='$parentType'" );
	}

	/**
	 * @return array - ModelCategory associated with parent
	 */
	public function getCategoriesMap() {

		$parentType	= $this->categoryType;

    	return $this->hasMany( ModelCategory::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$parentType'" );
	}

	/**
	 * @return array - list of category id associated with parent
	 */
	public function getCategoriesIdList() {

    	$categories 		= $this->categoriesMap;
		$categoriesList		= array();

		foreach ( $categories as $category ) {

			array_push( $categoriesList, $category->categoryId );
		}

		return $categoriesList;
	}

	/**
	 * @return array - list of category id and name associated with parent
	 */
	public function getCategoriesIdNameList() {

		$categories 	= $this->categories;
		$categoriesList	= array();

		foreach ( $categories as $category ) {

			$categoriesList[] = [ 'id' => $category->id, 'name' => $category->name ];
		}

		return $categoriesList;
	}

	/**
	 * @return array - map of category id and name associated with parent
	 */
	public function getCategoriesIdNameMap() {

		$categories 	= $this->categories;
		$categoriesMap	= array();

		foreach ( $categories as $category ) {

			$categoriesMap[ $category->id ] = $category->name;
		}

		return $categoriesMap;
	}	
}

?>