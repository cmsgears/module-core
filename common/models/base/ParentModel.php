<?php
namespace cmsgears\core\common\models\base;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ParentModel - It can be used by models which need simple parent child relationship. It does not support Nested Set(left, right value).
 *
 * @property integer $parentId
 */
abstract class ParentModel extends CmgEntity {

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