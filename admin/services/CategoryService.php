<?php
namespace cmsgears\core\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\entities\Category;

class CategoryService extends \cmsgears\core\common\services\CategoryService {

	// Static Methods ----------------------------------------------

	// Pagination -------

	public static function getPagination( $conditions = [], $query = null ) {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ]
	        ]
	    ]);

		return self::getPaginationDetails( new Category(), [ 'conditions' => $conditions, 'query' => $query, 'sort' => $sort, 'search-col' => 'name' ] );
	}

	public static function getPaginationByType( $type ) {

		return self::getPagination( [ "type" => $type ] );
	}
}

?>