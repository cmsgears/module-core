<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Option;
use cmsgears\core\common\models\entities\Category;

/**
 * The class OptionService is base class to perform database activities for Option Entity.
 */
class OptionService extends Service {
	
	// Static Methods ----------------------------------------------

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
	public static function findByCategoryName( $categoryName ) {

		return Option::findByCategoryName( $categoryName );
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
	public static function findByNameCategoryName( $name, $categoryName ) {

		return Option::findByNameCategoryName( $name, $categoryName );
	}

	/**
	 * @param string $value - option value
	 * @param integer $categoryName - category name
	 * @return Option
	 */
	public static function findByValueCategoryName( $value, $categoryName ) {

		return Option::findByValueCategoryName( $value, $categoryName );
	}

	/**
	 * @param integer $categoryId - category id
	 * @return array - an array having id as key and name as value for given category id.
	 */
	public static function getIdNameMapByCategoryId( $categoryId, $prepend = [], $append = [] ) {
		
		return self::findMap( 'id', 'name', CoreTables::TABLE_OPTION, [ 'conditions' => [ 'categoryId' => $categoryId ], 'asArray' => false, 'prepend' => $prepend, 'append' => $append ] );
	}

	/**
	 * @param integer $categoryName - category name
	 * @return array - an array having id as key and name as value for given category name.
	 */
	public static function getIdNameMapByCategoryName( $categoryName, $prepend = [], $append = [] ) {

		$category	= Category::findByName( $categoryName );

		return self::findMap( 'id', 'name', CoreTables::TABLE_OPTION, [ 'conditions' => [ 'categoryId' => $category->id ], 'asArray' => false, 'prepend' => $prepend, 'append' => $append ] );
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
	public static function getValueNameMapByCategoryName( $categoryName ) {

		$category	= Category::findByName( $categoryName );
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

		return self::getDataProvider( new Option(), $config );
	}
	
	// Delete ----
	
	public static function delete( $model ) {
		
		$model->delete();
			
	}
}

?>