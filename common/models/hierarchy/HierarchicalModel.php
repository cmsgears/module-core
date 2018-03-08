<?php
namespace cmsgears\core\common\models\hierarchy;

// CMG Imports
use cmsgears\core\common\models\base\ActiveRecord;

/**
 * HierarchicalModel can be used by models which need simple parent child relationship. It
 * does not support Nested Set(left, right value), but support hierarchy via parentId.
 *
 * @property integer $parentId
 *
 * @since 1.0.0
 */
abstract class HierarchicalModel extends ActiveRecord {

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

	// HierarchicalModel ---------------------

	/**
	 * Return the corresponding parent model.
	 *
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	abstract public function getParent();

	/**
	 * Check whether the model has parent.
	 *
	 * @return boolean
	 */
	public function hasParent() {

		return isset( $this->parentId ) && $this->parentId > 0;
	}

	/**
	 * Return the name of associated parent.
	 *
	 * @return string|null
	 */
	public function getParentName() {

		$parent	= $this->parent;

		return isset( $parent ) ? $parent->name : null;
	}

	/**
	 * Return either immediate or all children.
	 *
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	abstract public function getChildren();

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	// CMG parent classes --------------------

	// HierarchicalModel ---------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
