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
            [ 'id', 'safe' ],
            [ [ 'addressId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'type' ], 'number', 'integerOnly' => true, 'min' => 0 ],
            [ 'parentType', 'string', 'min' => 1, 'max' => 100 ]
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
			'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ADDRESS_TYPE )
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

		return self::find()->where( 'parentId=:id AND parentType=:ptype AND type=:type', [ ':id' => $parentId, ':ptype' => $parentType, ':type' => $type ] )->one();
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