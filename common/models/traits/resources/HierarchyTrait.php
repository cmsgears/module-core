<?php
namespace cmsgears\core\common\models\traits\resources;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\ModelHierarchy;

/**
 * HierarchyTrait can be used to access parent child relationship.
 */
trait HierarchyTrait {

    public function getParent() {

    	return $this->hasOne( get_class( $this ), [ 'id' => 'parentId' ] )
					->viaTable( CoreTables::TABLE_MODEL_HIERARCHY, [ 'childId' => 'id' ], function( $query ) {

						$modelHierarchy = CoreTables::TABLE_MODEL_HIERARCHY;

                      	$query->onCondition( "$modelHierarchy.parentType=:ptype", [ ':ptype' => $this->parentType ] );
					});
    }

    public function getParents() {

    	return $this->hasMany( get_class( $this ), [ 'id' => 'parentId' ] )
					->viaTable( CoreTables::TABLE_MODEL_HIERARCHY, [ 'childId' => 'id' ], function( $query ) {

						$modelHierarchy = CoreTables::TABLE_MODEL_HIERARCHY;

                      	$query->onCondition( "$modelHierarchy.parentType=:ptype", [ ':ptype' => $this->parentType ] );
					});
    }

    /**
     * @return array - list of immediate children
     */
    public function getChildren() {

    	return $this->hasMany( get_class( $this ), [ 'id' => 'childId' ] )
					->viaTable( CoreTables::TABLE_MODEL_HIERARCHY, [ 'parentId' => 'id' ], function( $query ) {

						$modelHierarchy = CoreTables::TABLE_MODEL_HIERARCHY;

                      	$query->onCondition( "$modelHierarchy.parentType=:ptype", [ ':ptype' => $this->parentType ] );
					});
    }
}

?>