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

		return ModelComment::findById( $id );
	}

	// Pagination ----------

	public static function getPagination( $config = [] ) {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name'
	            ],
	            'email' => [
	                'asc' => [ 'email' => SORT_ASC ],
	                'desc' => ['email' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'email'
	            ],
	            'rating' => [
	                'asc' => [ 'rating' => SORT_ASC ],
	                'desc' => ['rating' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'rating'
	            ],
	            'cdate' => [
	                'asc' => [ 'createdAt' => SORT_ASC ],
	                'desc' => ['createdAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'cdate'
	            ],
	            'udate' => [
	                'asc' => [ 'updatedAt' => SORT_ASC ],
	                'desc' => ['updatedAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'udate'
	            ],
	            'adate' => [
	                'asc' => [ 'approvedAt' => SORT_ASC ],
	                'desc' => ['approvedAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'adate'
	            ]
	        ],
	        'defaultOrder' => [
	        	'adate' => SORT_DESC
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

	public static function getPaginationByParent( $parentId, $parentType, $type = ModelComment::TYPE_COMMENT, $status = ModelComment::STATUS_APPROVED ) {

		return self::getPagination( [ 'conditions' => [ 'baseId' => null, 'parentId' => $parentId, 'parentType' => $parentType, 'type' => $type, 'status' => $status ] ] );
	}

	public static function getPaginationByParentType( $parentType, $type = ModelComment::TYPE_COMMENT, $status = ModelComment::STATUS_APPROVED ) {

		return self::getPagination( [ 'conditions' => [ 'baseId' => null, 'parentType' => $parentType, 'type' => $type, 'status' => $status ] ] );
	}

	public static function getPaginationByBaseId( $baseId, $type = ModelComment::TYPE_COMMENT, $status = ModelComment::STATUS_APPROVED ) {

		return self::getPagination( [ 'conditions' => [ 'baseId' => $baseId, 'type' => $type, 'status' => $status ] ] );
	}
}

?>