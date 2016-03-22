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

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    abstract public function getParent();

    public function hasParent() {

        return isset( $this->parentId ) && $this->parentId > 0;
    }

    public function getParentName() {

        $parent = $this->parent;

        return isset( $parent ) ? $parent->name : null;
    }

    abstract public function getChildren();

    // yii\base\Component ----------------

    // yii\base\Model --------------------

    // HierarchicalModel------------------

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    // HierarchicalModel------------------

    // Create -------------

    // Read ---------------

    // Update -------------

    // Delete -------------
}

?>