<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ModelOption Entity
 *
 * @property integer $id
 * @property integer $optionId
 * @property integer $parentId
 * @property string $parentType
 * @property short $order
 * @property short $active
 */
class ModelOption extends CmgModel {

	// Instance Methods --------------------------------------------

	/**
	 * @return Option - associated option
	 */
	public function getCategory() {

    	return $this->hasOne( Category::className(), [ 'id' => 'optionId' ] );
	}

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'optionId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'id', 'active' ], 'safe' ],
            [ [ 'optionId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'parentType' ], 'string', 'min' => 1, 'max' => 100 ],
            [ 'order', 'number', 'integerOnly' => true, 'min' => 0 ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'optionId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_OPTION ),
			'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
		];
	}

	// ModelCategory ---------------------

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_OPTION;
	}

	// ModelCategory ---------------------

	// Read ------

	public static function findByOptionId( $parentId, $parentType, $optionId ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND optionId=:cid', [ ':pid' => $parentId, ':ptype' => $parentType, ':cid' => $optionId ] )->one(); 
	}

	public static function findActiveByParentId( $parentId ) {

		return self::find()->where( 'parentId=:pid AND active=1', [ ':pid' => $parentId ] )->all();
	}

	public static function findActiveByOptionIdParentType( $optionId, $parentType ) {

		return self::find()->where( 'optionId=:cid AND parentType=:ptype AND active=1', [ ':cid' => $optionId, ':ptype' => $parentType ] )->all(); 
	}

	public static function findActiveByParentIdParentType( $parentId, $parentType ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND active=1', [ ':pid' => $parentId, ':ptype' => $parentType ] )->all();
	} 

	// Delete ----

	/**
	 * Delete all entries related to a category
	 */
	public static function deleteByOptionId( $optionId ) {
		
		self::deleteAll( 'optionId=:id', [ ':id' => $optionId ] );
	}
}

?>