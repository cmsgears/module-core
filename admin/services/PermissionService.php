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
	public static function getPagination( $config = [] ) {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
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

		return self::getDataProvider( new Permission(), $config );
	}

	/**
	 * @return ActiveDataProvider
	 */
	public static function getPaginationByType( $type ) {

		if( isset( $type ) ) {

			return self::getPagination( [ 'conditions' => [ 'type' => $type ] ] );
		}

		return self::getPagination();
	}
}

?>