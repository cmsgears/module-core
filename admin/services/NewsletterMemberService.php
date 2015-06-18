<?php
namespace cmsgears\core\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\entities\NewsletterMember;

class NewsletterMemberService extends \cmsgears\core\common\services\NewsletterMemberService {

	// Static Methods ----------------------------------------------

	// Pagination -------

	public static function getPagination( $conditions = [], $query = null ) {

	    $sort = new Sort([
	        'attributes' => [
	            'email' => [
	                'asc' => [ 'email' => SORT_ASC ],
	                'desc' => ['email' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'email',
	            ]
	        ]
	    ]);

		return self::getDataProvider( new NewsletterMember(), [ 'conditions' => $conditions, 'query' => $query, 'sort' => $sort, 'search-col' => 'email' ] );
	}
}

?>