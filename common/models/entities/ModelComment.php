<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
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
 * @property string $avatarUrl
 * @property string $websiteUrl
 * @property string $ip
 * @property string $agent
 * @property integer $status
 * @property integer $type
 * @property integer $rating
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $approvedAt
 * @property string $content
 * @property string $data
 */
class ModelComment extends CmgModel {

    const TYPE_COMMENT      =   0;
    const TYPE_REVIEW       =  10;
    const TYPE_TESTIMONIAL  =  20;

	const STATUS_NEW		=  500;
	const STATUS_BLOCKED	=  750;
	const STATUS_APPROVED	= 1000;

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
            [ [ 'parentId', 'parentType', 'name', 'email' ], 'required' ],
            [ [ 'id', 'agent', 'status', 'rating', 'content', 'data' ], 'safe' ],
            [ 'email', 'email' ],
            [ [ 'parentType', 'name', 'ip' ], 'string', 'min' => 1, 'max' => 100 ],
			[ [ 'parentId', 'baseId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'rating' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'avatarUrl', 'websiteUrl' ], 'url' ],
            [ [ 'createdAt', 'modifiedAt', 'approvedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

		// trim if required
		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'name', 'email','avatarUrl', 'websiteUrl' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
			'avatarUrl' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_AVATAR_URL ),
			'websiteUrl' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_WEBSITE ),
			'ip' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_IP ),
			'agent' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_AGENT_BROWSER ),
			'status' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'rating' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_RATING ),
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

	public static function findByBaseId( $baseId, $commentType, $status = self::STATUS_APPROVED ) {

		return self::find()->where( [ 'baseId' => $baseId, 'type' => $commentType, 'status' => $status ] )->all();
	}

	public static function findByParent( $parentId, $parentType, $commentType, $status = self::STATUS_APPROVED ) {

		return self::find()->where( [ 'parentId' => $parentId, 'parentType' => $parentType, 'type' => $commentType, 'status' => $status ] )->all();
	}

	public static function findByParentType( $parentType, $commentType, $status = self::STATUS_APPROVED ) {

		return self::find()->where( [ 'parentType' => $parentType, 'type' => $commentType, 'status' => $status ] )->all();
	}
}

?>