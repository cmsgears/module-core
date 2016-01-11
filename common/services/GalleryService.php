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
	 * @param string $slug
	 * @return Gallery
	 */
	public static function findBySlug( $slug ) {

		return Gallery::findBySlug( $slug );
	}

	/**
	 * @param string $id
	 * @return array - An array of associative array of gallery id and name
	 */
	public static function getIdNameList() {

		return self::findIdNameList( 'id', 'name', CoreTables::TABLE_GALLERY );
	}

	// Data Provider ----

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new Gallery(), $config );
	}

	// Create -----------
	
	/**
	 * @param Gallery $gallery
	 * @return Gallery
	 */
	public static function create( $gallery ) {

		// Create Gallery
		$gallery->save();

		// Return Gallery
		return $gallery;
	}

	public static function createByNameType( $name, $type ) {

		$gallery			= new Gallery();
		$gallery->name		= $name;
		$gallery->type		= $type;

		return self::create( $gallery );
	}

	/**
	 * @param Gallery $gallery
	 * @param CmgFile $item
	 * @return boolean
	 */
	public static function createItem( $gallery, $item ) {

		$modelFile 	= new ModelFile();

		// Save Gallery Image
		FileService::saveImage( $item, [ 'model' => $modelFile, 'attribute' => 'fileId' ] );

		// Save Gallery Item
		if( $item->id > 0 ) {

			$modelFile->parentType	= CoreGlobal::TYPE_GALLERY;
			$modelFile->parentId	= $gallery->id;

			$modelFile->save();
		}

		return $item;
	}

	// Update -----------

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

		// TODO: Delete Gallery Items

		// Delete Gallery
		$galleryToDelete->delete();

		return true;
	}
}

?>