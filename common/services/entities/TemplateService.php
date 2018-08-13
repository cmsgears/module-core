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

use cmsgears\core\common\services\interfaces\entities\ITemplateService;

use cmsgears\core\common\services\base\EntityService;

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
class TemplateService extends EntityService implements ITemplateService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\entities\Template';

	public static $typed		= true;

	public static $parentType	= CoreGlobal::TYPE_TEMPLATE;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
	use GridCacheTrait;
	use MultiSiteTrait;
	use NameTypeTrait;
	use SlugTypeTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TemplateService -----------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

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
			'defaultOrder' => [
				'id' => SORT_DESC
			]
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
		if( isset( $type ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
		}

		// Filter - Model
		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'active': {

					$config[ 'conditions' ][ "$modelTable.active" ] = true;

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

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'name' => "$modelTable.name",
				'title' => "$modelTable.title",
				'desc' => "$modelTable.description",
				'content' => "$modelTable.content"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name",
			'type' => "$modelTable.type",
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'active' => "$modelTable.active",
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

	public function getGlobalBySlugType( $slug, $type, $config = [] ) {

		$modelClass = static::$modelClass;

		return $modelClass::findGlobalBySlugType( $slug, $type, $config );
	}

	public function getActiveByType( $type ) {

		$modelClass = static::$modelClass;

		return $modelClass::findActiveByType( $type );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'name', 'slug', 'icon', 'title', 'description', 'renderer', 'fileRender',
			'layout', 'layoutGroup', 'viewPath', 'view', 'htmlOptions', 'help', 'content',
			'dataPath', 'dataForm' , 'attributesPath', 'attributesForm', 'configPath' , 'configForm', 'settingsPath', 'settingsForm'
		];

		if( $admin ) {

			$attributes[] = 'active';
		}

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

		$global = $model->layoutGroup ? false : true;

		$model->layoutGroup	= $global;

		return parent::updateSelective( $model, [
			'attributes' => [ 'layoutGroup' ]
		]);
 	}

	// Delete -------------

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
