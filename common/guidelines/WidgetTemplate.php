<?php
namespace cmsgears\core\common\guidelines;

/**
 * A widget extending CMG base widget can be further divided into below mentioned sections:
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
 * 		8.8. Current class methods - hasOne, hasMany following other methods
 * 9. Static methods
 * 		9.1. ActiveRecord overriden methods including getTableName
 * 		9.2. Yii parent class overriden methods
 * 		9.3. CMG parent class overriden methods
 * 		9.4. Current class methods
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

class WidgetTemplate extends \cmsgears\core\common\base\Widget {

	// Variables ---------------------------------------------------

	// Constants/Statics --

	// Public -------------

	// Private/Protected --

	// Constructor and Initialisation ------------------------------

	// yii\base\Object -------------------

	/**
	 * @inheritdoc
	 */
    public function init() {

		parent::init();

		// Init Code
	}

	// Instance Methods --------------------------------------------

	// yii\base\Widget -------------------

	/**
	 * @inheritdoc
	 */
    public function run() {

		return $this->renderWidget();
    }

	// WidgetTemplate --------------------

	public function renderWidget( $config = [] ) {

		// Render Views
	}
}

?>