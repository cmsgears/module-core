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
use yii\helpers\ArrayHelper;

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
				'icon' => [
					'asc' => [ "$modelTable.icon" => SORT_ASC ],
					'desc' => [ "$modelTable.icon" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Icon'
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
				case 'disabled': {

					$config[ 'conditions' ][ "$modelTable.active" ] = false;

					break;
				}
			}
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'name' => "$modelTable.name",
			'label' => "$modelTable.label",
			'value' => "$modelTable.value"
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
			'label' => "$modelTable.label",
			'active' => "$modelTable.active",
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

	public function initByNameType( $modelId, $name, $type, $valueType = Meta::VALUE_TYPE_TEXT, $label = null, $icon = null ) {

		$modelClass = static::$modelClass;

		$meta = $modelClass::findByNameType( $modelId, $name, $type );

		if( !isset( $meta ) ) {

			// Initialise
			$meta = new $modelClass();

			$meta->modelId = $modelId;

			$meta->name		= $name;
			$meta->label	= !empty( $label ) ? $label : $name;
			$meta->icon		= !empty( $icon ) ? $icon : null;
			$meta->type		= $type;
			$meta->active	= true;

			$meta->valueType = $valueType;

			switch( $valueType ) {

				case Meta::VALUE_TYPE_FLAG: {

					$meta->value = 1; // The flag should be turned on by default

					break;
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

	public function getNameValueMapByModelId( $modelId, $config = [] ) {

		$config[ 'conditions' ][ 'modelId' ] = $modelId;

		return $this->getNameValueMap( $config );
	}

	public function getNameValueMapByType( $modelId, $type, $config = [] ) {

		$config[ 'conditions' ][ 'modelId' ]	= $modelId;
		$config[ 'conditions' ][ 'type' ]		= $type;

		return $this->getNameValueMap( $config );
	}

	public function getIdMetaMapByModelId( $modelId, $config = [] ) {

		$config[ 'conditions' ][ 'modelId' ] = $modelId;

		return $this->getModelMap( $config );
	}

	public function getIdMetaMapByType( $modelId, $type, $config = [] ) {

		$config[ 'conditions' ][ 'modelId' ]	= $modelId;
		$config[ 'conditions' ][ 'type' ]		= $type;

		return $this->getModelMap( $config );
	}

	public function getNameMetaMapByType( $modelId, $type, $config = [] ) {

		$config[ 'key' ] = 'name';

		$config[ 'conditions' ][ 'modelId' ]	= $modelId;
		$config[ 'conditions' ][ 'type' ]		= $type;

		return $this->getModelMap( $config );
	}

	// Read - Others ---

	// Create -------------

	/**
	 * It generates the label if not set and create the meta.
	 *
	 * @param \cmsgears\core\common\models\base\Meta $model
	 * @param array $config
	 * @return \cmsgears\core\common\models\base\Meta
	 */
	public function create( $model, $config = [] ) {

		if( empty( $model->label ) ) {

			$model->label = $model->name;
		}

		if( !isset( $model->valueType ) ) {

			$model->valueType = Meta::VALUE_TYPE_TEXT;
		}

		return parent::create( $model );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'icon', 'name', 'label', 'active', 'order', 'valueType', 'value'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [ 'type' ] );
		}

		if( !isset( $config[ 'attributes' ] ) ) {

			$config[ 'attributes' ]	= $attributes;
		}

		return parent::update( $model, $config );
	}

	public function createOrUpdate( $model, $config = [] ) {

		$existingModel = $this->getByNameType( $model->parentId, $model->name, $model->type );

		// Create if it does not exist
		if( !isset( $existingModel ) ) {

			return $this->create( $model );
		}

		return $this->update( $model, $config );
	}

	/**
	 * It creates or update the $metas for given $parent.
	 * It also disable existing metas before updating in case type is provided.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $parent
	 * @param array $metas
	 * @param array $config
	 */
	public function creatOrUpdateByParent( $parent, $metas, $config = [] ) {

		$modelClass = static::$modelClass;

		// Disable all existing metas for given type
		if( isset( $config[ 'type' ] ) && isset( $config[ 'disable' ] ) ) {

			$this->disableByType( $parent, $config[ 'type' ] );
		}

		// Create/Update given metas
		if( !empty( $metas ) && count( $metas ) > 0 ) {

			foreach( $metas as $meta ) {

				$model = new $modelClass();

				if( isset( $meta[ 'id' ] ) ) {

					$model = $modelClass::findByNameType( $parent->id, $meta[ 'name' ], $meta[ 'type' ] );
				}

				$model->name	= $meta[ 'name' ];
				$model->label	= empty( $meta[ 'label' ] ) ? $meta[ 'name' ] : $meta[ 'label' ];
				$model->value	= $meta[ 'value' ];
				$model->type	= $meta[ 'type' ];
				$model->active	= true;
				$model->icon	= !empty( $meta[ 'icon' ] ) ? $meta[ 'icon' ] : $model->icon;

				$model->modelId	= $parent->id;

				$model->valueType = empty( $meta[ 'valueType' ] ) ? $modelClass::VALUE_TYPE_TEXT : $meta[ 'valueType' ];

				if( isset( $meta[ 'id' ] ) ) {

					parent::update( $model, [ 'attributes' => [ 'icon', 'name', 'value', 'label', 'active', 'type', 'valueType' ] ] );
				}
				else {

					parent::create( $model );
				}
			}
		}
	}

	/*
	 * It must be used only when name and type are not changed since it discover the
	 * model using name and type.
	 */
	public function updateByParams( $params = [], $config = [] ) {

		$modelId	= $params[ 'modelId' ];
		$name		= $params[ 'name' ];
		$type		= $params[ 'type' ];

		$model = $this->getByNameType( $modelId, $name, $type );

		if( isset( $model ) ) {

			$model->value = $params[ 'value' ];

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

	/**
	 * It will be used to update the metas of a specific type using the form.
	 * It does not expect the meta to have id and solely rely on given name and type.
	 *
	 * @param type $form
	 * @param type $config
	 */
	public function updateMultipleByForm( $form, $config = [] ) {

		$metas = $form->getArrayToStore();

		foreach( $metas as $meta ) {

			if( !isset( $meta[ 'valueType' ] ) ) {

				$meta[ 'valueType' ] = Meta::VALUE_TYPE_TEXT;
			}

			if( !isset( $meta[ 'label' ] ) ) {

				$meta[ 'label' ] = $form->getAttributeLabel( $meta[ 'name' ] );
			}

			if( !isset( $meta[ 'icon' ] ) ) {

				$meta[ 'icon' ] = null;
			}

			$model = $this->initByNameType( $config[ 'modelId' ], $meta[ 'name' ], $config[ 'type' ], $meta[ 'valueType' ], $meta[ 'label' ], $meta[ 'icon' ] );

			$model->value	= $meta[ 'value' ];
			$model->label	= $meta[ 'label' ];

			$this->update( $model );
		}
	}

	public function toggleActive( $model ) {

		if( $model->active ) {

			$model->active = false;
		}
		else {

			$model->active = true;
		}

		$model->update();
	}

	public function toggleValue( $model ) {

		if( $model->value ) {

			$model->value = false;
		}
		else {

			$model->value = true;
		}

		$model->update();
	}

	public function disableByType( $parent, $type ) {

		$metas = $this->getByType( $parent->id, $type );

		foreach( $metas as $meta ) {

			$meta->active = false;

			$meta->update();
		}
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
