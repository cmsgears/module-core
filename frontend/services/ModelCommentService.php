<?php
namespace cmsgears\core\frontend\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\entities\ModelComment;
 
/**
 * The class ModelCommentService is base class to perform database activities for ModelComment Entity.
 */
class ModelCommentService extends \cmsgears\core\common\services\ModelCommentService {

	// Static Methods ----------------------------------------------

	// Read ----------------
	
	public static function findById( $id ) {
		
		return ModelComment::findOne( $id );
	}
	
	// Data Provider ----

	/**
	 * @param array $config to generate query
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
	            ]
	        ]
	    ]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		if( !isset( $config[ 'search-col' ] ) ) {

			$config[ 'search-col' ] = 'name';
		}
		
		return self::getDataProvider( new ModelComment(), $config );
	}
 	
	// Update -----------

	public static function updateStatus( $model, $status ) {

		$model->status	= $status;

		$model->update();
	}
}

?>