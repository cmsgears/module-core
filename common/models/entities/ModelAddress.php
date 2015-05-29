<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ModelAddress Entity
 *
 * @property integer $addressId
 * @property integer $parentId
 * @property string $parentType
 * @property integer $type   
 */
class ModelAddress extends CmgEntity {

	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'addressId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'addressId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'type' ], 'number', 'integerOnly' => true, 'min' => 0 ],
            [ 'parentType', 'string', 'max' => 100 ]
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
		];
	}

	// ModelAddress ----------------------

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_FILE;
	}

	// ModelAddress ----------------------

	// Read ----

	/**
	 * @param int $parentId
	 * @param string $parentType
	 * @return array - ModelAddress by parent id and type
	 */
	public static function findByParentIdType( $parentId, $parentType ) {

		return self::find()->where( 'parentId=:id AND parentType=:type', [ ':id' => $parentId, ':type' => $parentType ] )->all();
	}

	// Delete ----

	/**
	 * Delete all the entries associated with the parent.
	 */
	public static function deleteByParentIdType( $parentId, $parentType ) {

		self::deleteAll( 'parentId=:id AND parentType=:type', [ ':id' => $parentId, ':type' => $parentType ] );
	}

	/**
	 * Delete all entries related to a address
	 */
	public static function deleteByAddressId( $addressId ) {

		self::deleteAll( 'fileId=:id', [ ':id' => $addressId ] );
	}
}

?>