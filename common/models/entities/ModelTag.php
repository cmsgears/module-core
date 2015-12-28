<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ModelTag Entity
 *
 * @property integer $id
 * @property integer $tagId
 * @property integer $parentId
 * @property string $parentType
 * @property short $order
 * @property short $active
 */
class ModelTag extends CmgModel {

	// Instance Methods --------------------------------------------

	public function getTag() {

		return $this->hasOne( Tag::className(), [ 'id' => 'tagId' ] );
	}
	
	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'tagId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'id', 'active' ], 'safe' ],
            [ [ 'tagId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'parentType', 'string', 'min' => 1, 'max' => 100 ],
            [ 'order', 'number', 'integerOnly' => true, 'min' => 0 ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'tagId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TAG ),
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
		];
	}

	// ModelTag --------------------------

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_TAG;
	}

	// ModelTag --------------------------

	// Read ----

	public static function findByTagId( $parentId, $parentType, $tagId ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND tagId=:tid', [ ':pid' => $parentId, ':ptype' => $parentType, ':tid' => $tagId ] )->one(); 
	}

	// Delete ----

	/**
	 * Delete all entries related to a tag
	 */
	public static function deleteByTagId( $tagId ) {

		self::deleteAll( 'tagId=:tid', [ ':tid' => $tagId ] );
	}
}

?>