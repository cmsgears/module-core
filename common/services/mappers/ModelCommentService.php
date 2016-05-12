<?php
namespace cmsgears\core\common\services\mappers;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\mappers\ModelComment;

use cmsgears\core\common\utilities\DateUtil;

/**
 * The class ModelCommentService is base class to perform database activities for ModelComment Entity.
 */
class ModelCommentService extends \cmsgears\core\common\services\base\Service {

	public function findById( $id ) {

		return ModelComment::findById( $id );
	}

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function getById( $id ) {

        return ModelComment::findById( $id );
	}

	public static function getByParent( $parentId, $parentType, $type = ModelComment::TYPE_COMMENT, $status = ModelComment::STATUS_APPROVED ) {

		return ModelComment::queryByParent( $parentId, $parentType, $type, $status )->andWhere( [ 'baseId' => null ] )->all();
	}

	public static function getByParentType( $parentType, $type = ModelComment::TYPE_COMMENT, $status = ModelComment::STATUS_APPROVED ) {

		return ModelComment::queryByParentType( $parentType, $type, $status )->andWhere( [ 'baseId' => null ] )->all();
	}

	/**
	 * It can be used to get child comments for given base id.
	 */
	public static function getByBaseId( $baseId, $type = ModelComment::TYPE_COMMENT, $status = ModelComment::STATUS_APPROVED ) {

		return ModelComment::queryByBaseId( $baseId, $type, $status )->all();
	}

    /**
	 * It can be used in cases where only one comment is allowed for an email.
	 */
    public static function isExistByEmail( $email ) {

        return ModelComment::queryByEmail( $email )->one() != null;
    }

    public static function getByEmail( $email ) {

        return ModelComment::queryByEmail( $email )->one();
    }

	// Data Provider ----

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

	public static function getPaginationByParent( $parentId, $parentType, $config = [] ) {

		$type 	= isset( $config[ 'type' ] ) ? $config[ 'type' ] : ModelComment::TYPE_COMMENT;
		$status = isset( $config[ 'status' ] ) ? $config[ 'status' ] : ModelComment::STATUS_APPROVED;

		$config[ 'conditions' ] = [ 'baseId' => null, 'parentId' => $parentId, 'parentType' => $parentType, 'type' => $type, 'status' => $status ];

		return self::getPagination( $config );
	}

	public static function getPaginationByParentType( $parentType ) {

		$type 	= isset( $config[ 'type' ] ) ? $config[ 'type' ] : ModelComment::TYPE_COMMENT;
		$status = isset( $config[ 'status' ] ) ? $config[ 'status' ] : ModelComment::STATUS_APPROVED;

		$config[ 'conditions' ] = [ 'baseId' => null, 'parentType' => $parentType, 'type' => $type, 'status' => $status ];

		return self::getPagination( $config );
	}

	public static function getPaginationByBaseId( $baseId, $type = ModelComment::TYPE_COMMENT, $status = ModelComment::STATUS_APPROVED ) {

		return self::getPagination( [ 'conditions' => [ 'baseId' => $baseId, 'type' => $type, 'status' => $status ] ] );
	}

	// Create -----------

 	public static function create( $comment ) {

		$comment->agent	= Yii::$app->request->userAgent;
		$comment->ip	= Yii::$app->request->userIP;

		$comment->save();

		return $comment;
 	}

	// Update -----------

	public static function update( $comment ) {

		// Find existing Comment
		$commentToUpdate	= self::getById( $comment->id );

		// Copy and set Attributes
		$commentToUpdate->copyForUpdateFrom( $comment, [ 'name', 'email', 'avatarUrl', 'websiteUrl', 'rating', 'content' ] );

		// Update Comment
		$commentToUpdate->update();

		// Return updated Comment
		return $commentToUpdate;
	}

    public static function updateSpamRequest( $model ) {

        $model->setDataAttribute( CoreGlobal::ATTRIBUTE_COMMENT_SPAM_REQUEST, true );
        $model->update();
    }

    public static function updateDeleteRequest( $model ) {

        $model->setDataAttribute( CoreGlobal::ATTRIBUTE_COMMENT_DELETE_REQUEST, true );
        $model->update();
    }

	public static function updateStatus( $model, $status ) {

		$model->status	= $status;

		$model->update();
	}

	public static function approve( $model ) {

		$model->approvedAt	= DateUtil::getDateTime();

		self::updateStatus( $model, ModelComment::STATUS_APPROVED );
	}

	public static function block( $model ) {

		self::updateStatus( $model, ModelComment::STATUS_BLOCKED );
	}

	public static function markSpam( $model ) {

		self::updateStatus( $model, ModelComment::STATUS_SPAM );
	}

	public static function markDelete( $model ) {

		self::updateStatus( $model, ModelComment::STATUS_DELETED );
	}

	// Delete -----------

	public static function delete( $comment ) {

		// Find existing Comment
		$commentToDelete	= self::getById( $comment->id );

		// Delete Comment
		$commentToDelete->delete();
    }
}

?>