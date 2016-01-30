<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\behaviors\AuthorBehavior;

use cmsgears\core\common\models\traits\CreateModifyTrait;

/**
 * Newsletter Entity
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $description
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $lastSentAt
 * @property longtext $content
 * @property longtext $data
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
            'authorBehavior' => [
                'class' => AuthorBehavior::className()
			],
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'createdAt',
 				'updatedAtAttribute' => 'modifiedAt',
 				'value' => new Expression('NOW()')
            ]
        ];
    }

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {
		
		// model rules
        $rules = [
            [ [ 'name' ], 'required' ],
            [ [ 'id', 'description', 'content', 'data' ], 'safe' ],
            [ 'name', 'string', 'min' => 1, 'max' => CoreGlobal::TEXT_MEDIUM ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt', 'lastSentAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

		// trim if required
		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'name', 'description' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'description' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'content' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DATA )
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