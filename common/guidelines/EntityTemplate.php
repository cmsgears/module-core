<?php
/**
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 * @license https://www.cmsgears.org/license/
 * @package module
 * @subpackage core
 */
namespace cmsgears\core\common\guidelines;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

// Project Imports

/**
 * EntityTemplate can be used as template for model classes associated with database table.
 *
 * Entities extending \yii\db\ActiveRecord. can be further divided into below mentioned sections:
 * <ol>
 *	<li>Yii imports</li>
 *	<li>CMG imports</li>
 *	<li>Project imports</li>
 *	<li>Class definition</li>
 *	<li>Variables
 *		<ol>
 *			<li>Globals
 *				<ol>
 *					<li>Constants</li>
 *					<li>Public</li>
 *					<li>Protected</li>
 *				</ol>
 *			</li>
 *			<li>Variables
 *				<ol>
 *					<li>Public</li>
 *					<li>Protected</li>
 *					<li>Private</li>
 *				</ol>
 *			</li>
 *		</ol>
 *	</li>
 *	<li>Traits</li>
 *	<li>Constructor and Initialisation</li>
 *	<li>Instance methods
 *		<ol>
 *			<li>Yii interface implementation</li>
 *			<li>Yii parent class overridden methods
 *				<ol>
 *					<li>Yii base component overridden methods including behaviours</li>
 *					<li>Yii base model overridden methods including rules and labels</li>
 *				</ol>
 *			</li>
 *			<li>CMG interface implementation</li>
 *			<li>CMG parent class overridden methods</li>
 *			<li>Current class validators</li>
 *			<li>Current class methods - hasOne, hasMany following other methods</li>
 *		</ol>
 *	</li>
 *	<li>Static methods
 *		<ol>
 *			<li>Yii parent class overridden methods
 *				<ol>
 *					<li>ActiveRecord overriden methods including getTableName</li>
 *				</ol>
 *			</li>
 *			<li>CMG parent class overridden methods</li>
 *			<li>Current class methods
 *				<ol>
 *					<li>Read - query<method></li>
 *					<li>Read - find<method></li>
 *					<li>Create</li>
 *					<li>Update</li>
 *					<li>Delete</li>
 *				</ol>
 *			</li>
 *		</ol>
 *	</li>
 *	<li>Class closure</li>
 * </ol>
 *
 * EntityTemplate Entity
 *
 * @author Bhagwat Singh Chouhan <bhagwat.chouhan@gmail.com>
 * @since 1.0.0
 *
 * @property int - short $<short>
 * @property int $<integer>
 * @property int - long $<long>
 * @property float $<float>
 * @property float - double $<double>
 * @property string $<string>
 * @property bool $<boolean>
 * @property array $<array>
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
