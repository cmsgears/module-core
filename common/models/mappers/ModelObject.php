<?php
namespace cmsgears\core\common\models\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\ObjectData;

/**
 * ModelObject Entity
 *
 * @property long $id
 * @property long $objectId
 * @property long $parentId
 * @property string $parentType
 * @property short $order
 * @property boolean $active
 */
class ModelObject extends \cmsgears\core\common\models\base\ParentResource {

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

    /**
     * @inheritdoc
     */
    public function rules() {

        return [
            [ [ 'objectId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ 'parentType', 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ 'order', 'number', 'integerOnly' => true, 'min' => 0 ],
            [ [ 'active' ], 'boolean' ],
            [ [ 'objectId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'objectId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_OBJECT ),
            'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
            'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
            'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
            'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ModelObject ---------------------------

    /**
     * @return ObjectData - associated object
     */
    public function getObject() {

        return $this->hasOne( ObjectData::className(), [ 'id' => 'objectId' ] );
    }

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	// CMG parent classes --------------------

	// ModelObject ---------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}

?>