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

use cmsgears\core\common\services\interfaces\entities\ITemplateService;
use cmsgears\core\common\services\interfaces\resources\IFileService;

use cmsgears\core\common\services\traits\base\MultiSiteTrait;
use cmsgears\core\common\services\traits\base\NameTypeTrait;
use cmsgears\core\common\services\traits\base\SlugTypeTrait;
use cmsgears\core\common\services\traits\cache\GridCacheTrait;
use cmsgears\core\common\services\traits\resources\DataTrait;

/**
 * TemplateService provide service methods of template model.
 *
 * @since 1.0.0
 */
class TemplateService extends \cmsgears\core\common\services\base\EntityService implements ITemplateService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\entities\Template';

	public static $typed = true;

	public static $parentType = CoreGlobal::TYPE_TEMPLATE;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $fileService;

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
	use GridCacheTrait;
	use MultiSiteTrait;
	use NameTypeTrait;
	use SlugTypeTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( IFileService $fileService, $config = [] ) {

		$this->fileService = $fileService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TemplateService -----------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$site	= Yii::$app->core->site;
		$theme	= $site->theme;

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
	                'label' => 'Name'
	            ],
	            'slug' => [
	                'asc' => [ "$modelTable.slug" => SORT_ASC ],
	                'desc' => [ "$modelTable.slug" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => "Slug"
	            ],
	            'type' => [
	                'asc' => [ "$modelTable.type" => SORT_ASC ],
	                'desc' => [ "$modelTable.type" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => "Type"
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
	                'label' => 'Icon'
	            ],
	            'active' => [
	                'asc' => [ "$modelTable.active" => SORT_ASC ],
	                'desc' => [ "$modelTable.active" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Active'
	            ],
				'frontend' => [
	                'asc' => [ "$modelTable.frontend" => SORT_ASC ],
	                'desc' => [ "$modelTable.frontend" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Frontend'
	            ],
	            'renderer' => [
	                'asc' => [ "$modelTable.renderer" => SORT_ASC ],
	                'desc' => [ "$modelTable.renderer" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Renderer'
	            ],
	            'frender' => [
	                'asc' => [  "$modelTable.fileRender" => SORT_ASC ],
	                'desc' => [ "$modelTable.fileRender" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'File Render'
	            ],
	            'layout' => [
	                'asc' => [ "$modelTable.layout" => SORT_ASC ],
	                'desc' => [ "$modelTable.layout" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Layout'
	            ],
	            'lgroup' => [
	                'asc' => [ "$modelTable.layoutGroup" => SORT_ASC ],
	                'desc' => [ "$modelTable.layoutGroup" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Layout Group'
	            ],
	            'vpath' => [
	                'asc' => [ "$modelTable.viewPath" => SORT_ASC ],
	                'desc' => [ "$modelTable.viewPath" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'View Path'
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
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Type
		if( isset( $type ) && empty( $config[ 'conditions' ][ "$modelTable.type" ] ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
		}

		// Filter - Model
		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'active': {

					$config[ 'conditions' ][ "$modelTable.active" ] = true;

					break;
				}
				case 'disabled': {

					$config[ 'conditions' ][ "$modelTable.active" ] = false;

					break;
				}
				case 'frontend': {

					$config[ 'conditions' ][ "$modelTable.frontend" ] = true;

					break;
				}
				case 'frender': {

					$config[ 'conditions' ][ "$modelTable.fileRender" ] = true;

					break;
				}
				case 'lgroup': {

					$config[ 'conditions' ][ "$modelTable.layoutGroup" ] = true;

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

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'name' => "$modelTable.name",
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'active' => "$modelTable.active",
			'frontend' => "$modelTable.frontend",
			'renderer' => "$modelTable.renderer",
			'frender' => "$modelTable.fileRender",
			'layout' => "$modelTable.layout",
			'lgroup' => "$modelTable.layoutGroup",
			'content' => "$modelTable.content",
			'cdate' => "$modelTable.createdAt",
			'udate' => "$modelTable.modifiedAt"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getActiveByType( $type, $config = [] ) {

		$modelClass = static::$modelClass;

		return $modelClass::findActiveByType( $type, $config );
	}

	public function getGlobalBySlugType( $slug, $type, $config = [] ) {

		$modelClass = static::$modelClass;

		return $modelClass::findGlobalBySlugType( $slug, $type, $config );
	}

	public function getByThemeSlugType( $slug, $type, $config = [] ) {

		$modelClass = static::$modelClass;

		return $modelClass::findByThemeSlugType( $slug, $type, $config );
	}

	// Read - Lists ----

	// Read - Maps -----

	public function getFrontendIdNameMapByType( $type, $config = [] ) {

		$config[ 'conditions' ][ 'type' ]		= $type;
		$config[ 'conditions' ][ 'frontend' ]	= true;

		return $this->getIdNameMap( $config );
	}

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$modelClass	= static::$modelClass;

		$preview = isset( $config[ 'preview' ] ) ? $config[ 'preview' ] : null;

		$this->fileService->saveFiles( $model, [ 'previewId' => $preview ] );

		return parent::create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$preview = isset( $config[ 'preview' ] ) ? $config[ 'preview' ] : null;

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'previewId', 'name', 'slug', 'icon', 'title', 'description', 'renderer', 'fileRender',
			'layout', 'layoutGroup', 'viewPath', 'view', 'htmlOptions', 'help', 'message', 'content',
			'classPath', 'dataPath', 'dataForm', 'attributesPath', 'attributesForm',
			'configPath', 'configForm', 'settingsPath', 'settingsForm'
		];

		if( $admin ) {

			$attributes[] = 'active';
			$attributes[] = 'frontend';
		}

		// Save Files
		$this->fileService->saveFiles( $model, [ 'previewId' => $preview ] );

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function toggleActive( $model, $config = [] ) {

		$active = $model->active ? false : true;

		$model->fileRender = $active;

		return parent::updateSelective( $model, [
			'attributes' => [ 'active' ]
		]);
 	}

	public function toggleFileRender( $model, $config = [] ) {

		$global = $model->fileRender ? false : true;

		$model->fileRender = $global;

		return parent::updateSelective( $model, [
			'attributes' => [ 'fileRender' ]
		]);
 	}

	public function toggleGroupLayout( $model, $config = [] ) {

		$group = $model->layoutGroup ? false : true;

		$model->layoutGroup	= $group;

		return parent::updateSelective( $model, [
			'attributes' => [ 'layoutGroup' ]
		]);
 	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete files
		$this->fileService->deleteMultiple( [ $model->preview ] );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'model': {

				switch( $action ) {

					case 'activate': {

						$model->active = true;

						$model->update();

						break;
					}
					case 'disable': {

						$model->active = false;

						$model->update();

						break;
					}
					case 'frontend': {

						$model->frontend = true;

						$model->update();

						break;
					}
					case 'frender': {

						$model->fileRender = true;

						$model->update();

						break;
					}
					case 'crender': {

						$model->fileRender = false;

						$model->update();

						break;
					}
					case 'group': {

						$model->layoutGroup = true;

						$model->update();

						break;
					}
					case 'single': {

						$model->layoutGroup = false;

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

	// TemplateService -----------------------

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
