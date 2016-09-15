<?php
namespace cmsgears\core\common\guidelines;

/**
 * A model extending Entity can be further divided into below mentioned sections:
 * 1. Yii imports
 * 2. CMG imports
 * 3. Project imports
 * 4. Class definition
 * 5. Variables
 * 		5.1. Globals
 * 				5.1.1. Constants
 * 				5.1.2. Public
 * 				5.1.3. Protected
 * 		5.2. Variables
 * 				5.2.1. Public
 * 				5.2.2. Protected
 * 				5.2.3. Private
 * 6. Traits
 * 7. Constructor and Initialisation
 * 8. Instance methods
 * 		8.1. Yii interface implementation
 * 		8.2. Yii parent class overriden methods
 * 				8.2.1. Yii base component overriden methods including behaviours
 * 				8.2.2. Yii base model overriden methods including rules and labels
 * 		8.3. CMG interface implementation
 * 		8.4. CMG parent class overriden methods
 * 		8.5. Current class validators
 * 		8.6. Current class methods - hasOne, hasMany following other methods
 * 9. Static methods
 * 		9.1. Yii parent class overriden methods
 * 				9.1.1. ActiveRecord overriden methods including getTableName
 * 		9.2. CMG parent class overriden methods
 * 		9.3. Current class methods
 * 				9.4.1. Read - query<method>
 * 				9.4.2. Read - find<method>
 * 				9.4.3. Create
 * 				9.4.4. Update
 * 				9.4.5. Delete
 * 10. Class closure
 */

// Yii Imports
/**
 * Imports following below mentioned order:
 * 1. \Yii
 * 2. base
 * 3. data
 * 4. db
 * 5. helpers
 * 6. Behaviours
 * 7. Exceptions
 */
use \Yii;

// CMG Imports
/**
 * Imports following below mentioned order:
 * 1. Globals
 * 2. Interfaces
 * 3. Models
 * 4. Traits
 * 5. Behaviours
 * 6. Services
 * 7. Utilities
 */
use cmsgears\core\common\config\CoreGlobal;

// Project Imports


/**
 * Note: Declare model properties in the same order as defined in DB Table and column types(short, int, long, float, double, string, boolean).
 */

/**
 * EntityTemplate Entity
 *
 * @property short $<integer member>
 * @property integer $<integer member>
 * @property long $<integer member>
 * @property float $<float member>
 * @property double $<float member>
 * @property string $<string member>
 * @property boolean $<boolean member>
 */
class EntityTemplate extends \cmsgears\core\common\models\base\Entity {

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

    // <Model> -------------------------------

    // Static Methods ----------------------------------------------

    // Yii parent classes --------------------

    // yii\db\ActiveRecord ----

    // CMG parent classes --------------------

    // <Model> -------------------------------

    // Read - Query -----------

    // Read - Find ------------

    // Create -----------------

    // Update -----------------

    // Delete -----------------
}
