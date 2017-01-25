<?php
namespace cmsgears\core\common\services\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Gallery;
use cmsgears\core\common\models\mappers\ModelGallery;

use cmsgears\core\common\services\interfaces\resources\IGalleryService;
use cmsgears\core\common\services\interfaces\mappers\IModelGalleryService;

use cmsgears\core\common\services\traits\MapperTrait;

/**
 * The class ModelGalleryService is base class to perform database activities for ModelGallery Entity.
 */
class ModelGalleryService extends \cmsgears\core\common\services\base\EntityService implements IModelGalleryService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\mappers\ModelGallery';

	public static $modelTable	= CoreTables::TABLE_MODEL_GALLERY;

	public static $parentType	= null;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $galleryService;

	// Traits ------------------------------------------------------

	use MapperTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( IGalleryService $galleryService, $config = [] ) {

		$this->galleryService	= $galleryService;

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
		$modelGallery				= new ModelGallery();

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

			$existingGallery	= $this->getByModelId( $parentId, $parentType, $gallery->id );

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

		$gallery	= $config[ 'gallery' ];

		$this->galleryService->update( $gallery );

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

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
