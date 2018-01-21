<?php
namespace cmsgears\core\common\models\traits\mappers;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\ObjectData;
use cmsgears\core\common\models\mappers\ModelObject;

/**
 * ObjectTrait can be used to associate objects to relevant models.
 */
trait ObjectTrait {

	/**
	 * @return array - ModelObject associated with parent
	 */
	public function getModelObjects() {

		return $this->hasMany( ModelObject::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$this->modelType'" );
	}

	/**
	 * @return array - ModelObject associated with parent
	 */
	public function getModelObjectsByType( $type ) {

		return $this->hasMany( ModelObject::className(), [ 'parentId' => 'id' ] )
					->where( "parentType=:ptype AND type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] )->all();
	}

	/**
	 * @return array - objects associated with parent
	 */
	public function getObjects() {

		return $this->hasMany( ObjectData::className(), [ 'id' => 'modelId' ] )
					->viaTable( CoreTables::TABLE_MODEL_OBJECT, [ 'parentId' => 'id' ], function( $query ) {

						$modelObjectTable = CoreTables::TABLE_MODEL_OBJECT;

						$query->onCondition( "$modelObjectTable.parentType=:type", [ ':type' => $this->modelType ] );
					});
	}
}
