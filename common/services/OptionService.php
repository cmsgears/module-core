<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Category;
use cmsgears\core\common\models\entities\Option;

class OptionService extends Service {

	public static function findByCategoryIdKey( $categoryId, $key ) {

		return Option::findByCategoryIdKey( $categoryId, $key );
	}
	
	public static function findByCategoryNameKey( $categoryName, $key ) {

		return Option::findByCategoryNameKey( $categoryName, $key );
	}
}

?>