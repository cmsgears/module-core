<?php
namespace cmsgears\core\common\models\base;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\traits\NameTypeTrait;

/**
 * TypedResource Entity
 *
 * It's the parent entity for all the resources which need unique name for a particular type.
 */
abstract class TypedResource extends Resource {

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