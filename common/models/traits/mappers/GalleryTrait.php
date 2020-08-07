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
			->where( "$modelGalleryTable.parentType='$this->modelType'" )
			->orderBy( [ "$modelGalleryTable.order" => SORT_DESC, "$modelGalleryTable.id" => SORT_DESC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveModelGalleries() {

		$modelGalleryTable = ModelGallery::tableName();

		return $this->hasMany( ModelGallery::class, [ 'parentId' => 'id' ] )
			->where( "$modelGalleryTable.parentType='$this->modelType' AND $modelGalleryTable.active=1" )
			->orderBy( [ "$modelGalleryTable.order" => SORT_DESC, "$modelGalleryTable.id" => SORT_DESC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelGalleriesByType( $type, $active = true ) {

		$modelGalleryTable = ModelGallery::tableName();

		if( $active ) {

			return $this->hasMany( ModelGallery::class, [ 'parentId' => 'id' ] )
				->where( "$modelGalleryTable.parentType=:ptype AND $modelGalleryTable.type=:type AND $modelGalleryTable.active=:active", [ ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )
				->orderBy( [ "$modelGalleryTable.order" => SORT_DESC, "$modelGalleryTable.id" => SORT_DESC ] );
		}

		return $this->hasMany( ModelGallery::class, [ 'parentId' => 'id' ] )
			->where( "$modelGalleryTable.parentType=:ptype AND $modelGalleryTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] )
			->orderBy( [ "$modelGalleryTable.order" => SORT_DESC, "$modelGalleryTable.id" => SORT_DESC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getGalleries() {

		$galleryTable		= Gallery::tableName();
		$modelGalleryTable	= ModelGallery::tableName();

		return Gallery::find()
			->leftJoin( $modelGalleryTable, "$modelGalleryTable.modelId=$galleryTable.id" )
			->where( "$modelGalleryTable.parentId=:pid AND $modelGalleryTable.parentType=:ptype", [ ':pid' => $this->id, ':ptype' => $this->modelType ] )
			->orderBy( [ "$modelGalleryTable.order" => SORT_DESC, "$modelGalleryTable.id" => SORT_DESC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveGalleries() {

		$galleryTable		= Gallery::tableName();
		$modelGalleryTable	= ModelGallery::tableName();

		return Gallery::find()
			->leftJoin( $modelGalleryTable, "$modelGalleryTable.modelId=$galleryTable.id" )
			->where( "$modelGalleryTable.parentId=:pid AND $modelGalleryTable.parentType=:ptype AND $modelGalleryTable.active=1", [ ':pid' => $this->id, ':ptype' => $this->modelType ] )
			->orderBy( [ "$modelGalleryTable.order" => SORT_DESC, "$modelGalleryTable.id" => SORT_DESC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getGalleriesByType( $type, $active = true ) {

		$galleryTable		= Gallery::tableName();
		$modelGalleryTable	= ModelGallery::tableName();

		if( $active ) {

			return Gallery::find()
				->leftJoin( $modelGalleryTable, "$modelGalleryTable.modelId=$galleryTable.id" )
				->where( "$modelGalleryTable.parentId=:pid AND $modelGalleryTable.parentType=:ptype AND $modelGalleryTable.type=:type AND $modelGalleryTable.active=:active", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )
				->orderBy( [ "$modelGalleryTable.order" => SORT_DESC, "$modelGalleryTable.id" => SORT_DESC ] )
				->all();
		}

		return Gallery::find()
			->leftJoin( $modelGalleryTable, "$modelGalleryTable.modelId=$galleryTable.id" )
			->where( "$modelGalleryTable.parentId=:pid AND $modelGalleryTable.parentType=:ptype AND $modelGalleryTable.type=:type", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':type' => $type ] )
			->orderBy( [ "$modelGalleryTable.order" => SORT_DESC, "$modelGalleryTable.id" => SORT_DESC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getGalleryByCode( $code ) {

		$galleryTable		= Gallery::tableName();
		$modelGalleryTable	= ModelGallery::tableName();

		return File::find()
			->leftJoin( $modelGalleryTable, "$modelGalleryTable.modelId=$galleryTable.id" )
			->where( "$modelGalleryTable.parentId=:pid AND $modelGalleryTable.parentType=:ptype AND $modelGalleryTable.code=:code", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':code' => $code ] )
			->one();
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
