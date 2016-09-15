<?php
namespace cmsgears\core\common\guidelines;

/**
 * A model extending EntityService can be further divided into below mentioned sections:
 * 1. Yii imports
 * 2. CMG imports
 * 3. Project imports
 * 4. Trait definition
 * 5. Instance methods
 * 		5.1. Yii parent class overriden methods
 * 		5.2. CMG interface implementation
 * 		5.3. CMG parent class overriden methods
 * 		5.4. Current class methods - Read(DataProvider, Models, Lists, Maps), Create, Update, Delete
 * 6. Static methods
 * 		6.1. CMG parent class overriden methods
 * 		6.2. Current class methods - Read(DataProvider, Models, Lists, Maps), Create, Update, Delete
 * 7. Trait closure
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

trait ServiceTraitTemplate {

    // Instance methods --------------------------------------------

    // Yii parent classes --------------------

    // CMG interfaces ------------------------

    // CMG parent classes --------------------

    // <Trait> -------------------------------

    // Data Provider ------

    // Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

    // Read - Others ---

    // Create -------------

    // Update -------------

    // Delete -------------

    // Static Methods ----------------------------------------------

    // CMG parent classes --------------------

    // <Trait> -------------------------------

    // Data Provider ------

    // Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

    // Read - Others ---

    // Create -------------

    // Update -------------

    // Delete -------------
}
