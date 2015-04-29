<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Category;
use cmsgears\core\common\models\entities\ModelCategory;

trait CategoryTrait {

	public function getCategories() {
		
		$parentType	= $this->categoryType;

    	return $this->hasMany( Category::className(), [ 'id' => 'categoryId' ] )
					->viaTable( CoreTables::TABLE_MODEL_CATEGORY, [ 'parentId' => 'id' ] )
					->where( "parentType=$parentType" );
	}

	public function getCategoriesMap() {

		$parentType	= $this->categoryType;

    	return $this->hasMany( ModelCategory::className(), [ 'parentId' => 'id' ] )
					->where( "parentType=$parentType" );
	}

	public function getCategoriesIdList() {

    	$categories 		= $this->categoriesMap;
		$categoriesList		= array();

		foreach ( $categories as $category ) {

			array_push( $categoriesList, $category->categoryId );
		}

		return $categoriesList;
	}

	public function getCategoriesIdNameList() {

		$categories 	= $this->categories;
		$categoriesList	= array();

		foreach ( $categories as $category ) {

			$categoriesList[] = [ 'id' => $category->id, 'name' => $category->name ];
		}

		return $categoriesList;
	}

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