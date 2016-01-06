<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * HierarchicalModel
 *
 * @property integer $parentId
 * @property integer $rootId
 * @property string $name
 * @property integer lValue
 * @property integer rValue
 */
abstract class HierarchicalModel extends CmgEntity {

	// Instance Methods --------------------------------------------

	public function getParent() {

		// To be overriden by child
	}

	public function hasParent() {

		return isset( $this->parentId ) && $this->parentId > 0;
	}

	public function getParentName() {

		$parent	= $this->parent;
		
		return isset( $parent ) ? $parent->name : null;
	}
	
	public function getChildren() {
		
		// To be overriden by child
	}
}

?>