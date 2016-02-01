<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Option;
use cmsgears\core\common\models\entities\Category;

/**
 * The class OptionService is base class to perform database activities for Option Entity.
 */
class OptionService extends Service {
	
	// Static Methods ----------------------------------------------

	// Create --------------

	public static function create( $model ) {

		$model->value	= $model->name;

		$model->save();

		return $model;
	}

	// Read ----------------

	/**
	 * @param integer $id
	 * @return Option
	 */
	public static function findById( $id ) {

		return Option::findById( $id );
	}

	/**
	 * @param integer $categoryId - category id
	 * @return Option
	 */
	public static function findByCategoryId( $categoryId ) {

		return Option::findByCategoryId( $categoryId );
	}

	/**
	 * @param integer $categoryName - category name
	 * @return Option
	 */
	public static function findByCategoryName( $categoryName, $categoryType = CoreGlobal::TYPE_COMBO ) {

		return Option::findByCategoryName( $categoryName, $categoryType );
	}

	/**
	 * @param string $name - option name
	 * @param integer $categoryId - category name
	 * @return Option
	 */
	public static function findByNameCategoryId( $name, $categoryId ) {

		return Option::findByNameCategoryId( $name, $categoryId );
	}

	/**
	 * @param string $name - option name
	 * @param integer $categoryName - category name
	 * @return Option
	 */
	public static function findByNameCategoryName( $name, $categoryName, $categoryType = CoreGlobal::TYPE_COMBO ) {

		return Option::findByNameCategoryName( $name, $categoryName, $categoryType );
	}

	/**
	 * @param string $value - option value
	 * @param integer $categoryName - category name
	 * @return Option
	 */
	public static function findByValueCategoryName( $value, $categoryName, $categoryType = CoreGlobal::TYPE_COMBO ) {

		return Option::findByValueCategoryName( $value, $categoryName, $categoryType );
	}

	/**
	 * @param integer $categoryId - category id
	 * @return array - an array having id as key and name as value for given category id.
	 */
	public static function getIdNameMapByCategoryId( $categoryId, $prepend = [], $append = [] ) {
		
		return self::findMap( 'id', 'name', CoreTables::TABLE_OPTION, [ 'conditions' => [ 'categoryId' => $categoryId ], 'prepend' => $prepend, 'append' => $append ] );
	}

	/**
	 * @param integer $categoryName - category name
	 * @return array - an array having id as key and name as value for given category name.
	 */
	public static function getIdNameMapByCategoryName( $categoryName, $prepend = [], $append = [], $type = CoreGlobal::TYPE_COMBO ) {

		$category	= Category::findByNameType( $categoryName, $type );

		return self::findMap( 'id', 'name', CoreTables::TABLE_OPTION, [ 'conditions' => [ 'categoryId' => $category->id ], 'prepend' => $prepend, 'append' => $append ] );
	}

	/**
	 * @param integer $categoryId - category id
	 * @return array - an array having value as key and name as value for given category id.
	 */
	public static function getValueNameMapByCategoryId( $categoryId ) {

		$category	= Category::findById( $categoryId );
		$options	= $category->options;
		$optionsMap	= array();

		foreach ( $options as $option ) {
			
			$optionsMap[ $option->value ] = $option->name;
		}
		
		return $optionsMap;
	}

	/**
	 * @param integer $categoryName - category name
	 * @return array - an array having value as key and name as value for given category name.
	 */
	public static function getValueNameMapByCategoryName( $categoryName, $type = CoreGlobal::TYPE_COMBO ) {

		$category	= Category::findByNameType( $categoryName, $type );
		$options	= $category->options;
		$optionsMap	= array();

		foreach ( $options as $option ) {

			$optionsMap[ $option->value ] = $option->name;
		}

		return $optionsMap;
	}

	public static function getValueNameMapByCategorySlug( $categorySlug, $type = CoreGlobal::TYPE_COMBO ) {

		$category	= Category::findBySlug( $categorySlug );
		$options	= $category->options;
		$optionsMap	= array();

		foreach ( $options as $option ) {

			$optionsMap[ $option->value ] = $option->name;
		}

		return $optionsMap;
	}

	// Data Provider ----

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {
		
		$sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name'
	            ],
	            'slug' => [
	                'asc' => [ 'slug' => SORT_ASC ],
	                'desc' => ['slug' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'slug'
	            ]
	        ]
	    ]);
	
		if( !isset( $config[ 'sort' ] ) ) {
	
			$config[ 'sort' ] = $sort;
		}
	
		if( !isset( $config[ 'search-col' ] ) ) {
	
			$config[ 'search-col' ] = 'name';
		}

		return self::getDataProvider( new Option(), $config );
	}
	
	// Update ----
	
	public static function update( $model ) {
		 
		$modelToUpdate	= self::findById( $model->id );

		// Copy Attributes
		$modelToUpdate->copyForUpdateFrom( $model, [ 'categoryId', 'name', 'value', 'icon', 'htmlOptions' ] );

		// Update Option
		$modelToUpdate->update();

		// Return updated option
		return $modelToUpdate;
	}
	
	// Delete ----
	
	public static function delete( $model ) {
		
		$model->delete();
			
	}
}

?>