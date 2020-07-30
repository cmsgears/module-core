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
use cmsgears\core\common\models\resources\ModelMeta;

use cmsgears\core\common\services\interfaces\resources\IModelMetaService;

/**
 * ModelMetaService provide service methods of model meta.
 *
 * @since 1.0.0
 */
class ModelMetaService extends \cmsgears\core\common\services\base\ModelResourceService implements IModelMetaService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\resources\ModelMeta';

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

	// ModelMetaService ----------------------

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

			$config[ 'search-col' ] = $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $search;
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

	public function getByType( $parentId, $parentType, $type ) {

		$modelClass = static::$modelClass;

		return $modelClass::findByType( $parentId, $parentType, $type );
	}

	public function getByNameType( $parentId, $parentType, $name, $type ) {

		$modelClass = static::$modelClass;

		return $modelClass::findByNameType( $parentId, $parentType, $name, $type );
	}

	public function initByNameType( $parentId, $parentType, $name, $type, $valueType = ModelMeta::VALUE_TYPE_TEXT, $label = null, $icon = null ) {

		$modelClass = static::$modelClass;

		$meta = $modelClass::findByNameType( $parentId, $parentType, $name, $type );

		if( !isset( $meta ) ) {

			$meta = new $modelClass();

			$meta->parentId		= $parentId;
			$meta->parentType	= $parentType;

			$meta->name		= $name;
			$meta->label	= !empty( $label ) ? $label : $name;
			$meta->icon		= !empty( $icon ) ? $icon : null;
			$meta->type		= $type;
			$meta->active	= true;

			$meta->valueType = $valueType;

			switch( $valueType ) {

				case ModelMeta::VALUE_TYPE_FLAG: {

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

	public function getNameValueMapByType( $parentId, $parentType, $type, $config = [] ) {

		$config[ 'conditions' ][ 'parentId' ]	= $parentId;
		$config[ 'conditions' ][ 'parentType' ] = $parentType;
		$config[ 'conditions' ][ 'type' ]		= $type;

		return $this->getNameValueMap( $config );
	}

	public function getIdMetaMapByType( $parentId, $parentType, $type, $config = [] ) {

		$config[ 'conditions' ][ 'parentId' ]	= $parentId;
		$config[ 'conditions' ][ 'parentType' ] = $parentType;
		$config[ 'conditions' ][ 'type' ]		= $type;

		return $this->getObjectMap( $config );
	}

	public function getNameMetaMapByType( $parentId, $parentType, $type, $config = [] ) {

		$config[ 'key' ] = 'name';

		$config[ 'conditions' ][ 'parentId' ]	= $parentId;
		$config[ 'conditions' ][ 'parentType' ] = $parentType;
		$config[ 'conditions' ][ 'type' ]		= $type;

		return $this->getObjectMap( $config );
	}

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		if( empty( $model->label ) ) {

			$model->label = $model->name;
		}

		if( !isset( $model->valueType ) ) {

			$model->valueType = ModelMeta::VALUE_TYPE_TEXT;
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

		$existingModel = $this->getByNameType( $model->parentId, $model->parentType, $model->name, $model->type );

		// Create if it does not exist
		if( !isset( $existingModel ) ) {

			return $this->create( $model );
		}

		return $this->update( $model, $config );
	}

	/**
	 * It creates or update the $metas for given $parentId and $parentType.
	 * It also disable existing metas before updating in case type is provided.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $parent
	 * @param array $metas
	 * @param array $config
	 */
	public function creatOrUpdateByParent( $parentId, $parentType, $metas, $config = [] ) {

		$modelClass = static::$modelClass;

		// Disable all existing metas for given type
		if( isset( $config[ 'type' ] ) && isset( $config[ 'disable' ] ) ) {

			$this->disableByType( $parentId, $parentType, $config[ 'type' ] );
		}

		// Create/Update given metas
		if( !empty( $metas ) && count( $metas ) > 0 ) {

			foreach( $metas as $meta ) {

				$model = new $modelClass();

				if( isset( $meta[ 'id' ] ) ) {

					$model = $modelClass::findByNameType( $parentId, $parentType, $meta[ 'name' ], $meta[ 'type' ] );
				}

				$model->name	= $meta[ 'name' ];
				$model->label	= empty( $meta[ 'label' ] ) ? $meta[ 'name' ] : $meta[ 'label' ];
				$model->value	= $meta[ 'value' ];
				$model->type	= $meta[ 'type' ];
				$model->active	= true;
				$model->icon	= !empty( $meta[ 'icon' ] ) ? $meta[ 'icon' ] : $model->icon;

				$model->parentId	= $parentId;
				$model->parentType	= $parentType;

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

	public function updateByParams( $params = [], $config = [] ) {

		$parentId	= $params[ 'parentId' ];
		$parentType	= $params[ 'parentType' ];
		$name		= $params[ 'name' ];
		$type		= $params[ 'type' ];

		$model = $this->getByNameType( $parentId, $parentType, $name, $type );

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

			// TODO: Add a check for parentType
			if( $model->parentId == $parent->id ) {

				$this->update( $model );
			}
		}
	}

	public function updateMultipleByForm( $form, $config = [] ) {

		$metas = $form->getArrayToStore();

		foreach( $metas as $meta ) {

			if( !isset( $meta[ 'valueType' ] ) ) {

				$meta[ 'valueType' ] = ModelMeta::VALUE_TYPE_TEXT;
			}

			if( !isset( $meta[ 'label' ] ) ) {

				$meta[ 'label' ] = $form->getAttributeLabel( $meta[ 'name' ] );
			}

			if( !isset( $meta[ 'icon' ] ) ) {

				$meta[ 'icon' ] = null;
			}

			$model = $this->initByNameType( $config[ 'parentId' ], $config[ 'parentType' ], $meta[ 'name' ], $config[ 'type' ], $meta[ 'valueType' ], $meta[ 'label' ], $meta[ 'icon' ] );

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

	public function disableByType( $parentId, $parentType, $type ) {

		$metas = $this->getByType( $parentId, $parentType, $type );

		foreach( $metas as $meta ) {

			$meta->active = false;

			$meta->update();
		}
	}

	// Delete -------------

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

	// ModelMetaService ----------------------

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
