<?php
namespace cmsgears\core\common\models\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Address;

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
class ModelAddress extends \cmsgears\core\common\models\base\CmgModel {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    // yii\base\Component ----------------

    // yii\base\Model --------------------

     /**
     * @inheritdoc
     */
    public function rules() {

        return [
            [ [ 'addressId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'parentType', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ 'order', 'number', 'integerOnly' => true, 'min' => 0 ],
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

    // ModelAddress-----------------------

    /**
     * @return Address - associated address
     */
    public function getAddress() {

        return $this->hasOne( Address::className(), [ 'id' => 'addressId' ] );
    }

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_MODEL_ADDRESS;
    }

    // ModelAddress-----------------------

    // Create -------------

    // Read ---------------

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

    // Update -------------

    // Delete -------------

    /**
     * Delete all entries related to a address
     */
    public static function deleteByAddressId( $addressId ) {

        self::deleteAll( 'addressId=:id', [ ':id' => $addressId ] );
    }
}

?>