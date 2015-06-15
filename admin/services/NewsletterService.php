<?php
namespace cmsgears\core\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\entities\Newsletter;

use cmsgears\core\common\utilities\DateUtil;

class NewsletterService extends \cmsgears\core\common\services\NewsletterService {

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
	            ],
	            'cdate' => [
	                'asc' => [ 'createdAt' => SORT_ASC ],
	                'desc' => ['createdAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'cdate',
	            ],
	            'udate' => [
	                'asc' => [ 'modifiedAt' => SORT_ASC ],
	                'desc' => ['modifiedAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'udate',
	            ],
	            'ldate' => [
	                'asc' => [ 'lastSentAt' => SORT_ASC ],
	                'desc' => ['lastSentAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'udate',
	            ]
	        ]
	    ]);

		return self::getDataProvider( new Newsletter(), [ 'conditions' => $conditions, 'query' => $query, 'sort' => $sort, 'search-col' => 'name' ] );
	}
}

?>