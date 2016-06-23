<?php
namespace cmsgears\listing\common\guidelines;

/**
 * Actions extending yii base action can be further divided into below mentioned sections:
 * 1. Yii imports
 * 2. CMG imports
 * 3. Project imports
 * 4. Class definition
 * 5. Variables
 * 		5.1. Global and Static variables
 * 		5.2. Public variables
 * 		5.3. Private and Protected Variables
 * 6. Traits
 * 7. Constructor and Initialisation
 * 8. Instance methods
 * 		8.1. Yii interface implementation
 * 		8.2. Yii parent class overriden methods
 * 		8.3. CMG interface implementation
 * 		8.4. CMG parent class overriden methods
 * 		8.5. Current class methods
 * 9. Class closure
 */

// Yii Imports
/**
 * Imports following below mentioned order:
 * 1. \Yii
 * 2. base
 * 3. data
 * 4. helpers
 * 5. Exceptions
 */
use \Yii;

// CMG Imports
/**
 * Imports following below mentioned order:
 * 1. Interfaces
 * 2. Models
 * 3. Services
 * 4. Utilities
 */
use cmsgears\core\common\config\CoreGlobal;

// Project Imports

class ActionTemplate extends \yii\base\Action {

	// Variables ---------------------------------------------------

	// Constants/Statics --

	// Public -------------

	// Private/Protected --

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance Methods --------------------------------------------

	// Yii interfaces --------------------

	// Yii parent class ------------------

	// CMG interfaces --------------------

	// CMG parent class ------------------

	// <Action> --------------------------

}

?>