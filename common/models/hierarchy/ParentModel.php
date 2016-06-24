<?php
namespace cmsgears\core\common\models\hierarchy;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ParentModel - It can be used by models which need simple parent child relationship. It does not support Nested Set(left, right value).
 *
 * @property integer $parentId
 */
abstract class ParentModel extends \cmsgears\core\common\models\base\Entity {

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

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ParentModel ---------------------------

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

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	// CMG parent classes --------------------

	// ParentModel ---------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}

?>