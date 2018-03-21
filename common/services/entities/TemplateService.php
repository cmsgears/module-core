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

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\services\interfaces\entities\ITemplateService;

use cmsgears\core\common\services\base\EntityService;

use cmsgears\core\common\services\traits\base\NameTypeTrait;
use cmsgears\core\common\services\traits\base\SlugTypeTrait;
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

	public static $modelTable	= CoreTables::TABLE_TEMPLATE;

	public static $typed		= true;

	public static $parentType	= CoreGlobal::TYPE_TEMPLATE;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
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

		$modelClass		= static::$modelClass;
		$modelTable		= static::$modelTable;

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ 'id' => SORT_ASC ],
					'desc' => [ 'id' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Name'
	            ],
	            'slug' => [
	                'asc' => [ 'slug' => SORT_ASC ],
	                'desc' => ['slug' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Slug'
	            ],
	            'type' => [
	                'asc' => [ 'type' => SORT_ASC ],
	                'desc' => ['type' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Type'
	            ],
	            'icon' => [
	                'asc' => [ 'icon' => SORT_ASC ],
	                'desc' => ['icon' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Icon'
	            ],
	            'renderer' => [
	                'asc' => [ 'renderer' => SORT_ASC ],
	                'desc' => ['renderer' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Renderer'
	            ],
	            'frender' => [
	                'asc' => [ 'fileRender' => SORT_ASC ],
	                'desc' => ['fileRender' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'File Render'
	            ],
	            'layout' => [
	                'asc' => [ 'layout' => SORT_ASC ],
	                'desc' => ['layout' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Layout'
	            ],
	            'lgroup' => [
	                'asc' => [ 'layoutGroup' => SORT_ASC ],
	                'desc' => ['layoutGroup' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Layout Group'
	            ],
	            'vpath' => [
	                'asc' => [ 'viewPath' => SORT_ASC ],
	                'desc' => ['viewPath' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'View Path'
	            ],
	            'cdate' => [
	                'asc' => [ 'createdAt' => SORT_ASC ],
	                'desc' => ['createdAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Created At'
	            ],
	            'udate' => [
	                'asc' => [ 'modifiedAt' => SORT_ASC ],
	                'desc' => ['modifiedAt' => SORT_DESC ],
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

		// Filters ----------

		// Filter - Status
		$status	= Yii::$app->request->getQueryParam( 'status' );

		if( isset( $status ) ) {

			switch( $status ) {

				case 'file': {

					$config[ 'conditions' ][ "$modelTable.fileRender" ]	= true;

					break;
				}
				case 'layout': {

					$config[ 'conditions' ][ "$modelTable.layoutGroup" ]	= true;

					break;
				}
			}
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [ 'name' => "$modelTable.name", 'desc' => "$modelTable.description", 'content' => "$modelTable.content" ];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name", 'desc' => "$modelTable.description", 'content' => "$modelTable.content",
			'file' => "$modelTable.fileRender", 'layout' => "$modelTable.layoutGroup"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getActiveByType( $type ) {

		$modelClass = static::$modelClass;

		return $modelClass::findActiveByType( $type );
	}

	// Read - Lists ----

	// Read - Maps -----

	public function getIdNameMap( $options = [] ) {

		$map = parent::getIdNameMap( $options );

		if( isset( $options[ 'default' ] ) && $options[ 'default' ] ) {

			unset( $options[ 'default' ] );

			$map = ArrayHelper::merge( [ '0' => 'Choose Template' ], $map );
		}

		return $map;
	}

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'name', 'icon', 'description', 'renderer', 'fileRender', 'layout', 'layoutGroup', 'viewPath', 'content' , 'active' ];

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function switchFileRender( $model, $config = [] ) {

		$global			= $model->fileRender ? false : true;
		$model->fileRender	= $global;

		return parent::updateSelective( $model, [
			'attributes' => [ 'fileRender' ]
		]);
 	}

	public function switchGroupLayout( $model, $config = [] ) {

		$global			= $model->layoutGroup ? false : true;
		$model->layoutGroup	= $global;

		return parent::updateSelective( $model, [
			'attributes' => [ 'layoutGroup' ]
		]);
 	}

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'file': {

						$model->fileRender = true;

						$model->update();

						break;
					}
					case 'cache': {

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

	// Bulk ---------------

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

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
