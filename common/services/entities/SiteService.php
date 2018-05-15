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

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\interfaces\entities\ISiteService;
use cmsgears\core\common\services\interfaces\resources\IFileService;
use cmsgears\core\common\services\interfaces\resources\ISiteMetaService;

use cmsgears\core\common\services\base\EntityService;

use cmsgears\core\common\services\traits\base\NameTrait;
use cmsgears\core\common\services\traits\base\SlugTrait;
use cmsgears\core\common\services\traits\resources\DataTrait;
use cmsgears\core\common\services\traits\resources\MetaTrait;

/**
 * SiteService provide service methods of site model.
 *
 * @since 1.0.0
 */
class SiteService extends EntityService implements ISiteService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\entities\Site';

	public static $parentType	= CoreGlobal::TYPE_SITE;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $fileService;
	private $metaService;

	// Traits ------------------------------------------------------

	use DataTrait;
	use MetaTrait;
	use NameTrait;
	use SlugTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( IFileService $fileService, ISiteMetaService $metaService, $config = [] ) {

		$this->fileService	= $fileService;
		$this->metaService	= $metaService;

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

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$themeTable = Yii::$app->factory->get( 'themeService' )->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'theme' => [
					'asc' => [ "$themeTable.name" => SORT_ASC ],
					'desc' => [ "$themeTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
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
				'icon' => [
					'asc' => [ "$modelTable.icon" => SORT_ASC ],
					'desc' => [ "$modelTable.icon" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Icon'
				],
				'title' => [
					'asc' => [ "$modelTable.title" => SORT_ASC ],
					'desc' => [ "$modelTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'order' => [
					'asc' => [ "$modelTable.order" => SORT_ASC ],
					'desc' => [ "$modelTable.order" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Order'
				],
				'active' => [
					'asc' => [ "$modelTable.active" => SORT_ASC ],
					'desc' => [ "$modelTable.active" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Active'
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
			'defaultOrder' => [
				'id' => SORT_DESC
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

		// Params
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Model
		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'active': {

					$config[ 'conditions' ][ "$modelTable.active" ] = true;

					break;
				}
			}
		}

		// Searching --------

		$searchCol = Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'name' => "$modelTable.name",
				'title' => "$modelTable.title",
				'desc' => "$modelTable.description",
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name",
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'order' => "$modelTable.order",
			'active' => "$modelTable.active"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getFeaturedTestimonials( $site ) {

		$commentService	= Yii::$app->factory->get( 'modelCommentService' );

		return $commentService->getFeaturedTestimonials( $site->id, static::$parentType );
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

		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;
		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'avatarId', 'bannerId', 'themeId', 'name', 'slug', 'title', 'description', 'order' ];
		$avatar		= isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner		= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;

		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar, 'bannerId' => $banner ] );

		if( $admin ) {

			$attributes[] = 'active';
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete dependencies
		$this->fileService->deleteFiles( [ $model->avatar, $model->banner ] );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'model': {

				switch( $action ) {

					case 'active': {

						$model->active = true;

						$model->update();

						break;
					}
					case 'inactive': {

						$model->active = false;

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
