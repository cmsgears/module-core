<?php
namespace cmsgears\core\common\services\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\resources\Gallery;
use cmsgears\core\common\models\mappers\ModelGallery;

use cmsgears\core\common\services\resources\GalleryService;

/**
 * The class ModelGalleryService is base class to perform database activities for ModelGallery Entity.
 */
class ModelGalleryService extends \cmsgears\core\common\services\base\Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function getById( $id ) {

		return ModelGallery::findById( $id );
	}

	public static function getByGalleryId( $parentId, $parentType, $galleryId ) {

		return ModelGallery::queryByGalleryId( $parentId, $parentType, $galleryId )->one();
	}

	// Data Provider ----

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new ModelGallery(), $config );
	}

	// Create -----------

	public static function create( $gallery, $parentId, $parentType, $type = null, $order = 0 ) {

		// Create Gallery
		$gallery->type	= $parentType;

		$gallery->save();

		// Create Model Gallery
		$modelGallery				= new ModelGallery();

		$modelGallery->galleryId 	= $gallery->id;
		$modelGallery->parentId 	= $parentId;
		$modelGallery->parentType 	= $parentType;
		$modelGallery->type			= $type;
		$modelGallery->order		= $order;
		$modelGallery->active		= true;

		$modelGallery->save();

		// Return Model Gallery
		return $modelGallery;
	}

	public static function createOrUpdate( $gallery, $parentId, $parentType, $type = null, $order = 0 ) {

		// Update Existing
		if( isset( $gallery->id ) && !empty( $gallery->id ) ) {

			$existingGallery	= self::getByGalleryId( $parentId, $parentType, $gallery->id );

			if( isset( $existingGallery ) ) {

				return self::update( $existingGallery, $gallery );
			}
		}
		// Create New
		else {

			return self::create( $gallery, $parentId, $parentType, $type, $order );
		}
	}

	// Update -----------

	public static function update( $modelGallery, $gallery ) {

		// Update Gallery
		GalleryService::update( $gallery );

		// Find existing Model Gallery
		$galleryToUpdate	= self::getById( $modelGallery->id );

		// Copy Attributes
		$galleryToUpdate->copyForUpdateFrom( $modelGallery, [ 'type', 'order' ] );

		// Update Model Gallery
		$galleryToUpdate->update();

		// Return updated Model Gallery
		return $galleryToUpdate;
	}

	// Delete -----------

	public static function deleteByParent( $parentId, $parentType ) {

		ModelGallery::deleteByParent( $parentId, $parentType );
	}

	public static function delete( $model ) {

		return $model->delete();
	}
}

?>