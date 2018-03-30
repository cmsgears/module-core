<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\resources;

// CMG Imports
use cmsgears\core\common\models\resources\ModelHierarchy;

/**
 * HierarchyTrait can be used to access parent child relationship.
 */
trait HierarchyTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// HierarchyTrait ------------------------

	/**
	 * @inheritdoc
	 */
	public function getParent() {

		$modelHierarchyTable = ModelHierarchy::tableName();

		return $this->hasOne( get_class( $this ), [ 'id' => 'parentId' ] )
			->viaTable( $modelHierarchyTable, [ 'childId' => 'id' ], function( $query ) use( &$modelHierarchyTable ) {

				$query->onCondition( "$modelHierarchyTable.parentType=:ptype", [ ':ptype' => $this->modelType ] );
			});
	}

	/**
	 * @inheritdoc
	 */
	public function getParents() {

		$modelHierarchyTable = ModelHierarchy::tableName();

		return $this->hasMany( get_class( $this ), [ 'id' => 'parentId' ] )
			->viaTable( $modelHierarchyTable, [ 'childId' => 'id' ], function( $query ) use( &$modelHierarchyTable ) {

				$query->onCondition( "$modelHierarchyTable.parentType=:ptype", [ ':ptype' => $this->modelType ] );
			});
	}

	/**
	 * @inheritdoc
	 */
	public function getChildren() {

		$modelHierarchyTable = ModelHierarchy::tableName();

		return $this->hasMany( get_class( $this ), [ 'id' => 'childId' ] )
			->viaTable( $modelHierarchyTable, [ 'parentId' => 'id' ], function( $query ) use( &$modelHierarchyTable ) {

				$query->onCondition( "$modelHierarchyTable.parentType=:ptype", [ ':ptype' => $this->modelType ] );
			});
	}

	/**
	 * @inheritdoc
	 */
	public function getChildrenIdList() {

		$children		= $this->children;
		$cildrenIdList	= [];

		foreach( $children as $child ) {

			array_push( $cildrenIdList, $child->id );
		}

		return $cildrenIdList;
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// HierarchyTrait ------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
