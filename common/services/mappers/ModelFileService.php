<?php
namespace cmsgears\core\common\services\mappers;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\mappers\ModelFile;

use cmsgears\core\common\services\interfaces\resources\IFileService;
use cmsgears\core\common\services\interfaces\mappers\IModelFileService;

use cmsgears\core\common\services\traits\MapperTrait;

/**
 * The class ModelFileService is base class to perform database activities for ModelFile Entity.
 */
class ModelFileService extends \cmsgears\core\common\services\base\EntityService implements IModelFileService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\mappers\ModelFile';

	public static $modelTable	= CoreTables::TABLE_MODEL_FILE;

	public static $parentType	= null;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $fileService;

	// Traits ------------------------------------------------------

	use MapperTrait;

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

	// ModelFileService ----------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByFileTitle( $parentId, $parentType, $fileTitle ) {

		return ModelFile::findByFileTitle( $parentId, $parentType, $fileTitle );
	}

	public function getByFileTitleLike( $parentId, $parentType, $likeTitle ) {

		return ModelFile::findByFileTitleLike( $parentId, $parentType, $likeTitle );
	}

	public function getByFileType( $parentId, $parentType, $fileType ) {

		return ModelFile::findByFileType( $parentId, $parentType, $fileType );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createOrUpdateByTitle( $file, $config = [] ) {

		$parent		= $config[ 'parent' ];
		$parentType = $config[ 'parentType' ];

		if( isset( $file ) && isset( $file->title ) ) {

			$fileModel	= $this->getByFileTitle( $parent->id, $parentType, $file->title );

			if( isset( $fileModel ) ) {

				$file->id	= $fileModel->file->id;

				$this->fileService->saveFile( $file, [ 'model' => $fileModel, 'attribute' => 'modelId' ] );

				$fileModel->update();
			}
			else {

				$fileModel				= new ModelFile();

				$fileModel->parentId	= $parent->id;
				$fileModel->parentType	= $parentType;

				$this->fileService->saveFile( $file, [ 'model' => $fileModel, 'attribute' => 'modelId' ] );

				$fileModel->save();
			}

			return $fileModel;
		}
	}

	// Update -------------

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelFileService ----------------------

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
