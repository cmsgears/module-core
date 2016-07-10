<?php
namespace cmsgears\core\common\models\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\entities\Site;

/**
 * SiteAttribute Entity
 *
 * @property integer $id
 * @property integer $modelId
 * @property string $name
 * @property string $label
 * @property string $type
 * @property string $valueType
 * @property string $value
 */
class SiteAttribute extends \cmsgears\core\common\models\base\ModelAttribute {

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

	// SiteAttribute -------------------------

	public function getParent() {

		return $this->hasOne( Site::className(), [ 'id' => 'modelId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_SITE_ATTRIBUTE;
	}

	// CMG parent classes --------------------

	// SiteAttribute -------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}
