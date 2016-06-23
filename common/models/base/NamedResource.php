<?php
namespace cmsgears\core\common\models\base;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\traits\NameTrait;

/**
 * NamedResource Entity
 *
 * It's the parent entity for all the resources which need unique name.
 */
abstract class NamedResource extends Entity {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

	use NameTrait;

    // Constructor and Initialisation ------------------------------

	// Instance Methods --------------------------------------------

    // yii\base\Component ----------------

    // yii\base\Model --------------------

    // NamedCmgEntity --------------------

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    // NamedCmgEntity --------------------

    // Create -------------

    // Read ---------------

    // Update -------------

    // Delete -------------
}

?>