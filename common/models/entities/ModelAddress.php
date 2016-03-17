<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ModelAddress Entity
 *
 * @property integer $id
 * @property integer $addressId
 * @property integer $parentId
 * @property string $parentType
 * @property integer $type
 * @property short $order
 * @property short $active
 */
class ModelAddress extends CmgModel {

	// Instance Methods --------------------------------------------

	/**
	 * @return Address - associated address
	 */
	public function getAddress() {

    	return $this->hasOne( Address::className(), [ 'id' => 'addressId' ] );
	}

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'addressId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'id', 'active' ], 'safe' ],
            [ [ 'addressId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'parentType', 'string', 'min' => 1, 'max' => 100 ],
            [ [ 'type', 'order' ], 'number', 'integerOnly' => true, 'min' => 0 ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'addressId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ADDRESS ),
			'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ADDRESS_TYPE ),
			'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
		];
	}

	// ModelAddress ----------------------

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_ADDRESS;
	}

	// ModelAddress ----------------------

	// Read ------

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
    
    public static function findByParentId( $parentId ) {
        
        return self::find()->where( 'parentId=:pid',[ ':pid' => $parentId ] )->all();
    }

	// Delete ----

	/**
	 * Delete all entries related to a address
	 */
	public static function deleteByAddressId( $addressId ) {

		self::deleteAll( 'addressId=:id', [ ':id' => $addressId ] );
	}
}

?>