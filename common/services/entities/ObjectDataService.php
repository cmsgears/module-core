<?php
namespace cmsgears\core\common\services\entities;

use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\ObjectData;
use cmsgears\core\common\models\mappers\ModelObject;

use cmsgears\core\common\services\interfaces\entities\IObjectService;
use cmsgears\core\common\services\interfaces\resources\IFileService;

use cmsgears\core\common\services\traits\NameTypeTrait;
use cmsgears\core\common\services\traits\SlugTypeTrait;

class ObjectDataService extends \cmsgears\core\common\services\base\EntityService implements IObjectService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\entities\ObjectData';

	public static $modelTable	= CoreTables::TABLE_OBJECT_DATA;

	public static $parentType	= CoreGlobal::TYPE_OBJECT;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $fileService;

	// Traits ------------------------------------------------------

	use NameTypeTrait;
	use SlugTypeTrait;

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

	// CountryService ------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

	    $sort = new Sort([
	        'attributes' => [
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
	            ]
	        ]
	    ]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		return parent::findPage( $config );
	}

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$data 	= isset( $config[ 'data' ] ) ? $config[ 'data' ] : null;
		$avatar = isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner = isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;

		// Generate Data
		if( isset( $data ) ) {

			$model->generateJsonFromObject( $data );
		}

		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar, 'bannerId' => $banner ] );

		return parent::create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'templateId', 'avatarId', 'name', 'icon', 'description', 'type', 'active', 'htmlOptions', 'data' ];
		$data 		= isset( $config[ 'data' ] ) ? $config[ 'data' ] : null;
		$avatar 	= isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner 	= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;

		// Generate Data
		if( isset( $data ) ) {

			$model->generateJsonFromObject( $data );
		}

		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar, 'bannerId' => $banner ] );

		return parent::update( $model, [
			'attributes' => $attributes
		]);
 	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete files
		$this->fileService->deleteFiles( [ $model->avatar, $model->banner ] );

		// Delete mapping
		ModelObject::deleteByModelId( $model->id );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// CountryService ------------------------

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
