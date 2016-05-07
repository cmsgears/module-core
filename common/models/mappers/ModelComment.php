<?php
namespace cmsgears\core\common\models\mappers;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\traits\CreateModifyTrait;
use cmsgears\core\common\models\traits\DataTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * ModelComment Entity
 *
 * @property long $id
 * @property long $baseId
 * @property long $parentId
 * @property long $createdBy
 * @property long $modifiedBy
 * @property string $parentType
 * @property string $name
 * @property string $email
 * @property string $avatarUrl
 * @property string $websiteUrl
 * @property string $ip
 * @property string $agent
 * @property short $status
 * @property short $type
 * @property short $rating
 * @property boolean $featured
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $approvedAt
 * @property string $content
 * @property string $data
 */
class ModelComment extends \cmsgears\core\common\models\base\CmgModel {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    const TYPE_COMMENT      =   0;
    const TYPE_REVIEW       =  10;
    const TYPE_TESTIMONIAL  =  20;

    const STATUS_NEW        =  500;
    const STATUS_SPAM       =  600;
    const STATUS_BLOCKED    =  700;
    const STATUS_APPROVED   =  800;
    const STATUS_TRASH    	=  900;

    public static $statusMap = [
        self::STATUS_NEW => 'New',
        self::STATUS_SPAM => 'Spam',
        self::STATUS_BLOCKED => 'Blocked',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_TRASH => 'Trash'
    ];

    // Public -------------

    public $captcha;

    // Private/Protected --

    // Traits ------------------------------------------------------

    use CreateModifyTrait;
    use DataTrait;

    // Constructor and Initialisation ------------------------------

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
            [ [ 'parentId', 'parentType', 'name', 'email' ], 'required' ],
            [ [ 'id', 'content', 'data' ], 'safe' ],
            [ 'email', 'email' ],
            [ [ 'parentType', 'name', 'ip' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ [ 'agent' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->extraLargeText ],
            [ [ 'status', 'rating', 'type' ], 'number', 'integerOnly' => true, 'min' => 0 ],
            [ [ 'avatarUrl', 'websiteUrl' ], 'url' ],
            [ 'content', 'required', 'on' => [ 'testimonial' ] ],
            [ 'rating', 'required', 'on' => [ 'review' ] ],
            [ 'captcha', 'captcha', 'captchaAction' => '/cmgcore/site/captcha', 'on' => 'captcha' ],
            [ [ 'parentId', 'baseId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
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
            'featured' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_FEATURED ),
            'content' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_MESSAGE ),
            'data' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DATA )
        ];
    }

    // ModelComment ----------------------

    public function getBaseComment() {

        return $this->hasOne( ModelComment::className(), [ 'id' => 'baseId' ] );
    }

    public function getChildComments() {

        return $this->hasMany( ModelComment::className(), [ 'baseId' => 'id' ] );
    }

    public function getStatusStr() {

        return self::$statusMap[ $this->status ];
    }

    public function getFeaturedStr() {

        return Yii::$app->formatter->asBoolean( $this->featured );
    }

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_MODEL_COMMENT;
    }

    // ModelComment ----------------------

    // Create -------------

    // Read ---------------

    public static function queryByParent( $parentId, $parentType, $type = self::TYPE_COMMENT, $status = self::STATUS_APPROVED ) {

        return self::find()->where( [ 'parentId' => $parentId, 'parentType' => $parentType, 'type' => $type, 'status' => $status ] );
    }

    public static function queryByParentType( $parentType, $type = self::TYPE_COMMENT, $status = self::STATUS_APPROVED ) {

        return self::find()->where( [ 'parentType' => $parentType, 'type' => $type, 'status' => $status ] );
    }

    public static function queryByBaseId( $baseId, $type = self::TYPE_COMMENT, $status = self::STATUS_APPROVED ) {

        return self::find()->where( [ 'baseId' => $baseId, 'type' => $type, 'status' => $status ] );
    }

    public static function queryByEmail( $email ) {

        return self::find()->where( [ 'email' => $email ] );
    }

    // Update -------------

    // Delete -------------
}

?>