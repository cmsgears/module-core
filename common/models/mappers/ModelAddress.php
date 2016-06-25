<?php
namespace cmsgears\core\common\models\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Address;

use cmsgears\core\common\models\traits\ParentTypeTrait;

/**
 * ModelAddress Entity
 *
 * @property long $id
 * @property long $addressId
 * @property long $parentId
 * @property string $parentType
 * @property short $type
 * @property short $order
 * @property boolean $active
 */
class ModelAddress extends \cmsgears\core\common\models\base\Mapper {

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

	use ParentTypeTrait;

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
            [ [ 'addressId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'parentType' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ [ 'order', 'type' ], 'number', 'integerOnly' => true, 'min' => 0 ],
            [ [ 'active' ], 'boolean' ],
            [ [ 'addressId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'addressId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ADDRESS ),
            'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
            'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
            'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ADDRESS_TYPE ),
            'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
            'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

    // ModelAddress --------------------------

    /**
     * @return Address - associated address
     */
    public function getAddress() {

        return $this->hasOne( Address::className(), [ 'id' => 'addressId' ] );
    }

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_MODEL_ADDRESS;
    }

	// CMG parent classes --------------------

	// ModelAddress --------------------------

	// Read - Query -----------

	// Read - Find ------------

    /**
     * @param int $parentId
     * @param string $parentType
     * @param string $type
     * @return ModelAddress by parent id, parent type and type
     */
    public static function findByType( $parentId, $parentType, $type ) {

        return self::find()->where( 'parentId=:pid AND parentType=:ptype AND type=:type', [ ':pid' => $parentId, ':ptype' => $parentType, ':type' => $type ] )->all();
    }

    /**
     * Models Supporting one address for same type.
     */
    public static function findFirstByType( $parentId, $parentType, $type ) {

        return self::find()->where( 'parentId=:pid AND parentType=:ptype AND type=:type', [ ':pid' => $parentId, ':ptype' => $parentType, ':type' => $type ] )->one();
    }

    public static function findByAddressId( $parentId, $parentType, $addressId ) {

        return self::find()->where( 'parentId=:pid AND parentType=:ptype AND addressId=:aid', [ ':pid' => $parentId, ':ptype' => $parentType, ':aid' => $addressId ] )->one();
    }

	// Create -----------------

	// Update -----------------

	// Delete -----------------

    /**
     * Delete all entries related to a address
     */
    public static function deleteByAddressId( $addressId ) {

        self::deleteAll( 'addressId=:id', [ ':id' => $addressId ] );
    }
}

?>