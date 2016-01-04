<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii; 

// CMG Imports
use cmsgears\core\common\models\entities\ModelTag;

/**
 * The class ModelTagService is base class to perform database activities for ModelTag Entity.
 */
class ModelTagService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function findByParentType( $parentType ) {
		
		return ModelTag::findByParentType( $parentType );
	}
	
	public static function findByParentId( $parentId ) {
		
		return ModelTag::findByParentId( $parentId );
	}

	public static function findActiveByParentId( $parentId ) {

		return ModelTag::findActiveByParentId( $parentId );
	}

	public static function findByTagId( $parentId, $parentType, $tagId ) {
		
		return ModelTag::findByTagId( $parentId, $parentType, $tagId );
	}
	
	public static function findByParentIdParentType( $parentId, $parentType ) {
		
		return ModelTag::findByParentIdParentType( $parentId, $parentType );
	}
	
	public static function findActiveByTagIdParentType( $tagId, $parentType ) {
		
		return ModelTag::findActiveByTagIdParentType( $tagId, $parentType );
	}

	public static function findActiveByParentIdParentType( $parentId, $parentType ) {
		
		return ModelTag::findActiveByParentIdParentType( $parentId, $parentType );
	}

	// Create ----------------

	public static function create( $model ) {

		$model->save();
	}

	// Update ---------------

	public static function update( $parentId, $parentType, $tagId ) {

		$existingModelTag	= self::findByTagId( $parentId, $parentType, $tagId );

		if( isset( $existingModelTag ) ) {

			self::updateActive( $existingModelTag, 1 );
		}
		else {

			$modelTag				= new ModelTag();
			$modelTag->tagId		= $tagId;
			$modelTag->parentId		= $parentId;
			$modelTag->parentType	= $parentType;

			self::create( $modelTag );
		}
	}

	public static function updateActive( $model, $flag ) {

		$model->active	= $flag;

		$model->update();

		return $model;
	}

	// Delete ----------------

	public static function delete( $model ) {

		$model->delete();

		return true;
	} 
}

?>