<?php
namespace cmsgears\core\common\models\hierarchy;

/**
 * HierarchicalModel can be used by models which need parent child relationship. It supports
 * two way relationship i.e. by parentId and Nested Set(left, right value).
 *
 * @property long $parentId
 * @property long $rootId
 * @property string $name
 * @property integer lValue
 * @property integer rValue
 *
 * @since 1.0.0
 */
abstract class NestedSetModel extends HierarchicalModel {

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

	// NestedSetModel ------------------------

	/**
	 * Return the corresponding root model.
	 *
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	abstract public function getRoot();

	/**
	 * Check whether the model has root.
	 *
	 * @return boolean
	 */
	public function hasRoot() {

		return isset( $this->rootId ) && $this->rootId > 0;
	}

	/**
	 * Return the name of associated parent.
	 *
	 * @return string|null
	 */
	public function getRootName() {

		$root = $this->root;

		return isset( $root ) ? $root->name : null;
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	// CMG parent classes --------------------

	// NestedSetModel ------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
