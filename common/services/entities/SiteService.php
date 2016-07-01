<?php
namespace cmsgears\core\common\services\entities;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\Theme;

use cmsgears\core\common\services\interfaces\entities\ISiteService;
use cmsgears\core\common\services\interfaces\resources\IFileService;
use cmsgears\core\common\services\interfaces\resources\ISiteAttributeService;

use cmsgears\core\common\services\traits\NameTrait;
use cmsgears\core\common\services\traits\SlugTrait;

/**
 * The class SiteService is base class to perform database activities for Site Entity.
 */
class SiteService extends \cmsgears\core\common\services\base\EntityService implements ISiteService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\entities\Site';

	public static $modelTable	= CoreTables::TABLE_SITE;

	public static $parentType	= CoreGlobal::TYPE_SITE;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $fileService;
	private $siteAttributeService;

	// Traits ------------------------------------------------------

	use NameTrait;
	use SlugTrait;

	// Constructor and Initialisation ------------------------------

    public function __construct( IFileService $fileService, ISiteAttributeService $siteAttributeService, $config = [] ) {

		$this->fileService 				= $fileService;
		$this->siteAttributeService 	= $siteAttributeService;

        parent::__construct( $config );
    }

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SiteService ---------------------------

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

		$config[ 'sort' ] = $sort;

		return parent::findPage( $config );
	}

	// Read ---------------

    // Read - Models ---

	/**
	 * @param string $name
	 * @param string $type
	 * @return array - An associative array of site attribute for the given site slug and meta type having name as key and value as attribute.
	 */
    public function getAttributeMapBySlugType( $slug, $type ) {

		$site = Site::findBySlug( $slug );

		return $this->siteAttributeService->getObjectMapByType( $type );
    }

	/**
	 * @param string $slug
	 * @param string $type
	 * @return array - An associative array of site attribute for the given site slug and meta type having name as key and value as value.
	 */
    public function getAttributeNameValueMapBySlugType( $slug, $type ) {

		$site = Site::findBySlug( $slug );

		return $this->siteAttributeService->getNameValueMapByType( $site->id, $type );
    }

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$avatar = isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner = isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;

		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar, 'bannerId' => $banner ] );

		return parent::create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$avatar = isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner = isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;

		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar, 'bannerId' => $banner ] );

		return parent::update( $model, [
			'attributes' => [ 'avatarId', 'bannerId', 'themeId', 'name', 'order', 'active' ]
		]);
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete dependencies
		$this->fileService->deleteFiles( [ $model->avatar, $model->banner ] );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// SiteService ---------------------------

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