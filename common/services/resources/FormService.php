<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\resources;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\interfaces\resources\IFormService;

use cmsgears\core\common\services\traits\base\MultiSiteTrait;
use cmsgears\core\common\services\traits\base\NameTypeTrait;
use cmsgears\core\common\services\traits\base\SlugTypeTrait;
use cmsgears\core\common\services\traits\cache\GridCacheTrait;
use cmsgears\core\common\services\traits\resources\DataTrait;

/**
 * FormService provide service methods of form model.
 *
 * @since 1.0.0
 */
class FormService extends \cmsgears\core\common\services\base\ResourceService implements IFormService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\resources\Form';

	public static $typed = true;

	public static $parentType = CoreGlobal::TYPE_FORM;

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

	// FormService ---------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

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
	                'label' => 'Template'
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
				'title' => [
					'asc' => [ "$modelTable.title" => SORT_ASC ],
					'desc' => [ "$modelTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'captcha' => [
					'asc' => [ "$modelTable.captcha" => SORT_ASC ],
					'desc' => [ "$modelTable.captcha" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Captcha'
				],
				'visibility' => [
					'asc' => [ "$modelTable.visibility" => SORT_ASC ],
					'desc' => [ "$modelTable.visibility" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Visibility'
				],
				'status' => [
					'asc' => [ "$modelTable.status" => SORT_ASC ],
					'desc' => [ "$modelTable.status" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Status'
				],
				'umail' => [
					'asc' => [ "$modelTable.userMail" => SORT_ASC ],
					'desc' => [ "$modelTable.userMail" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'User Mail'
				],
				'amail' => [
					'asc' => [ "$modelTable.adminMail" => SORT_ASC ],
					'desc' => [ "$modelTable.adminMail" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Admin Mail'
				],
				'unsubmit' => [
					'asc' => [ "$modelTable.uniqueSubmit" => SORT_ASC ],
					'desc' => [ "$modelTable.uniqueSubmit" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Unique Submit'
				],
				'upsubmit' => [
					'asc' => [ "$modelTable.updateSubmit" => SORT_ASC ],
					'desc' => [ "$modelTable.updateSubmit" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Update Submit'
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

				case 'captcha': {

					$config[ 'conditions' ][ "$modelTable.captcha" ] = true;

					break;
				}
				case 'umail': {

					$config[ 'conditions' ][ "$modelTable.userMail" ] = true;

					break;
				}
				case 'amail': {

					$config[ 'conditions' ][ "$modelTable.adminMail" ] = true;

					break;
				}
				case 'unsubmit': {

					$config[ 'conditions' ][ "$modelTable.uniqueSubmit" ] = true;

					break;
				}
				case 'upsubmit': {

					$config[ 'conditions' ][ "$modelTable.updateSubmit" ] = true;

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
				'success' => "$modelTable.description",
				'failure' => "$modelTable.description",
				'content' => "$modelTable.content"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name",
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'success' => "$modelTable.success",
			'failure' => "$modelTable.failure",
			'content' => "$modelTable.content",
			'captcha' => "$modelTable.captcha",
			'status' => "$modelTable.status",
			'visibility' => "$modelTable.visibility",
			'umail' => "$modelTable.userMail",
			'amail' => "$modelTable.adminMail",
			'unsubmit' => "$modelTable.uniqueSubmit",
			'upsubmit' => "$modelTable.updateSubmit"
		];

		// Result -----------

		return parent::findPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		// Copy Template
		$config[ 'template' ] = $model->template;

		$this->copyTemplate( $model, $config );

		return parent::create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'templateId', 'name', 'slug', 'icon', 'texture', 'title', 'description',
			'success', 'failure', 'captcha', 'visibility', 'status',
			'mailTo', 'userMail', 'adminMail', 'uniqueSubmit', 'updateSubmit',
			'htmlOptions', 'content'
		];

		if( $admin ) {

			$attributes[] = 'status';
		}

		// Copy Template
		$config[ 'template' ] = $model->template;

		if( $this->copyTemplate( $model, $config ) ) {

			$attributes[] = 'data';
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete mappings
		Yii::$app->factory->get( 'modelFormService' )->deleteByModelId( $model->id );

		// Delete Fields
		Yii::$app->factory->get( 'formFieldService' )->deleteByFormId( $model->id );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'confirm': {

						$this->confirm( $model );

						break;
					}
					case 'approve': {

						$this->approve( $model );

						break;
					}
					case 'reject': {

						$this->reject( $model );

						break;
					}
					case 'activate': {

						$this->activate( $model );

						break;
					}
					case 'freeze': {

						$this->freeze( $model );

						break;
					}
					case 'block': {

						$this->block( $model );

						break;
					}
				}

				break;
			}
			case 'model': {

				switch( $action ) {

					case 'captcha': {

						$model->captcha = true;

						$model->update();

						break;
					}
					case 'umail': {

						$model->umail = true;

						$model->update();

						break;
					}
					case 'amail': {

						$model->amail = true;

						$model->update();

						break;
					}
					case 'unsubmit': {

						$model->unsubmit = true;

						$model->update();

						break;
					}
					case 'upsubmit': {

						$model->upsubmit = true;

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

	// FormService ---------------------------

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
