<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ModelTag Entity
 *
 * @property int $tagId
 * @property int $parentId
 * @property string $parentType
 */
class ModelTag extends CmgEntity {

	// Instance Methods --------------------------------------------

	public function getTag() {

		return $this->hasOne( Tag::className(), [ 'id' => 'tagId' ] )->from( CoreTables::TABLE_TAG . ' tag' );
	}
	
	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'tagId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'tagId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'parentType', 'string', 'min' => 1, 'max' => 100 ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'tagId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TAG ),
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE )
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
	
	/**
	 * @return array - categories by given parent id and type.
	 */
	public static function findByParentIdType( $id, $type ) {

		return self::find()->where( 'parentId=:id AND parentType=:type', [ ':id' => $id, ':type' => $type ] )->all();
	}

	// Delete ----

	/**
	 * Delete categories by given parent id and type.
	 */
	public static function deleteByParentIdType( $id, $type ) {

		self::deleteAll( 'parentId=:id AND parentType=:type', [ ':id' => $id, ':type' => $type ] );
	}
}

?>