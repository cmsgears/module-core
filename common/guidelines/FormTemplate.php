<?php
namespace cmsgears\listing\common\guidelines;

/**
 * A model extending JsonModel, GenericForm or yii base model can be further divided into below mentioned sections:
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
 * 		8.2. Yii base component overriden methods including behaviours
 * 		8.3. Yii base model overriden methods including rules and labels
 * 		8.4. Yii parent class overriden methods
 * 		8.5. CMG interface implementation
 * 		8.6. CMG parent class overriden methods
 * 		8.7. Current class validators
 * 		8.8. Current class methods
 * 9. Static methods
 * 		9.1. Yii parent class overriden methods
 * 		9.2. CMG parent class overriden methods
 * 		9.3. Current class methods
 * 10. Class closure
 */

// Yii Imports
/**
 * Imports following below mentioned order:
 * 1. \Yii
 * 2. base
 * 3. helpers
 * 4. Behaviours
 * 5. Exceptions
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
 * FormTemplate Entity
 *
 * @property short $<integer member>
 * @property int $<integer member>
 * @property long $<integer member>
 * @property float $<float member>
 * @property double $<float member>
 * @property string $<string member>
 * @property boolean $<boolean member>
 */
class FormTemplate extends \yii\base\Model {

	// Variables ---------------------------------------------------

	// Constants/Statics --

	// Public -------------

	// Private/Protected --

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces --------------------

	// yii\base\Component ----------------

	// yii\base\Model --------------------

	// Yii parent class ------------------

	// CMG interfaces --------------------

	// CMG parent class ------------------

	// Validators ------------------------

	// <Model> ---------------------------

	// Static Methods ----------------------------------------------

	// Yii parent class ------------------

	// CMG parent class ------------------

	// <Model> ---------------------------

}

?>