<?php
namespace cmsgears\core\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Gallery;
use cmsgears\core\common\models\entities\ModelFile;

use cmsgears\core\admin\services\FileService;

use cmsgears\core\common\utilities\DateUtil;

class GalleryService extends \cmsgears\core\common\services\GalleryService {

	// Static Methods ----------------------------------------------

	// Pagination -------

	public static function getPagination() {

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

		return self::getPaginationDetails( new Gallery(), [ 'sort' => $sort, 'search-col' => 'name' ] );
	}

	// Create -----------

	public static function create( $gallery ) {
		
		// Set Attributes
		$date				= DateUtil::getMysqlDate();
		$gallery->createdAt	= $date;
		
		// Create Gallery
		$gallery->save();
		
		// Return Gallery
		return $gallery;
	}

	// Update -----------

	public static function update( $gallery ) {

		// Find existing Gallery
		$galleryToUpdate	= self::findById( $gallery->id );

		// Copy and set Attributes
		$date				= DateUtil::getMysqlDate();

		$galleryToUpdate->modifiedAt	= $date;

		$galleryToUpdate->copyForUpdateFrom( $gallery, [ 'name', 'description' ] );
		
		// Update Gallery
		$galleryToUpdate->update();
		
		// Return updated Gallery
		return $galleryToUpdate;
	}

	// Delete -----------

	public static function delete( $gallery ) {

		// Find existing Gallery
		$galleryToDelete	= self::findById( $gallery->id );

		// Delete Gallery
		$galleryToDelete->delete();

		return true;
	}

	public static function createItem( $gallery, $item ) {

		// Find User and Slider
		$user		= Yii::$app->user->getIdentity();
		$modelFile 	= new ModelFile();

		// Save Slide Image to Slide Dimensions
		FileService::saveImage( $item, $user, [ 'model' => $modelFile, 'attribute' => 'fileId' ] );
		
		if( $item->id > 0 ) {

			$modelFile->parentType	= CoreGlobal::TYPE_GALLERY;
			$modelFile->parentId	= $gallery->id;

			// commit slide
			$modelFile->save();
		}

		return true;
	}

	public static function updateItem( $item ) {
		
		$user		= Yii::$app->user->getIdentity();

		// Save Slide Image to Slide Dimensions
		FileService::saveImage( $item, $user );

		return true;
	}
}

?>