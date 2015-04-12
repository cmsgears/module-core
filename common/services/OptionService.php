<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\Option;

class OptionService extends Service {

	public static function findByCategoryId( $categoryId ) {

		return Option::findByCategoryId( $categoryId );
	}

	public static function findByCategoryIdKey( $categoryId, $key ) {

		return Option::findByCategoryIdKey( $categoryId, $key );
	}

	public static function findByCategoryName( $categoryName ) {

		return Option::findByCategoryName( $categoryName );
	}

	public static function findByCategoryNameKey( $categoryName, $key ) {

		return Option::findByCategoryNameKey( $categoryName, $key );
	}
}

?>