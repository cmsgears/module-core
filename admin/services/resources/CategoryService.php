<?php
namespace cmsgears\core\admin\services\resources;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\resources\Category;

class CategoryService extends \cmsgears\core\common\services\resources\CategoryService {

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
	        ]
	    ]);

		if( !isset( $config[ 'conditions' ] ) ) {

			$config[ 'conditions' ] = [];
		}

		// Restrict to site
		if( !isset( $config[ 'site' ] ) || !$config[ 'site' ] ) {

			$config[ 'conditions' ][ 'siteId' ] = Yii::$app->cmgCore->siteId;

			unset( $config[ 'site' ] );
		}

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		if( !isset( $config[ 'search-col' ] ) ) {

			$config[ 'search-col' ] = 'name';
		}

		return self::getDataProvider( new Category(), $config );
	}

	public static function getPaginationByType( $type ) {

		return self::getPagination( [ 'conditions' => [ 'type' => $type ] ] );
	}
}

?>