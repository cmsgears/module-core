<?php
namespace cmsgears\core\common\models\base;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\traits\NameTypeTrait;

/**
 * TypedEntity Entity
 *
 * It's the parent entity for all the entities which need unique name for a particular type.
 */
abstract class TypedEntity extends Entity {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

	use NameTypeTrait;

    // Constructor and Initialisation ------------------------------

	// Instance Methods --------------------------------------------

    // yii\base\Component ----------------

    // yii\base\Model --------------------

    // TypedEntity -----------------------

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    // TypedCmgEntity --------------------

    // Create -------------

    // Read ---------------

    // Update -------------

    // Delete -------------
}

?>