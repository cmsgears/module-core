<?php
namespace cmsgears\core\common\guidelines;

/**
 * A widget extending Widget can be further divided into below mentioned sections:
 * 1. Yii imports
 * 2. CMG imports
 * 3. Project imports
 * 4. Class definition
 * 5. Variables
 *		5.1. Globals
 *				5.1.1. Constants
 *				5.1.2. Public
 *				5.1.3. Protected
 *		5.2. Variables
 *				5.2.1. Public
 *				5.2.2. Protected
 *				5.2.3. Private
 * 6. Traits
 * 7. Constructor and Initialisation
 * 8. Instance methods
 *		8.1. Yii interface implementation
 *		8.2. Yii parent class overriden methods
 *				8.2.1. Yii base widget overriden methods
 *		8.3. CMG interface implementation
 *		8.4. CMG parent class overriden methods
 *				8.4.1. CMG base widget overriden methods
 *		8.5. Current class methods
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
 * WidgetTemplate Entity
 *
 * @property short $<integer member>
 * @property integer $<integer member>
 * @property long $<integer member>
 * @property float $<float member>
 * @property double $<float member>
 * @property string $<string member>
 * @property boolean $<boolean member>
 */
class WidgetTemplate extends \cmsgears\core\common\base\Widget {

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

	// yii\base\Widget --------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// cmsgears\core\common\base\Widget

	public function renderWidget( $config = [] ) {

		// Render Views
	}

	// <Widget> ------------------------------

}
