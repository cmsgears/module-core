<?php
namespace cmsgears\core\common\models\base;

use cmsgears\core\common\models\traits\ParentTypeTrait;

/**
 * ParentMapper Entity
 *
 * It's the parent entity for all the mappers which support parentId and parentType columns.
 */
abstract class ParentMapper extends Mapper {

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