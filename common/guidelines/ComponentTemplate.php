<?php
namespace cmsgears\core\common\guidelines;

/**
 * A component extending Component can be further divided into below mentioned sections:
 * 1. Yii imports
 * 2. CMG imports
 * 3. Project imports
 * 4. Class definition
 * 5. Variables
 * 		5.2.1. Global
 * 		5.2.2. Public
 * 		5.2.3. Protected
 * 		5.2.4. Private
 * 6. Constructor and Initialisation
 * 7. Instance methods
 * 		7.1. Yii parent class overriden methods
 * 		7.2. CMG parent class overriden methods
 * 		7.3. Current class methods
 * 8. Class closure
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
 * ComponentTemplate Entity
 *
 * @property short $<integer member>
 * @property integer $<integer member>
 * @property long $<integer member>
 * @property float $<float member>
 * @property double $<float member>
 * @property string $<string member>
 * @property boolean $<boolean member>
 */
class ComponentTemplate extends \yii\base\Component {

    // Variables ---------------------------------------------------

    // Global -----------------

    // Public -----------------

    // Protected --------------

    // Private ----------------

    // Constructor and Initialisation ------------------------------

    // Instance methods --------------------------------------------

    // Yii parent classes --------------------

    // CMG parent classes --------------------

    // <Component> ---------------------------
}
