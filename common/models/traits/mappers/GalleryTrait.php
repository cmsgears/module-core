<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\mappers;

// CMG Imports
use cmsgears\core\common\models\resources\Gallery;
use cmsgears\core\common\models\mappers\ModelGallery;

/**
 * A model can support for multiple or single gallery. In case of single gallery, model must have galleryId column.
 */
trait GalleryTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// GalleryTrait --------------------------

	/**
	 * @inheritdoc
	 */
	public function getModelGalleries() {

		$modelGalleryTable = ModelGallery::tableName();

		return $this->hasMany( ModelGallery::class, [ 'parentId' => 'id' ] )
			->where( "$modelGalleryTable.parentType='$this->modelType'" );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveModelGalleries() {

		$modelGalleryTable = ModelGallery::tableName();

		return $this->hasMany( ModelGallery::class, [ 'parentId' => 'id' ] )
			->where( "$modelGalleryTable.parentType='$this->modelType' AND $modelGalleryTable.active=1" );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelGalleriesByType( $type, $active = true ) {

		$modelGalleryTable = ModelGallery::tableName();

		return $this->hasOne( ModelGallery::class, [ 'parentId' => 'id' ] )
			->where( "$modelGalleryTable.parentType=:ptype AND $modelGalleryTable.type=:type AND $modelGalleryTable.active=:active", [ ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getGalleries() {

		$modelGalleryTable = ModelGallery::tableName();

		return $this->hasMany( Gallery::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelGalleryTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelGalleryTable ) {

					$query->onCondition( [ "$modelGalleryTable.parentType" => $this->modelType ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveGalleries() {

		$modelGalleryTable = ModelGallery::tableName();

		return $this->hasMany( Gallery::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelGalleryTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelGalleryTable ) {

					$query->onCondition( [ "$modelGalleryTable.parentType" => $this->modelType, "$modelGalleryTable.active" => true ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getGalleriesByType( $type, $active = true ) {

		$modelGalleryTable = ModelGallery::tableName();

		return $this->hasMany( Gallery::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelGalleryTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$type, &$active, &$modelGalleryTable ) {

					$query->onCondition( [ "$modelGalleryTable.parentType" => $this->modelType, "$modelGalleryTable.type" => $type, "$modelGalleryTable.active" => $active ] );
				}
			)->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getGalleryByTitle( $title ) {

		$galleryTable		= Gallery::tableName();
		$modelGalleryTable	= ModelGallery::tableName();

		return $this->hasOne( Gallery::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelGalleryTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelGalleryTable ) {

					$query->onCondition( "$modelGalleryTable.parentType=:type", [ ':type' => $this->modelType ] );
				}
			)->where( "$galleryTable.title=:title", [ ':title' => $title ] )->one();
	}

	// Useful for models having single gallery mapped via $galleryId.

	/**
	 * @inheritdoc
	 */
	public function hasGallery() {

		$result = isset( $this->galleryId ) && $this->galleryId > 0;

		return $result;
	}

	/**
	 * @inheritdoc
	 */
	public function getGallery() {

		return $this->hasOne( Gallery::class, [ 'id' => 'galleryId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// GalleryTrait --------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
