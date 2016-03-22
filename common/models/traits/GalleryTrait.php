<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Gallery;
use cmsgears\core\common\models\entities\ModelGallery;

/**
 * A model can support for multiple or single gallery. In case of single gallery, model must have galleryId column.
 */
trait GalleryTrait {

	// Single Gallery ----------------------------

	public function hasGallery() {

		return $this->galleryId > 0;
	}

	public function getGallery() {

		return $this->hasOne( Gallery::className(), [ 'id' => 'galleryId' ] );
	}

	// Multiple Galleries ------------------------

	public function getModelGalleries() {

    	return $this->hasMany( ModelGallery::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$this->parentType'" );
	}

	public function getModelGalleryByType( $type ) {

    	return $this->hasOne( ModelGallery::className(), [ 'parentId' => 'id' ] )
					->where( "parentType=:ptype AND type=:type", [ ':ptype' => $this->parentType, ':type' => $type ] )->one();
	}

	public function getGalleries() {

		$modelGalleryTable	= CoreTables::TABLE_MODEL_GALLERY;

    	return $this->hasMany( Gallery::className(), [ 'id' => 'galleryId' ] )
					->viaTable( $modelGalleryTable, [ 'parentId' => 'id' ], function( $query ) use( &$modelGalleryTable ) {
                      	$query->onCondition( [ "$modelGalleryTable.parentType" => $this->parentType ] );
					});
	}

	public function getGalleryById( $id ) {

		$galleryTable	= CoreTables::TABLE_GALLERY;

    	return $this->getGalleries()->where( [ "$galleryTable.id" => $id ] )->one();
	}

	public function getGalleryByType( $type ) {

		$modelGalleryTable	= CoreTables::TABLE_MODEL_GALLERY;

    	return $this->hasMany( Gallery::className(), [ 'id' => 'galleryId' ] )
					->viaTable( $modelGalleryTable, [ 'parentId' => 'id' ], function( $query ) use( &$modelGalleryTable, &$type ) {
                      	$query->onCondition( [ "$modelGalleryTable.parentType" => $this->parentType, "$modelGalleryTable.type" => $type ] );
					})->one();
	}

	public function getGalleriesByType( $type ) {

		$modelGallery	= CoreTables::TABLE_MODEL_GALLERY;

    	return $this->hasMany( Gallery::className(), [ 'id' => 'galleryId' ] )
					->viaTable( $modelGallery, [ 'galleryId' => 'id' ], function( $query ) use( &$modelGallery, &$type ) {
						$query->onCondition( [ "$modelGallery.parentType" => $this->parentType, "$modelGallery.type" => $type ] );
					})->all();
	}
}

?>