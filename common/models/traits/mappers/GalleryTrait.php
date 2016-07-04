<?php
namespace cmsgears\core\common\models\traits\mappers;

// Yii Import
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Gallery;
use cmsgears\core\common\models\mappers\ModelGallery;

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
					->where( "parentType='$this->mParentType'" );
	}

	public function getModelGalleryByType( $type, $first = true ) {

		$query = $this->hasOne( ModelGallery::className(), [ 'parentId' => 'id' ] )
						->where( "parentType=:ptype AND type=:type", [ ':ptype' => $this->mParentType, ':type' => $type ] );

		if( $first ) {

	    	return $query->one();
		}

    	return $query->all();
	}

	public function getGalleries() {

		$modelGalleryTable	= CoreTables::TABLE_MODEL_GALLERY;

    	return $this->hasMany( Gallery::className(), [ 'id' => 'modelId' ] )
					->viaTable( $modelGalleryTable, [ 'parentId' => 'id' ], function( $query ) use( &$modelGalleryTable ) {
                      	$query->onCondition( [ "$modelGalleryTable.parentType" => $this->mParentType ] );
					});
	}

	public function getGalleryById( $id ) {

		$galleryTable	= CoreTables::TABLE_GALLERY;

    	return $this->getGalleries()->where( [ "$galleryTable.id" => $id ] )->one();
	}

	public function getGalleryByType( $type, $first = true ) {

		$modelGalleryTable	= CoreTables::TABLE_MODEL_GALLERY;
		$query				= $this->hasMany( Gallery::className(), [ 'id' => 'modelId' ] )
									->viaTable( $modelGalleryTable, [ 'parentId' => 'id' ], function( $query ) use( &$modelGalleryTable, &$type ) {
										$query->onCondition( [ "$modelGalleryTable.parentType" => $this->mParentType, "$modelGalleryTable.type" => $type ] );
									});

		if( $first ) {

	    	return $query->one();
		}

		return $query->all();
	}
}

?>