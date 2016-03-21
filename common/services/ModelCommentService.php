<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\ModelComment;

use cmsgears\core\common\utilities\DateUtil;

/**
 * The class ModelCommentService is base class to perform database activities for ModelComment Entity.
 */
class ModelCommentService extends Service {

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
		$commentToUpdate	= self::findById( $comment->id );

		// Copy and set Attributes
		$commentToUpdate->copyForUpdateFrom( $comment, [ 'name', 'email', 'avatarUrl', 'websiteUrl', 'rating', 'content' ] );

		// Update Comment
		$commentToUpdate->update();

		// Return updated Comment
		return $commentToUpdate;
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
		$commentToDelete	= self::findById( $comment->id );

		// Delete Comment
		$commentToDelete->delete();
    }
}

?>