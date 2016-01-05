<?php
namespace cmsgears\core\frontend\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\Gallery;

class GalleryService extends \cmsgears\core\common\services\GalleryService {

	// Static Methods ----------------------------------------------

	// Update -----------

	/**
	 * @param Gallery $gallery
	 * @return Gallery
	 */
	public static function update( $gallery ) {

		// Find existing Gallery
		$galleryToUpdate	= self::findById( $gallery->id );

		// Copy and set Attributes
		$galleryToUpdate->copyForUpdateFrom( $gallery, [ 'name', 'description' ] );

		// Update Gallery
		$galleryToUpdate->update();

		// Return updated Gallery
		return $galleryToUpdate;
	}
}

?>