<?php
namespace cmsgears\core\common\services\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\resources\Tag;
use cmsgears\core\common\models\mappers\ModelTag;

use cmsgears\core\common\services\resources\TagService;

/**
 * The class ModelTagService is base class to perform database activities for ModelTag Entity.
 */
class ModelTagService extends \cmsgears\core\common\services\base\Service {

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

	public static function findAllByTagId( $tagId ) {

		return ModelTag::findAllByTagId( $tagId );
	}

	// Create ----------------

	public static function create( $model ) {

		$model->save();
	}

	public static function createFromCsv( $parentId, $parentType, $tags ) {

		$tags	= preg_split( "/,/", $tags );

		foreach ( $tags as $tagName ) {

			$tagName	= trim( $tagName );
			$tag		= Tag::findByNameType( $tagName, $parentType );

			if( !isset( $tag ) ) {

				$tag			= new Tag();
				$tag->siteId	= Yii::$app->cmgCore->siteId;
				$tag->name		= $tagName;
				$tag->type		= $parentType;
				$tag			= TagService::create( $tag );
			}

			$modelTag	= self::findByTagId( $parentId, $parentType, $tag->id );

			// Create if does not exist
			if( !isset( $modelTag ) ) {

				$modelTag	= new ModelTag();

				$modelTag->tagId		= $tag->id;
				$modelTag->parentId		= $parentId;
				$modelTag->parentType	= $parentType;
				$modelTag->order		= 0;
				$modelTag->active		= true;

				$modelTag->save();
			}
			// Activate if already exist
			else {

				self::activate( $modelTag, 1 );
			}
		}
	}

	// Update ---------------

	public static function update( $modelTag ) {

		// Find existing Model Tag
		$modelTagToUpdate	= self::findById( $modelTag->id );

		// Copy and set Attributes
		$modelTagToUpdate->copyForUpdateFrom( $modelTag, [ 'order', 'active' ] );

		// Update Model Tag
		$modelTagToUpdate->update();

		// Return updated Model Tag
		return $modelTagToUpdate;
	}

	public static function activate( $modelTag ) {

		$modelTag->active	= true;

		$modelTag->update();

		return $modelTag;
	}

	public static function deActivate( $modelTag ) {

		$modelTag->active	= false;

		$modelTag->update();

		return $modelTag;
	}

	// Delete ----------------

	public static function delete( $model ) {

		$model->delete();

		return true;
	}

	public static function deleteByTagSlug( $parentId, $parentType, $tagSlug, $delete = false ) {

		$tag				= TagService::findBySlug( $tagSlug );
		$modelTagToDelete	= self::findByTagId( $parentId, $parentType, $tag->id );

		if( isset( $modelTagToDelete ) ) {

			if( $delete ) {

				$modelTagToDelete->delete();
			}
			else {

				self::deActivate( $modelTagToDelete );
			}
		}

		return true;
	}
}

?>