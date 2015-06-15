<?php
namespace cmsgears\core\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\entities\Permission;

class PermissionService extends \cmsgears\core\common\services\PermissionService {

	// Static Methods ----------------------------------------------

	// Pagination -------

	/**
	 * @return ActiveDataProvider
	 */
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

		return self::getDataProvider( new Permission(), [ 'conditions' => $conditions, 'query' => $query, 'sort' => $sort, 'search-col' => 'name' ] );
	}
}

?>