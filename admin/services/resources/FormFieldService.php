<?php
namespace cmsgears\core\admin\services\resources;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\resources\FormField;

class FormFieldService extends \cmsgears\core\common\services\resources\FormFieldService {

	// Static Methods ----------------------------------------------

	// Pagination -------

	public static function getPagination( $config = [] ) {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ]
	        ],
	        'defaultOrder' => [
	        	'name' => SORT_DESC
	        ]
	    ]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		if( !isset( $config[ 'search-col' ] ) ) {

			$config[ 'search-col' ] = 'name';
		}

		return self::getDataProvider( new FormField(), $config );
	}

	public static function getPaginationByFormId( $formId ) {

		return self::getPagination( [ 'conditions' => [ 'formId' => $formId ] ] );
	}
}

?>