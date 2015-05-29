<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\traits\CreateModifyTrait;

/**
 * Newsletter Entity
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $description
 * @property longtext $content
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $lastSentAt
 */
class Newsletter extends NamedCmgEntity {

	use CreateModifyTrait;

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [

            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'createdAt',
 				'updatedAtAttribute' => 'modifiedAt'
            ]
        ];
    }

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'name' ], 'required' ],
            [ [ 'id', 'description', 'content' ], 'safe' ],
            [ 'name', 'string', 'min'=>1, 'max'=>100 ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt', 'lastSentAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'description' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'content' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CONTENT )
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_NEWSLETTER;
	}

	// Newsletter ------------------------

}

?>