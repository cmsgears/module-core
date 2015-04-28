<?php
namespace cmsgears\core\common\models\entities;

trait CategoryTrait {

	public function getCategories() {
		
		$parentType	= $this->parentType;

    	return $this->hasMany( Category::className(), [ 'id' => 'categoryId' ] )
					->viaTable( CoreTables::TABLE_MODEL_CATEGORY, [ 'parentId' => 'id' ] )
					->where( "parentType=$parentType" );
	}

	public function getCategoriesMap() {

		$parentType	= $this->metaType;

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