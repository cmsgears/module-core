<?php
namespace cmsgears\core\common\guidelines;

/**
 * A model extending Object or any other class can be further divided into below mentioned sections:
 * 1. Yii imports
 * 2. CMG imports
 * 3. Project imports
 * 4. Class definition
 * 5. Variables
 * 		5.1. Globals
 * 		5.2. Public
 * 		5.3. Protected
 * 		5.4. Private
 * 6. Traits
 * 7. Constructor and Initialisation
 * 8. Instance methods
 * 		8.1. Yii interface implementation
 * 		8.2. Yii parent class overriden methods
 * 		8.3. CMG interface implementation
 * 		8.4. CMG parent class overriden methods
 * 		8.6. Current class methods
 * 9. Class closure
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
 * MinObjectTemplate Entity
 *
 * @property short $<integer member>
 * @property integer $<integer member>
 * @property long $<integer member>
 * @property float $<float member>
 * @property double $<float member>
 * @property string $<string member>
 * @property boolean $<boolean member>
 */
class MinObjectTemplate extends \yii\base\Object {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// <Object> ------------------------------

}
