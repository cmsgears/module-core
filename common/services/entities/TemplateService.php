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
	            'icon' => [
	                'asc' => [ "$modelTable.icon" => SORT_ASC ],
	                'desc' => [ "$modelTable.icon" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Icon'
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

			$search = [
				'name' => "$modelTable.name",
				'desc' => "$modelTable.description",
				'content' => "$modelTable.content"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content",
			'file' => "$modelTable.fileRender",
			'layout' => "$modelTable.layoutGroup"
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

		$global = $model->fileRender ? false : true;

		$model->fileRender = $global;

		return parent::updateSelective( $model, [
			'attributes' => [ 'fileRender' ]
		]);
 	}

	public function switchGroupLayout( $model, $config = [] ) {

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
