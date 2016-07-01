<?php
namespace cmsgears\core\common\services\resources;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Gallery;
use cmsgears\core\common\models\mappers\ModelGallery;
use cmsgears\core\common\models\mappers\ModelFile;

use cmsgears\core\common\services\interfaces\resources\IGalleryService;
use cmsgears\core\common\services\interfaces\resources\IFileService;

use cmsgears\core\common\services\traits\NameTrait;
use cmsgears\core\common\services\traits\SlugTrait;

/**
 * The class GalleryService is base class to perform database activities for Gallery Entity.
 */
class GalleryService extends \cmsgears\core\common\services\base\EntityService implements IGalleryService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\resources\Gallery';

	public static $modelTable	= CoreTables::TABLE_GALLERY;

	public static $parentType	= CoreGlobal::TYPE_GALLERY;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $fileService;

	// Traits ------------------------------------------------------

	use NameTrait;
	use SlugTrait;

	// Constructor and Initialisation ------------------------------

    public function __construct( IFileService $fileService, $config = [] ) {

		$this->fileService	= $fileService;

        parent::__construct( $config );
    }

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// GalleryService ------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

	    $sort = new Sort([
	        'attributes' => [
	            'owner' => [
	                'asc' => [ 'createdBy' => SORT_ASC ],
	                'desc' => ['createdBy' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'owner'
	            ],
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name'
	            ],
	            'slug' => [
	                'asc' => [ 'slug' => SORT_ASC ],
	                'desc' => ['slug' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'slug'
	            ],
	            'title' => [
	                'asc' => [ 'title' => SORT_ASC ],
	                'desc' => ['title' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'title'
	            ]
	        ]
	    ]);

		$config[ 'sort' ] = $sort;

		return parent::findPage( $config );
	}

	public function getPageByType( $type, $config = [] ) {

		$modelTable	= self::$modelTable;

		$config[ 'conditions' ][ "$modelTable.type" ] 	= $type;

		return $this->getPage( $config );
	}

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createByNameType( $name, $type ) {

		$gallery			= new Gallery();
		$gallery->name		= $name;
		$gallery->type		= $type;

		return $this->create( $gallery );
	}

	public function createItem( $gallery, $item ) {

		$modelFile 	= new ModelFile();

		// Save Gallery Image
		$this->fileService->saveImage( $item, [ 'model' => $modelFile, 'attribute' => 'modelId' ] );

		// Save Gallery Item
		if( $item->id > 0 ) {

			$modelFile->parentType	= CoreGlobal::TYPE_GALLERY;
			$modelFile->parentId	= $gallery->id;

			$modelFile->save();
		}

		return $item;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		// Provide activate/deactivate capability to admin
		if( isset( $config[ 'admin' ] ) && $config[ 'admin' ] ) {

			return parent::update( $model, [
				'attributes' => [ 'templateId', 'name', 'title', 'description', 'active' ]
			]);
		}

		return parent::update( $model, [
			'attributes' => [ 'templateId', 'name', 'title', 'description' ]
		]);
 	}

	public function updateItem( $item ) {

		// Save Gallery Item
		$this->fileService->saveImage( $item );

		return true;
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete dependencies
		$items	= $model->files;

		// Delete mappings
		ModelFile::deleteByParent( $model->id, CoreGlobal::TYPE_GALLERY );

		// Delete Items
		foreach ( $items as $item ) {

			$this->fileService->delete( $item );
		}

		// Delete model
		return parent::delete( $model, $config );
	}

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// GalleryService ------------------------

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

?>