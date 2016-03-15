<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\Gallery;

/**
 * A model can support for multiple or single gallery. In case of single gallery, model must have galleryId column.
 */
trait GalleryTrait {

	public function hasGallery() {

		return $this->galleryId > 0;
	}

	public function getGallery() {

		return $this->hasOne( Gallery::className(), [ 'id' => 'galleryId' ] );
	}
}

?>