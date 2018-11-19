<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\base;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\base\Meta;

use cmsgears\core\common\services\interfaces\base\IMetaService;

/**
 * MetaService is base class for all the services having [[\cmsgears\core\common\models\base\Meta]] as primary model.
 */
abstract class MetaService extends ResourceService implements IMetaService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// MetaService ---------------------------

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
					'label' => 'name'
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
				'active' => [
					'asc' => [ "$modelTable.active" => SORT_ASC ],
					'desc' => [ "$modelTable.active" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Active'
				],
				'vtype' => [
					'asc' => [ "$modelTable.valueType" => SORT_ASC ],
					'desc' => [ "$modelTable.valueType" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Value Type'
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
			}
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'name' => "$modelTable.name",
				'label' => "$modelTable.label",
				'value' => "$modelTable.value"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name",
			'label' => "$modelTable.label",
			'type' => "$modelTable.type",
			'vtype' => "$modelTable.valueType",
			'value' => "$modelTable.value"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getByModelId( $modelId ) {

		return self::findByModelId( $modelId );
	}

	public function getByName( $modelId, $name ) {

		return self::findByName( $modelId, $name );
	}

	public function getFirstByName( $modelId, $name ) {

		return self::getFirstByName( $modelId, $name );
	}

	public function getByType( $modelId, $type ) {

		return self::findByType( $modelId, $type );
	}

	public function getByNameType( $modelId, $name, $type ) {

		return self::findByNameType( $modelId, $name, $type );
	}

	public function initByNameType( $modelId, $name, $type, $valueType = Meta::VALUE_TYPE_TEXT, $label = null ) {

		$meta = $this->getByNameType( $modelId, $name, $type );

		if( !isset( $meta ) ) {

			$modelClass = static::$modelClass;

			// Initialise
			$meta = new $modelClass();

			$meta->modelId		= $modelId;
			$meta->name			= $name;
			$meta->label		= !empty( $label ) ? $label : $name;
			$meta->type			= $type;
			$meta->active		= true;
			$meta->valueType	= $valueType;

			switch( $valueType ) {

				case Meta::VALUE_TYPE_FLAG: {

					$meta->value = 0;
				}
				default: {

					$meta->value = null;
				}
			}

			// Create & Refresh
			$meta->save();

			$meta->refresh();
		}

		return $meta;
	}

	// Read - Lists ----

	// Read - Maps -----

	public function getNameValueMapByModelId( $modelId ) {

		$config[ 'conditions' ][ 'modelId' ] = $modelId;

		return $this->getNameValueMap( $config );
	}

	public function getNameValueMapByType( $modelId, $type ) {

		$config[ 'conditions' ][ 'modelId' ]	= $modelId;
		$config[ 'conditions' ][ 'type' ]		= $type;

		return $this->getNameValueMap( $config );
	}

	public function getIdMetaMapByModelId( $modelId ) {

		$config[ 'conditions' ][ 'modelId' ] = $modelId;

		return $this->getObjectMap( $config );
	}

	public function getIdMetaMapByType( $modelId, $type ) {

		$config[ 'conditions' ][ 'modelId' ]	= $modelId;
		$config[ 'conditions' ][ 'type' ]		= $type;

		return $this->getObjectMap( $config );
	}

	public function getNameMetaMapByType( $modelId, $type ) {

		$config[ 'key' ]						= 'name';
		$config[ 'conditions' ][ 'modelId' ]	= $modelId;
		$config[ 'conditions' ][ 'type' ]		= $type;

		return $this->getObjectMap( $config );
	}

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		if( !isset( $model->label ) || strlen( $model->label ) <= 0 ) {

			$model->label = $model->name;
		}

		$model->save();

		return $model;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		if( isset( $model->id ) ) {
			
			$existingModel	= $this->getById( $model->id );
		}
		else {
			
			$existingModel	= $this->getByNameType( $model->modelId, $model->name, $model->type );
		}

		// Create if it does not exist
		if( !isset( $existingModel ) ) {

			return $this->create( $model );
		}

		if( isset( $model->valueType ) ) {

			$attributes	= [ 'valueType', 'value' ];
		}
		else {

			$attributes	= [ 'value' ];
		}

		if( !isset( $config[ 'attributes' ] ) ) {

			$config[ 'attributes' ]	= $attributes;
		}

		return parent::update( $model, $config );
	}

	public function updateByParams( $params = [], $config = [] ) {

		$modelId	= $params[ 'modelId' ];
		$name		= $params[ 'name' ];
		$type		= $params[ 'type' ];

		$model		= $this->getByNameType( $modelId, $name, $type );

		if( isset( $model ) ) {

			$model->value	= $params[ 'value' ];

			return parent::update( $model, [
				'selective' => false,
				'attributes' => [ 'value' ]
			]);
		}
	}

	public function updateMultiple( $models, $config = [] ) {

		$parent	= $config[ 'parent' ];

		foreach( $models as $model ) {

			if( $model->modelId == $parent->id ) {

				$this->update( $model );
			}
		}
	}

	public function updateMultipleByForm( $form, $config = [] ) {

		$metas = $form->getArrayToStore();

		foreach( $metas as $meta ) {

			if( !isset( $meta[ 'valueType' ] ) ) {

				$meta[ 'valueType' ] = Meta::VALUE_TYPE_TEXT;
			}

			if( !isset( $meta[ 'label' ] ) ) {

				$meta[ 'label' ] = $form->getAttributeLabel( $meta[ 'name' ] );
			}

			$model = $this->initByNameType( $config[ 'modelId' ], $meta[ 'name' ], $config[ 'type' ], $meta[ 'valueType' ], $meta[ 'label' ] );

			$model->value	= $meta[ 'value' ];
			$model->label	= $form->getAttributeLabel( $meta[ 'name' ] );

			$this->update( $model );
		}
	}

	public function toggle( $model ) {

		if( $model->value ) {

			$model->value = false;
		}
		else {

			$model->value = true;
		}

		$model->update();
	}

	// Delete -------------

	public function deleteByModelId( $modelId ) {

		$modelClass	= static::$modelClass;

		$modelClass::deleteAll( 'modelId=:id', [ ':id' => $modelId ] );
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

	// MetaService ---------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public static function findByModelId( $modelId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByModelId( $modelId );
	}

	public static function findByName( $modelId, $name ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByName( $modelId, $name );
	}

	public static function findFirstByName( $modelId, $name ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findFirstByName( $modelId, $name );
	}

	public static function findByType( $modelId, $type ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByType( $modelId, $type );
	}

	public static function findByNameType( $modelId, $name, $type ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByNameType( $modelId, $name, $type );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
