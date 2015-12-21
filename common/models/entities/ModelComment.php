<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use \cmsgears\core\common\behaviors\AuthorBehavior;

use cmsgears\core\common\models\traits\CreateModifyTrait;

/**
 * ModelComment Entity
 *
 * @property integer $id
 * @property integer $baseId
 * @property integer $parentId 
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $parentType
 * @property string $name
 * @property string $email
 * @property string $ip
 * @property string $content
 * @property string $data
 * @property integer $status
 * @property integer $rating
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $approvedAt
 */
class ModelComment extends CmgModel {

	const STATUS_NEW		=  0;
	const STATUS_BLOCKED	= 10;
	const STATUS_APPROVED	= 20;

	public static $statusMap = [
		self::STATUS_NEW => 'New',
		self::STATUS_BLOCKED => 'Blocked',
		self::STATUS_APPROVED => 'Approved'
	];
	
	use CreateModifyTrait;

	// Instance Methods --------------------------------------------

	public function getBaseComment() {

		return $this->hasOne( ModelComment::className(), [ 'id' => 'baseId' ] );
	}

	public function getChildComments() {

		return $this->hasMany( ModelComment::className(), [ 'baseId' => 'id' ] );
	}

	public function getStatusStr() {

		return self::$statusMap[ $this->status ];
	}

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
            [ [ 'parentId', 'parentType', 'content' ], 'required' ],
            [ [ 'id', 'email', 'status', 'rating', 'data' ], 'safe' ],
            [ [ 'parentType', 'name', 'ip' ], 'string', 'min' => 1, 'max' => 100 ],
			[ [ 'parentId', 'baseId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt', 'approvedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

		// trim if required
		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'ip', 'name', 'email', 'rating' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'baseId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'email' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
			'ip' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_IP ),
			'status' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'rating' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_MESSAGE ),
			'content' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_MESSAGE ),
			'data' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DATA )
		];
	}

	// ModelTag --------------------------

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_COMMENT;
	}

	// ModelComment ----------------------

	// Read ------
}

?>