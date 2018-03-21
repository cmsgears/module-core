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

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\FormField;

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

	public static $modelTable	= CoreTables::TABLE_FORM_FIELD;

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

		$modelClass		= static::$modelClass;
		$modelTable		= static::$modelTable;

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'name' => [
					'asc' => [ 'name' => SORT_ASC ],
					'desc' => ['name' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'name',
				]
			],
			'defaultOrder' => [
				'name' => SORT_DESC
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

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [ 'name' => "$modelTable.name", 'slug' => "$modelTable.slug", 'template' => "$modelTable.template" ];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name", 'slug' => "$modelTable.slug", 'template' => "$modelTable.template",  'active' => "$modelTable.active"
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

		return self::findByFormId( $formId );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'name', 'label', 'type', 'compress', 'validators', 'order', 'icon', 'htmlOptions', 'data' ];

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

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

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// FormFieldService ----------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public static function findByFormId( $formId ) {

		return FormField::findByFormId( $formId );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
