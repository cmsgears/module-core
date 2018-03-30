<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\mappers;

// CMG Imports
use cmsgears\core\common\services\interfaces\resources\IGalleryService;
use cmsgears\core\common\services\interfaces\mappers\IModelGalleryService;

use cmsgears\core\common\services\base\ModelMapperService;

/**
 * ModelGalleryService provide service methods of gallery mapper.
 *
 * @since 1.0.0
 */
class ModelGalleryService extends ModelMapperService implements IModelGalleryService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\mappers\ModelGallery';

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $galleryService;

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( IGalleryService $galleryService, $config = [] ) {

		$this->galleryService = $galleryService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelGalleryService -------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $gallery, $config = [] ) {

		$parentId	= $config[ 'parentId' ];
		$parentType = $config[ 'parentType' ];
		$type		= isset( $config[ 'type' ] ) ? $config[ 'type' ] : null;
		$order		= isset( $config[ 'order' ] ) ? $config[ 'order' ] : 0;

		// Create Gallery
		$gallery->type	= $parentType;

		$gallery->save();

		// Create Model Gallery
		$modelClass	= static::$modelClass;

		$modelGallery = new $modelClass();

		$modelGallery->modelId		= $gallery->id;
		$modelGallery->parentId		= $parentId;
		$modelGallery->parentType	= $parentType;
		$modelGallery->type			= $type;
		$modelGallery->order		= $order;
		$modelGallery->active		= true;

		$modelGallery->save();
	}

	public function createOrUpdate( $gallery, $config = [] ) {

		$parentId	= $config[ 'parentId' ];
		$parentType = $config[ 'parentType' ];
		$type		= isset( $config[ 'type' ] ) ? $config[ 'type' ] : null;
		$order		= isset( $config[ 'order' ] ) ? $config[ 'order' ] : 0;

		// Update Existing
		if( isset( $gallery->id ) && !empty( $gallery->id ) ) {

			$existingGallery = $this->getByModelId( $parentId, $parentType, $gallery->id );

			if( isset( $existingGallery ) ) {

				return $this->update( $existingGallery, [ 'gallery' => $gallery ] );
			}
		}
		// Create New
		else {

			return $this->create( $gallery, $config );
		}
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'type', 'order' ];

		$gallery = $config[ 'gallery' ];

		$this->galleryService->update( $gallery );

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelGalleryService -------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
