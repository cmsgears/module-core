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

	public static function getPagination( $conditions = [], $query = null ) {

	    $sort = new Sort([
	        'attributes' => [
	            'owner' => [
	                'asc' => [ 'createdBy' => SORT_ASC ],
	                'desc' => ['createdBy' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'owner',
	            ],
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ]
	        ]
	    ]);

		if( !isset( $query ) ) {

			$query = Gallery::findWithOwner();
		}

		return self::getDataProvider( new Gallery(), [ 'conditions' => $conditions, 'query' => $query, 'sort' => $sort, 'search-col' => 'name' ] );
	}
}

?>