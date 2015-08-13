<?php
namespace cmsgears\core\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\entities\Gallery;

class GalleryService extends \cmsgears\core\common\services\GalleryService {

	// Static Methods ----------------------------------------------

	// Pagination -------

	public static function getPagination( $config = [] ) {

	    $sort = new Sort([
	        'attributes' => [
	            'owner' => [
	                'asc' => [ 'createdBy' => SORT_ASC ],
	                'desc' => ['createdBy' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'owner'
	            ],
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
	            ],
	            'title' => [
	                'asc' => [ 'title' => SORT_ASC ],
	                'desc' => ['title' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'title'
	            ]
	        ]
	    ]);

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'query' ] = Gallery::findWithOwner();
		}

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		if( !isset( $config[ 'search-col' ] ) ) {

			$config[ 'search-col' ] = 'name';
		}

		return self::getDataProvider( new Gallery(), $config );
	}
}

?>