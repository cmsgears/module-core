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

use cmsgears\core\common\services\interfaces\entities\IThemeService;

use cmsgears\core\common\services\traits\base\NameTypeTrait;
use cmsgears\core\common\services\traits\base\SlugTypeTrait;
use cmsgears\core\common\services\traits\cache\GridCacheTrait;
use cmsgears\core\common\services\traits\resources\DataTrait;

/**
 * ThemeService provide service methods of theme model.
 *
 * @since 1.0.0
 */
class ThemeService extends \cmsgears\core\common\services\base\EntityService implements IThemeService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\entities\Theme';

	public static $typed = true;

	public static $parentType = CoreGlobal::TYPE_THEME;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
	use GridCacheTrait;
	use NameTypeTrait;
	use SlugTypeTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ThemeService --------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
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
	            'title' => [
	                'asc' => [ "$modelTable.title" => SORT_ASC ],
	                'desc' => [ "$modelTable.title" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => "Title"
	            ],
	            'default' => [
	                'asc' => [ "$modelTable.default" => SORT_ASC ],
	                'desc' => [ "$modelTable.default" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => "Default"
	            ],
	            'renderer' => [
	                'asc' => [ "$modelTable.renderer" => SORT_ASC ],
	                'desc' => [ "$modelTable.renderer" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => "Renderer"
	            ],
	            'base' => [
	                'asc' => [ "$modelTable.basePath" => SORT_ASC ],
	                'desc' => [ "$modelTable.basePath" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => "Base Path"
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

			$config[ 'hasOne' ] = true;
		}

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

				case 'default': {

					$config[ 'conditions' ][ "$modelTable.default" ] = true;

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
			'content' => "$modelTable.content",
			'default' => "$modelTable.default",
			'renderer' => "$modelTable.renderer"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		// Uncheck default for all other themes
		if( $model->default ) {

			$modelClass	= static::$modelClass;

			$modelClass::updateAll( [ 'default' => false ], '`default`=1' );
		}

		return parent::create( $model );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'name', 'slug', 'title', 'description', 'basePath', 'renderer'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [
				'type', 'default'
			]);

			// Uncheck default for all other themes
			if( $model->default ) {

				$modelClass	= static::$modelClass;

				$modelClass::updateAll( [ 'default' => false ], '`default`=1' );
			}
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	/**
	 * Make the default theme available for all sites in case no theme is selected.
	 */
	public function makeDefault( $model, $config = [] ) {

		$modelClass	= static::$modelClass;

		$type = CoreGlobal::TYPE_SITE;

		if( $model->type !== $type ) {

			return false;
		}

		// Disable All
		$modelClass::updateAll( [ 'default' => false ], "`default`=1 AND `type`='$type'" );

		// Make Default
		$model->default = true;

		// Update
		return parent::update( $model, [
			'attributes' => [ 'default' ]
		]);
	}

	// Delete -------------

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

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

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ThemeService --------------------------

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
