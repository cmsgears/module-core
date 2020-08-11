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

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\Binder;

use cmsgears\core\common\services\interfaces\base\IModelMapperService;

/**
 * ModelMapperService defines commonly used methods specific to model mapper. It provide several
 * useful and commonly used methods by mapper.
 *
 * The model mapper must have modelId, parentId and parentType attributes where
 * modelId is the id of model mapped to parent for given parentId and parentType.
 *
 * The mapper might also provide few more common attributes including active, order and type.
 *
 * @since 1.0.0
 */
abstract class ModelMapperService extends ActiveRecordService implements IModelMapperService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	protected $parentService;

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

	// ModelMapperService --------------------

	// Data Provider ------

	public function getPageByParent( $parentId, $parentType ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		return $this->getPage( [ 'conditions' => [ "$modelTable.parentId" => $parentId, "$modelTable.parentType" => $parentType ] ] );
	}

	// Read ---------------

	// Read - Models ---

	/**
	 * @inheritdoc
	 */
	public function getByParentId( $parentId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByParentId( $parentId );
	}

	/**
	 * @inheritdoc
	 */
	public function getByParentType( $parentType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByParentType( $parentType );
	}

	/**
	 * @inheritdoc
	 */
	public function getByParent( $parentId, $parentType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByParent( $parentId, $parentType );
	}

	/**
	 * @inheritdoc
	 */
	public function getByModelId( $modelId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByModelId( $modelId );
	}

	/**
	 * @inheritdoc
	 */
	public function getByParentModelId( $parentId, $parentType, $modelId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByParentModelId( $parentId, $parentType, $modelId );
	}

	/**
	 * @inheritdoc
	 */
	public function getFirstByParentModelId( $parentId, $parentType, $modelId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findFirstByParentModelId( $parentId, $parentType, $modelId );
	}

	/**
	 * @inheritdoc
	 */
	public function getByModelIdParentType( $modelId, $parentType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByModelIdParentType( $modelId, $parentType );
	}

	/**
	 * @inheritdoc
	 */
	public function getByParentModelIdType( $parentId, $parentType, $modelId, $type ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByParentModelIdType( $parentId, $parentType, $modelId, $type );
	}

	/**
	 * @inheritdoc
	 */
	public function getFirstByParentModelIdType( $parentId, $parentType, $modelId, $type ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findFirstByParentModelIdType( $parentId, $parentType, $modelId, $type );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveByParentId( $parentId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findActiveByParentId( $parentId );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveByParentType( $parentType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findActiveByParentType( $parentType );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveByParent( $parentId, $parentType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findActiveByParent( $parentId, $parentType );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveByModelId( $modelId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findActiveByModelId( $modelId );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveByParentModelId( $parentId, $parentType, $modelId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findActiveByParentModelId( $parentId, $parentType, $modelId );
	}

	/**
	 * @inheritdoc
	 */
	public function getFirstActiveByParentModelId( $parentId, $parentType, $modelId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findFirstActiveByParentModelId( $parentId, $parentType, $modelId );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveByModelIdParentType( $modelId, $parentType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findActiveByModelIdParentType( $modelId, $parentType );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveByParentModelIdType( $parentId, $parentType, $modelId, $type ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findActiveByParentModelIdType( $parentId, $parentType, $modelId, $type );
	}

	/**
	 * @inheritdoc
	 */
	public function getFirstActiveByParentModelIdType( $parentId, $parentType, $modelId, $type ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findFirstActiveByParentModelIdType( $parentId, $parentType, $modelId, $type );
	}

	// Read - Lists ----

	public function getActiveModelIdListByParent( $parentId, $parentType ) {

		$modelClass	= static::$modelClass;

		$models = $modelClass::findActiveByParent( $parentId, $parentType );

		$ids = [];

		foreach( $models as $model ) {

			$ids[] = $model->parent->id;
		}

		return $ids;
	}

	// Read - Maps -----

	// Read - Others ---

	public function getMappingsCount( $parentType, $config = [] ) {

		$mappingType = $config[ 'mappingType' ] ?? null;

		$parentTable	= $this->parentService->getModelTable();
		$mappingTable	= $this->getModelTable();

		$query = new Query();

		$query->select( [ "$parentTable.id as id", "count($parentTable.id) as total" ] )
				->from( $parentTable )
				->leftJoin( $mappingTable, "$mappingTable.parentId=$parentTable.id" )
				->where( "$mappingTable.parentType='$parentType'" );

		if( isset( $mappingType ) ) {

			$query->andWhere( "$mappingTable.type='$mappingType'" );
		}

		$query->groupBy( "$parentTable.id" );

		$counts = $query->all();

		$returnArr	= [];
		$counter	= 0;

		foreach ( $counts as $count ) {

			$returnArr[ $count[ 'id' ] ] = $count[ 'total' ];

			$counter = $counter + $count[ 'total' ];
		}

		$returnArr[ 'all' ] = $counter;

		return $returnArr;
	}

	// Create -------------

	public function createByParams( $params = [], $config = [] ) {

		$params[ 'active' ]	= isset( $params[ 'active' ] ) ? $params[ 'active' ] : CoreGlobal::STATUS_ACTIVE;

		return parent::createByParams( $params, $config );
	}

	public function createWithParent( $parent, $config = [] ) {

		$modelClass	= static::$modelClass;

		$parentId	= $config[ 'parentId' ];
		$parentType	= $config[ 'parentType' ];
		$type		= isset( $config[ 'type' ] ) ? $config[ 'type' ] : CoreGlobal::TYPE_DEFAULT;
		$order		= isset( $config[ 'order' ] ) ? $config[ 'order' ] : 0;

		$parent = $this->parentService->create( $parent, $config );

		$model = new $modelClass;

		$model->modelId		= $parent->id;
		$model->parentId	= $parentId;
		$model->parentType	= $parentType;

		$model->type	= $type;
		$model->order	= $order;
		$model->active	= true;

		return parent::create( $model );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		//$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'type', 'order', 'active'
		];

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function activate( $model ) {

		$model->active = true;

		$model->update();

		return $model;
	}

	public function disable( $model ) {

		$model->active = false;

		$model->update();

		return $model;
	}

	public function toggleActive( $model ) {

		if( $model->active ) {

			$model->active = false;
		}
		else {

			$model->active = true;
		}

		return parent::update( $model, [
			'attributes' => [ 'active' ]
		]);
 	}

	/**
	 * It assumes that only one model exist for same parentId, parentType, modelId, and type.
	 *
	 * @inheritdoc
	 */
	public function activateByParentModelId( $parentId, $parentType, $modelId, $type = null ) {

		$model = $this->getFirstByParentModelId( $parentId, $parentType, $modelId );

		// Existing Mapping
		if( isset( $model ) ) {

			return $this->activate( $model );
		}
		// New Mapping
		else {

			$type = $type ??$parentType;

			return $this->createByParams([
				'parentId' => $parentId, 'parentType' => $parentType, 'modelId' => $modelId,
				'type' => $type, 'active' => true
			]);
		}
	}

	public function disableByParentModelId( $parentId, $parentType, $modelId, $delete = false ) {

		$model = $this->getByModelId( $parentId, $parentType, $modelId );

		if( isset( $model ) ) {

			// Hard delete
			if( $delete ) {

				$model->delete();
			}
			// Soft delete
			else {

				$this->disable( $model );
			}
		}
	}

	public function toggleByParentModelId( $parentId, $parentType, $modelId, $type = null ) {

		$model = $this->getFirstByParentModelId( $parentId, $parentType, $modelId );

		// Existing Mapping
		if( isset( $model ) ) {

			if( $model->active ) {

				$model->active = false;
			}
			else {

				$model->active = true;
			}

			$model->update();
		}
		// New Mapping
		else {

			$type = $type ??$parentType;

			return $this->createByParams([
				'parentId' => $parentId, 'parentType' => $parentType, 'modelId' => $modelId,
				'type' => $type, 'active' => true
			]);
		}
	}

	public function disableByParent( $parentId, $parentType, $delete = false ) {

		$models = $this->getByParent( $parentId, $parentType );

		foreach( $models as $model ) {

			// Hard delete
			if( $delete ) {

				$model->delete();
			}
			// Soft delete
			else {

				$this->disable( $model );
			}
		}
	}

	public function bindModels( $parentId, $parentType, $config = [] ) {

		$modelClass	= static::$modelClass;
		$binderName	= isset( $config[ 'binder' ] ) ? $config[ 'binder' ] : 'Binder';

		$modelBinder = $config[ 'modelBinder' ] ?? null;

		if( empty( $modelBinder ) ) {

			$modelBinder = new Binder();

			$modelBinder->load( Yii::$app->request->post(), $binderName );
		}

		$all	= $modelBinder->all; // Possible Bindings
		$binded	= $modelBinder->binded; // Existing Bindings

		$process = []; // For Execution

		// Check for All
		if( count( $all ) > 0 ) {

			$process = $all;
		}
		// Check for Active
		else {

			$process = $binded;

			$modelClass::disableByParent( $parentId, $parentType );
		}

		// Process the List
		foreach( $process as $id ) {

			$existingMapping = $modelClass::findFirstByParentModelId( $parentId, $parentType, $id );

			// Existing mapping
			if( isset( $existingMapping ) ) {

				if( in_array( $id, $binded ) ) {

					$existingMapping->active = true;
				}
				else {

					$existingMapping->active = false;
				}

				$existingMapping->update();
			}
			// Create Mapping
			else if( in_array( $id, $binded ) ) {

				$this->createByParams([
					'modelId' => $id, 'parentId' => $parentId, 'parentType' => $parentType,
					'type' => $parentType, 'order' => 0, 'active' => true
				]);
			}
		}

		return true;
	}

	// Delete -------------

	public function deleteByParent( $parentId, $parentType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::deleteByParent( $parentId, $parentType );
	}

	public function deleteByModelId( $modelId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::deleteByModelId( $modelId );
	}

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelMapperService --------------------

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
