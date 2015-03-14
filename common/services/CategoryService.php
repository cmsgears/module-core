<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Category;

class CategoryService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function findById( $id ) {

		return Category::findOne( $id );
	}

	public static function findByName( $name ) {

		return Category::findByName( $name );
	}

	public static function findByType( $type ) {

		return Category::findByType( $type );
    }

	public static function getIdNameMapByType( $type ) {

		return self::findIdNameArrayList( "category_id", "category_name", CoreTables::TABLE_CATEGORY, [ "category_type" => $type ] );
	}

	public static function getOptionIdKeyMapById( $id ) {

		return self::findKeyValueMap( "option_id", "option_key", CoreTables::TABLE_OPTION, [ "option_category" => $id ] );
	}

	public static function getOptionIdKeyMapByName( $name, $prepend = null ) {

		$category	= self::findByName( $name );
		$options	= $category->options;
		$optionsMap	= array();

		if( isset( $prepend ) ) {
			
			foreach ( $prepend as $key => $value ) {

				$optionsMap[ $key ] = $value;
			}
		}

		foreach ( $options as $option ) {
			
			$optionsMap[ $option->getId() ] = $option->getKey();
		}
		
		return $optionsMap;
	}

	public static function getOptionValueKeyMapById( $id ) {

		$category	= self::findById( $id );
		$options	= $category->options;
		$optionsMap	= array();

		foreach ( $options as $option ) {
			
			$optionsMap[ $option->getValue() ] = $option->getKey();
		}
		
		return $optionsMap;
	}

	public static function getOptionValueKeyMapByName( $name ) {

		$category	= self::findByName( $name );
		$options	= $category->options;
		$optionsMap	= array();

		foreach ( $options as $option ) {
			
			$optionsMap[ $option->getValue() ] = $option->getKey();
		}

		return $optionsMap;
	}
}

?>