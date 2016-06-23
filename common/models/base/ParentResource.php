<?php
namespace cmsgears\core\common\models\base;

use cmsgears\core\common\models\traits\ParentTypeTrait;

/**
 * ParentResource Entity
 *
 * It's the parent entity for all the resources which support parentId and parentType columns.
 */
abstract class ParentResource extends Resource {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

	use ParentTypeTrait;

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    // yii\base\Component ----------------

    // yii\base\Model --------------------

    // CmgModel --------------------------

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    // CmgModel --------------------------

    // Create -------------

    // Read ---------------

    // Update -------------

    // Delete -------------

}

?>