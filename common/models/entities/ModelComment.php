<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ModelComment Entity
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $baseId
 * @property integer $parentId
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

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

		$trim		= [];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'ip', 'name', 'email', 'rating' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];
		}

        $rules = [
            [ [ 'parentId', 'parentType', 'content' ], 'required' ],
            [ [ 'id', 'name', 'email', 'ip', 'data', 'status', 'rating' ], 'safe' ],
            [ 'parentType', 'string', 'min' => 1, 'max' => 100 ],
			[ [ 'parentId', 'baseId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt', 'approvedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

		if( Yii::$app->cmgCore->trimFieldValue ) {

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
			'content' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_MESSAGE ),
			'status' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'rating' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_MESSAGE )
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