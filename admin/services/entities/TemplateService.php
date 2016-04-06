<?php
namespace cmsgears\core\admin\services\entities;

// Yii Imports
use \Yii;
use yii\data\Sort;

use cmsgears\core\common\models\entities\Template;

class TemplateService extends \cmsgears\core\common\services\entities\TemplateService {

	// Static Methods ----------------------------------------------

	// Pagination -------

	public static function getPagination( $config = [] ) {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => [ 'name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ]
	        ]
	    ]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		if( !isset( $config[ 'search-col' ] ) ) {

			$config[ 'search-col' ] = 'name';
		}

		return self::getDataProvider( new Template(), $config );
	}

	public static function getPaginationByType( $type ) {

		return self::getPagination( [ 'conditions' => [ 'type' => $type ] ] );
	}
}

?>