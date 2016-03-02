<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\ModelComment;

/**
 * The class ModelCommentService is base class to perform database activities for ModelComment Entity.
 */
class ModelCommentService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function findById( $id ) {

        return ModelComment::findById( $id );
	}

    public static function findApprovedByType( $type ) {

        return ModelComment::findAll( [ 'type' => $type, 'status' =>  ModelComment::STATUS_APPROVED ] );                     
    }

	public static function findApprovedByBaseId( $baseId, $commentType = ModelComment::TYPE_COMMENT ) {

		return ModelComment::findByBaseId( $baseId, $commentType );
	}

	public static function findApprovedByParent( $parentId, $parentType, $commentType = ModelComment::TYPE_COMMENT ) {

		return ModelComment::findByParent( $parentId, $parentType, $commentType );
	}

	public static function findApprovedByParentType( $parentType, $commentType = ModelComment::TYPE_COMMENT ) {

		return ModelComment::findByParentType( $parentType, $commentType );
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
		$commentToUpdate->copyForUpdateFrom( $comment, [ 'name', 'email', 'avatarUrl', 'websiteUrl', 'rating', 'content', 'status' ] );

		// Update Comment
		$commentToUpdate->update();

		// Return updated Comment
		return $commentToUpdate;
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