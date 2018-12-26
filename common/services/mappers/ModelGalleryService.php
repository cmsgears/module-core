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

/**
 * ModelGalleryService provide service methods of gallery mapper.
 *
 * @since 1.0.0
 */
class ModelGalleryService extends \cmsgears\core\common\services\base\ModelMapperService implements IModelGalleryService {

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

	public function createWithParent( $parent, $config = [] ) {

		$modelClass	= static::$modelClass;

		$parentId	= $config[ 'parentId' ];
		$parentType	= $config[ 'parentType' ];
		$type		= isset( $config[ 'type' ] ) ? $config[ 'type' ] : CoreGlobal::TYPE_DEFAULT;
		$order		= isset( $config[ 'order' ] ) ? $config[ 'order' ] : 0;

		$parent->type = $parentType;

		$gallery = $this->galleryService->create( $parent );

		$model = new $modelClass;

		$model->modelId		= $gallery->id;
		$model->parentId	= $parentId;
		$model->parentType	= $parentType;

		$model->type	= $type;
		$model->order	= $order;
		$model->active	= true;

		return parent::create( $model );
	}

	// Update -------------

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
