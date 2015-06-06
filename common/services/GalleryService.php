<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Gallery;
use cmsgears\core\common\models\entities\ModelFile;

use cmsgears\core\admin\services\FileService;

/**
 * The class GalleryService is base class to perform database activities for Gallery Entity.
 */
class GalleryService extends Service {

	// Static Methods ----------------------------------------------
	
	// Read ----------------

	/**
	 * @param integer $id
	 * @return Gallery
	 */
	public static function findById( $id ) {

		return Gallery::findById( $id );
	}

	/**
	 * @param string $name
	 * @return Gallery
	 */
	public static function findByName( $name ) {

		return Gallery::findByName( $name );
	}

	/**
	 * @param string $id
	 * @return array - An array of associative array of gallery id and name
	 */
	public static function getIdNameList() {

		return self::findIdNameList( 'id', 'name', CoreTables::TABLE_GALLERY );
	}

	// Create -----------
	
	/**
	 * @param Gallery $gallery
	 * @return Gallery
	 */
	public static function create( $gallery ) {

		// Set Attributes
		$user				= Yii::$app->user->getIdentity();
		$gallery->createdBy	= $user->id;

		// Create Gallery
		$gallery->save();

		// Return Gallery
		return $gallery;
	}

	/**
	 * @param Gallery $gallery
	 * @param CmgFile $item
	 * @return boolean
	 */
	public static function createItem( $gallery, $item ) {

		// Find User and Slider
		$modelFile 	= new ModelFile();

		// Save Slide Image to Slide Dimensions
		FileService::saveImage( $item, [ 'model' => $modelFile, 'attribute' => 'fileId' ] );
		
		if( $item->id > 0 ) {

			$modelFile->parentType	= CoreGlobal::TYPE_GALLERY;
			$modelFile->parentId	= $gallery->id;

			// commit slide
			$modelFile->save();
		}

		return true;
	}

	// Update -----------

	/**
	 * @param Gallery $gallery
	 * @return Gallery
	 */
	public static function update( $gallery ) {

		// Find existing Gallery
		$galleryToUpdate	= self::findById( $gallery->id );

		// Copy and set Attributes
		$user					= Yii::$app->user->getIdentity();
		$gallery->modifiedBy	= $user->id;

		$galleryToUpdate->copyForUpdateFrom( $gallery, [ 'name', 'description' ] );
		
		// Update Gallery
		$galleryToUpdate->update();
		
		// Return updated Gallery
		return $galleryToUpdate;
	}

	/**
	 * @param CmgFile $item
	 * @return boolean
	 */
	public static function updateItem( $item ) {

		// Save Gallery Item
		FileService::saveImage( $item );

		return true;
	}

	// Delete -----------

	/**
	 * @param Gallery $gallery
	 * @return boolean
	 */
	public static function delete( $gallery ) {

		// Find existing Gallery
		$galleryToDelete	= self::findById( $gallery->id );

		// Delete Gallery
		$galleryToDelete->delete();

		return true;
	}
}

?>