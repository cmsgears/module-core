<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ModelActivity Entity
 *
 * @property integer $id
 * @property integer $activityId
 * @property integer $parentId
 * @property string $parentType
 * @property integer $consumed 
 * @property integer $admin
 */
class ModelActivity extends CmgModel {

	// Instance Methods --------------------------------------------

	/**
	 * @return Activity - associated activity
	 */
	public function getActivity() {

    	return $this->hasOne( Activity::className(), [ 'id' => 'activityId' ] );
	}

	/**
	 * @return string representation of flag
	 */
	public function getConsumedStr() {

		return Yii::$app->formatter->asBoolean( $this->consumed ); 
	}

	/**
	 * @return string representation of flag
	 */
	public function getAdminStr() {

		return Yii::$app->formatter->asBoolean( $this->admin ); 
	}

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'activityId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'activityId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'parentType' ], 'string', 'min' => 1, 'max' => 100 ],
            [ [ 'consumed', 'admin' ], 'boolean' ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'activityId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVITY ),
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'consumed' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CONSUMED ),
			'admin' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ADMIN )
		];
	}

	// ModelActivity ---------------------

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_ACTIVITY;
	}

	// ModelActivity ---------------------

	// Read ------

	public static function findByParentId( $parentId ) {

		return self::find()->where( 'parentId=:pid', [ ':pid' => $parentId ] )->all();
	}

	// Delete ----

	/**
	 * Delete all entries related to a parent
	 */
	public static function deleteByParentId( $parentId ) {

		self::deleteAll( 'parentId=:id', [ ':id' => $parentId ] );
	}
}

?>