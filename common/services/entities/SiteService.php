<?php
namespace cmsgears\core\common\services\entities;

// Yii Imports
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\Site;

use cmsgears\core\common\services\interfaces\entities\ISiteService;
use cmsgears\core\common\services\interfaces\resources\IFileService;
use cmsgears\core\common\services\interfaces\resources\ISiteMetaService;

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
	private $siteMetaService;

	// Traits ------------------------------------------------------

	use NameTrait;
	use SlugTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( IFileService $fileService, ISiteMetaService $siteMetaService, $config = [] ) {

		$this->fileService		= $fileService;
		$this->siteMetaService	= $siteMetaService;

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

		$modelClass		= static::$modelClass;
		$modelTable		= static::$modelTable;

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'name'
				],
				'slug' => [
					'asc' => [ "$modelTable.slug" => SORT_ASC ],
					'desc' => [ "$modelTable.slug" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'slug'
				],
				'order' => [
					'asc' => [ 'order' => SORT_ASC ],
					'desc' => ['order' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Order'
				],
				'active' => [
					'asc' => [ 'active' => SORT_ASC ],
					'desc' => ['active' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Active'
				]
			]
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'hasOne' ] = true;
		}

		// Filters ----------

		// Filter - Status
		$status	= Yii::$app->request->getQueryParam( 'status' );

		if( isset( $status ) && $status === 'active' ) {

			$config[ 'conditions' ][ "$modelTable.active" ]	= true;
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [ 'name' => "$modelTable.name" ];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name",
			'active' => "$modelTable.active"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	/**
	 * @param string $slug
	 * @return array - An associative array of site meta for the given site slug having id as key and model as value.
	 */
	public function getIdMetaMapBySlug( $slug ) {

		$site = Site::findBySlug( $slug );

		return $this->siteMetaService->getIdMetaMapByModelId( $site->id );
	}

	/**
	 * @param string $name
	 * @param string $type
	 * @return array - An associative array of site meta for the given site slug and meta type having name as key and value as meta.
	 */
	public function getMetaMapBySlugType( $slug, $type ) {

		$site = Site::findBySlug( $slug );

		return $this->siteMetaService->getNameMetaMapByType( $site->id, $type );
	}

	/**
	 * @param string $slug
	 * @param string $type
	 * @return array - An associative array of site meta for the given site slug and meta type having name as key and value as value.
	 */
	public function getMetaNameValueMapBySlugType( $slug, $type ) {

		$site = Site::findBySlug( $slug );

		return $this->siteMetaService->getNameValueMapByType( $site->id, $type );
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

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'avatarId', 'bannerId', 'themeId', 'name', 'order', 'active' ];
		$avatar		= isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner		= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;

		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar, 'bannerId' => $banner ] );

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'active': {

						$model->active = true;

						$model->update();

						break;
					}
					case 'block': {

						$model->active = false;

						$model->update();

						break;
					}
				}

				break;
			}
			case 'model': {

				switch( $action ) {

					case 'delete': {

						$this->delete( $model );

						break;
					}
				}

				break;
			}
		}
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
