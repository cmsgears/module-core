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

use cmsgears\core\common\services\interfaces\resources\IFormFieldService;

use cmsgears\core\common\services\base\ResourceService;

use cmsgears\core\common\services\traits\resources\DataTrait;

/**
 * FormFieldService provide service methods of form fields.
 *
 * @since 1.0.0
 */
class FormFieldService extends ResourceService implements IFormFieldService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\resources\FormField';

	public static $parentType	= CoreGlobal::TYPE_FORM_FIELD;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// FormFieldService ----------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$formTable = Yii::$app->factory->get( 'formService' )->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'form' => [
					'asc' => [ "$formTable.name" => SORT_ASC ],
					'desc' => [ "$formTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Form'
				],
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'label' => [
					'asc' => [ "$modelTable.label" => SORT_ASC ],
					'desc' => [ "$modelTable.label" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Label'
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
				'compress' => [
					'asc' => [ "$modelTable.compress" => SORT_ASC ],
					'desc' => [ "$modelTable.compress" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Compress'
				],
				'active' => [
					'asc' => [ "$modelTable.active" => SORT_ASC ],
					'desc' => [ "$modelTable.active" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Active'
				],
				'order' => [
					'asc' => [ "$modelTable.order" => SORT_ASC ],
					'desc' => [ "$modelTable.order" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Order'
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
				case 'inactive': {

					$config[ 'conditions' ][ "$modelTable.active" ] = false;

					break;
				}
				case 'compress': {

					$config[ 'conditions' ][ "$modelTable.compress" ] = true;

					break;
				}
			}
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'name' => "$modelTable.name",
				'label' => "$modelTable.label",
				'validators' => "$modelTable.validators",
				'content' => "$modelTable.content"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name",
			'label' => "$modelTable.label",
			'validators' => "$modelTable.validators",
			'content' => "$modelTable.content",
			'order' => "$modelTable.order",
			'active' => "$modelTable.active",
			'compress' => "$modelTable.compress"
		];

		// Result -----------

		return parent::findPage( $config );
	}

	public function getPageByFormId( $formId ) {

		return $this->getPage( [ 'conditions' => [ 'formId' => $formId ] ] );
	}

	// Read ---------------

	// Read - Models ---

	public function getByFormId( $formId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByFormId( $formId );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'name', 'label', 'type', 'compress', 'validators',
			'order', 'icon', 'htmlOptions', 'content'
		];

		return parent::update( $model, [
			'attributes' => $attributes
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
					case 'compress': {

						$model->comress = true;

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

	// FormFieldService ----------------------

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
