<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\entities;

// Yii Imports
use Yii;
use yii\data\Sort;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\mappers\ModelObject;

use cmsgears\core\common\services\interfaces\entities\IObjectService;
use cmsgears\core\common\services\interfaces\resources\IFileService;
use cmsgears\core\common\services\interfaces\mappers\IModelFileService;

use cmsgears\core\common\services\traits\base\ApprovalTrait;
use cmsgears\core\common\services\traits\base\FeaturedTrait;
use cmsgears\core\common\services\traits\base\MultiSiteTrait;
use cmsgears\core\common\services\traits\base\NameTypeTrait;
use cmsgears\core\common\services\traits\base\SlugTypeTrait;
use cmsgears\core\common\services\traits\resources\DataTrait;
use cmsgears\core\common\services\traits\resources\VisualTrait;

/**
 * ObjectDataService provide service methods of object model.
 *
 * @since 1.0.0
 */
class ObjectDataService extends \cmsgears\core\common\services\base\EntityService implements IObjectService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\entities\ObjectData';

	public static $typed = true;

	public static $parentType = CoreGlobal::TYPE_OBJECT;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $fileService;
	protected $modelFileService;

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
	use FeaturedTrait;
	use MultiSiteTrait;
	use NameTypeTrait;
	use SlugTypeTrait;
	use VisualTrait;

	use ApprovalTrait {

		getPageByOwnerId as baseGetPageByOwnerId;
	}

	// Constructor and Initialisation ------------------------------

	public function __construct( IFileService $fileService, IModelFileService $modelFileService ,$config = [] ) {

		$this->fileService = $fileService;

		$this->modelFileService = $modelFileService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ObjectDataService ---------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$site	= Yii::$app->core->site;
		$theme	= $site->theme;

		$templateTable = Yii::$app->factory->get( 'templateService' )->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'template' => [
					'asc' => [ "$templateTable.name" => SORT_ASC ],
					'desc' => [ "$templateTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Template',
				],
				'parent' => [
					'asc' => [ "$templateTable.parentId" => SORT_ASC ],
					'desc' => [ "$templateTable.parentId" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Parent',
				],
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'slug' => [
					'asc' => [ "$modelTable.slug" => SORT_ASC ],
					'desc' => [ "$modelTable.slug" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Slug'
				],
	            'type' => [
	                'asc' => [ "$modelTable.type" => SORT_ASC ],
	                'desc' => [ "$modelTable.type" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Type'
	            ],
	            'icon' => [
	                'asc' => [ "$modelTable.icon" => SORT_ASC ],
	                'desc' => [ "$modelTable.icon" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Icon'
	            ],
	            'texture' => [
	                'asc' => [ "$modelTable.texture" => SORT_ASC ],
	                'desc' => [ "$modelTable.texture" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Texture'
	            ],
				'title' => [
					'asc' => [ "$modelTable.title" => SORT_ASC ],
					'desc' => [ "$modelTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'status' => [
					'asc' => [ "$modelTable.status" => SORT_ASC ],
					'desc' => [ "$modelTable.status" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Status'
				],
				'visibility' => [
					'asc' => [ "$modelTable.visibility" => SORT_ASC ],
					'desc' => [ "$modelTable.visibility" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Visibility'
				],
				'order' => [
					'asc' => [ "$modelTable.order" => SORT_ASC ],
					'desc' => [ "$modelTable.order" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Order'
				],
				'pinned' => [
					'asc' => [ "$modelTable.pinned" => SORT_ASC ],
					'desc' => [ "$modelTable.pinned" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Pinned'
				],
				'featured' => [
					'asc' => [ "$modelTable.featured" => SORT_ASC ],
					'desc' => [ "$modelTable.featured" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Featured'
				],
				'admin' => [
					'asc' => [ "$modelTable.admin" => SORT_ASC ],
					'desc' => [ "$modelTable.admin" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Admin'
				],
				'shared' => [
					'asc' => [ "$modelTable.shared" => SORT_ASC ],
					'desc' => [ "$modelTable.shared" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Shared'
				],
				'cdate' => [
					'asc' => [ "$modelTable.createdAt" => SORT_ASC ],
					'desc' => [ "$modelTable.createdAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Created At'
				],
				'udate' => [
					'asc' => [ "$modelTable.modifiedAt" => SORT_ASC ],
					'desc' => [ "$modelTable.modifiedAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Updated At'
				]
			],
			'defaultOrder' => $defaultSort
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'query' ] = $modelClass::queryWithHasOne( [ 'ignoreSite' => true ] );
		}

		$config[ 'ignoreSite' ]		= true;
		$config[ 'conditions' ][]	= isset( $theme ) ? "$modelTable.themeId={$theme->id} OR $modelTable.siteId={$site->id} OR ($modelTable.themeId IS NULL AND $modelTable.siteId IS NULL)" : "$modelTable.siteId={$site->id} OR ($modelTable.themeId IS NULL AND $modelTable.siteId IS NULL)";

		// Filters ----------

		// Params
		$type	= Yii::$app->request->getQueryParam( 'type' );
		$status	= Yii::$app->request->getQueryParam( 'status' );
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Type
		if( isset( $type ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
		}

		// Filter - Status
		if( isset( $status ) && isset( $modelClass::$urlRevStatusMap[ $status ] ) ) {

			$config[ 'conditions' ][ "$modelTable.status" ]	= $modelClass::$urlRevStatusMap[ $status ];
		}

		// Filter - Model
		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'pinned': {

					$config[ 'conditions' ][ "$modelTable.pinned" ] = true;

					break;
				}
				case 'featured': {

					$config[ 'conditions' ][ "$modelTable.featured" ] = true;

					break;
				}
				case 'admin': {

					$config[ 'conditions' ][ "$modelTable.admin" ] = true;

					break;
				}
				case 'shared': {

					$config[ 'conditions' ][ "$modelTable.shared" ] = true;

					break;
				}
			}
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'name' => "$modelTable.name",
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name",
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content",
			'status' => "$modelTable.status",
			'visibility' => "$modelTable.visibility",
			'order' => "$modelTable.order",
			'pinned' => "$modelTable.pinned",
			'featured' => "$modelTable.featured"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	/**
	 * Returns the child objects.
	 */
	public function getPageByParentId( $parentId, $config = [] ) {

		$modelTable = $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.parentId" ] = $parentId;

		return $this->getPage( $config );
	}

	/**
	 * Returns the collection made by the user.
	 */
	public function getPageByOwnerId( $ownerId, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.admin" ] = false;
		$config[ 'conditions' ][ "$modelTable.shared" ] = true;

		return $this->baseGetPageByOwnerId( $ownerId, $config );
	}

	/**
	 * Returns the collection made for the parent i.e. directly mapped models.
	 */
	public function getPageByTypeParent( $type, $parentId, $parentType, $config = [] ) {

		$admin	= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false; // Returns frontend mappings
		$shared	= isset( $config[ 'shared' ] ) ? $config[ 'shared' ] : false; // Returns direct mappings

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$modelObjectTable = ModelObject::tableName();

		$query = $modelClass::queryWithHasOne( [ 'ignoreSite' => true ] );

		$query->leftJoin( $modelObjectTable, "$modelObjectTable.modelId=$modelTable.id" );
		$query->where( "$modelObjectTable.parentId=$parentId AND $modelObjectTable.parentType='$parentType' AND $modelObjectTable.type='$type'" );

		$config[ 'conditions' ][ "$modelTable.type" ]	= $type;
		$config[ 'conditions' ][ "$modelTable.admin" ]	= $admin;
		$config[ 'conditions' ][ "$modelTable.shared" ]	= $shared;

		$config[ 'query' ] = $query;

		return $this->getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getByName( $name ) {

		return $this->getByNameType( $name, static::$parentType );
	}

	public function getFirstByName( $name ) {

		return $this->getFirstByNameType( $name, static::$parentType );
	}

	public function getFeatured( $config = [] ) {

		$modelClass	= static::$modelClass;

		return $modelClass::find()->where( 'featured=:featured AND type=:type', [ ':featured' => true, ':type' => static::$parentType ] )->all();
	}

	public function getActive( $config = [] ) {

		$modelClass = static::$modelClass;

		return $modelClass::queryByType( static::$parentType, $config )->andWhere( [ 'status' => $modelClass::STATUS_ACTIVE ] )->all();
	}

	public function getActiveByType( $type, $config = [] ) {

		$modelClass = static::$modelClass;

		return $modelClass::queryByType( $type, $config )->andWhere( [ 'status' => $modelClass::STATUS_ACTIVE ] )->all();
	}

	public static function getL0ByType( $type, $config = [] ) {

		$modelClass = static::$modelClass;

		return $modelClass::queryL0ByType( $type, $config )->andWhere( [ 'status' => $modelClass::STATUS_ACTIVE ] )->all();
	}

	public static function getByParentId( $parentId, $config = [] ) {

		$modelClass = static::$modelClass;

		return $modelClass::queryByParentId( $parentId, $config )->andWhere( [ 'status' => $modelClass::STATUS_ACTIVE ] )->all();
	}

	// Read - Lists ----

	public function getIdList( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.type" ] = static::$parentType;

		return parent::getIdList( $config );
	}

	public function getIdNameList( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.type" ] = static::$parentType;

		return parent::getIdNameList( $config );
	}

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$modelClass	= static::$modelClass;

		$avatar	= isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner	= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$video 	= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;

		if( !isset( $model->visibility ) ) {

			$model->visibility = $modelClass::VISIBILITY_PRIVATE;
		}

		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar, 'bannerId' => $banner, 'videoId' => $video ] );

		return parent::create( $model, $config );
	}

	public function add( $model, $config = [] ) {

		$this->register( $model, $config );
	}

	public function register( $model, $config = [] ) {

		$modelClass = static::$modelClass;

		$gallery	= isset( $config[ 'gallery' ] ) ? $config[ 'gallery' ] : null;
		$addGallery	= isset( $config[ 'addGallery' ] ) ? $config[ 'addGallery' ] : false;

		$galleryService = Yii::$app->factory->get( 'galleryService' );

		$galleryClass = $galleryService->getModelClass();

		$transaction = Yii::$app->db->beginTransaction();

		try {

			// Copy Template
			$config[ 'template' ] = $model->template;

			$this->copyTemplate( $model, $config );

			// Create Model
			$model = $this->create( $model, $config );

			// Refresh Model
			$model->Refresh();

			// Create Gallery
			if( isset( $gallery ) ) {

				$gallery->type		= static::$parentType;
				$gallery->status	= $galleryClass::STATUS_ACTIVE;
				$gallery->siteId	= Yii::$app->core->siteId;

				$gallery = $galleryService->create( $gallery );
			}
			else if( $addGallery ) {

				$gallery = $galleryService->createByParams([
					'type' => static::$parentType, 'status' => $galleryClass::STATUS_ACTIVE,
					'name' => $model->name, 'title' => $model->title,
					'siteId' => Yii::$app->core->siteId
				]);
			}

			// Attach gallery
			if( isset( $gallery ) ) {

				$model->galleryId = $gallery->id;

				$model->update();
			}

			$transaction->commit();
		}
		catch( Exception $e ) {

			$transaction->rollBack();

			return false;
		}

		return $model;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$avatar 	= isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner 	= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$video 		= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;
		$gallery	= isset( $config[ 'gallery' ] ) ? $config[ 'gallery' ] : null;

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'templateId', 'parentId', 'avatarId', 'bannerId', 'videoId', 'galleryId',
			'name', 'slug', 'title', 'icon', 'texture', 'description', 'visibility',
			'htmlOptions', 'summary', 'content'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [
				'status', 'order', 'pinned', 'featured'
			]);
		}

		// Copy Template
		$config[ 'template' ] = $model->template;

		if( $this->copyTemplate( $model, $config ) ) {

			$attributes[] = 'data';
		}

		// Save Files
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar, 'bannerId' => $banner, 'videoId' => $video ] );

		// Create/Update gallery
		if( isset( $gallery ) ) {

			$galleryService = Yii::$app->factory->get( 'galleryService' );

			$gallery = $galleryService->createOrUpdate( $gallery );

			if( empty( $model->galleryId ) ) {

				$model->galleryId = $gallery->id;
			}
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete files
		$this->fileService->deleteMultiple( [ $model->avatar, $model->banner, $model->video ] );
		$this->fileService->deleteMultiple( $model->files );

		// Delete File Mappings - Shared Files
		$this->modelFileService->deleteMultiple( $model->modelFiles );

		// Delete mapping
		Yii::$app->factory->get( 'modelObjectService' )->deleteByModelId( $model->id );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		$direct = isset( $config[ 'direct' ] ) ? $config[ 'direct' ] : true; // Trigger direct notifications
		$users	= isset( $config[ 'users' ] ) ? $config[ 'users' ] : []; // Trigger user notifications

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'confirm': {

						$this->confirm( $model, [ 'direct' => $direct, 'users' => $users ] );

						break;
					}
					case 'approve': {

						$this->approve( $model, [ 'direct' => $direct, 'users' => $users ] );

						break;
					}
					case 'reject': {

						$this->reject( $model, [ 'direct' => $direct, 'users' => $users ] );

						break;
					}
					case 'activate': {

						$this->activate( $model, [ 'direct' => $direct, 'users' => $users ] );

						break;
					}
					case 'freeze': {

						$this->freeze( $model, [ 'direct' => $direct, 'users' => $users ] );

						break;
					}
					case 'block': {

						$this->block( $model, [ 'direct' => $direct, 'users' => $users ] );

						break;
					}
				}

				break;
			}
			case 'model': {

				switch( $action ) {

					case 'pinned': {

						$model->pinned = true;

						$model->update();

						break;
					}
					case 'featured': {

						$model->featured = true;

						$model->update();

						break;
					}
					case 'delete': {

						$this->delete( $model );

						break;
					}
				}

				break;
			}
		}
	}

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ObjectDataService ---------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	public static function generateNameValueList( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable = $modelClass::tableName();

		$site	= Yii::$app->core->site;
		$theme	= $site->theme;

		$config[ 'ignoreSite' ] = true;

		$config[ 'conditions' ][] = isset( $theme ) ? "$modelTable.themeId={$theme->id} OR $modelTable.siteId={$site->id} OR ($modelTable.themeId IS NULL AND $modelTable.siteId IS NULL)" : "$modelTable.siteId={$site->id} OR ($modelTable.themeId IS NULL AND $modelTable.siteId IS NULL)";

		return parent::generateNameValueList( $config );
	}

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
