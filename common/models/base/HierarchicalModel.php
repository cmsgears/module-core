<?php
namespace cmsgears\core\common\models\base;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * HierarchicalModel - It can be used by models which need parent child relationship. It supports two way relationship i.e. by parentId and Nested Set(left, right value).
 *
 * @property integer $parentId
 * @property integer $rootId
 * @property string $name
 * @property integer lValue
 * @property integer rValue
 */
abstract class HierarchicalModel extends CmgEntity {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    // yii\base\Component ----------------

    // yii\base\Model --------------------

    // HierarchicalModel------------------

	abstract public function getParent();

	public function hasParent() {

		return isset( $this->parentId ) && $this->parentId > 0;
	}

	public function getParentName() {

		$parent	= $this->parent;

		return isset( $parent ) ? $parent->name : null;
	}

	abstract public function getChildren();

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    // HierarchicalModel------------------

    // Create -------------

    // Read ---------------

    // Update -------------

    // Delete -------------
}

?>