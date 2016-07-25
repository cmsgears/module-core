<?php
namespace cmsgears\core\common\models\resources;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\traits\CreateModifyTrait;
use cmsgears\core\common\models\traits\ResourceTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;

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
 * @property string $type
 * @property string $name
 * @property string $email
 * @property string $avatarUrl
 * @property string $websiteUrl
 * @property string $ip
 * @property string $agent
 * @property short $status
 * @property short $fragment
 * @property short $rating
 * @property boolean $featured
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $approvedAt
 * @property string $content
 * @property string $data
 */
class ModelComment extends \cmsgears\core\common\models\base\Resource {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

    const TYPE_COMMENT      =  'comment';
    const TYPE_REVIEW       =  'review';
    const TYPE_TESTIMONIAL  =  'testimonial';

    const STATUS_NEW        =  500;
    const STATUS_SPAM       =  600;
    const STATUS_BLOCKED    =  700;
    const STATUS_APPROVED   =  800;
    const STATUS_TRASH    	=  900;

	// Public -----------------

    public static $typeMap = [
        self::TYPE_COMMENT => 'Comment',
        self::TYPE_REVIEW => 'Review',
        self::TYPE_TESTIMONIAL => 'Testimonial'
    ];

    public static $statusMap = [
        self::STATUS_NEW => 'New',
        self::STATUS_SPAM => 'Spam',
        self::STATUS_BLOCKED => 'Blocked',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_TRASH => 'Trash'
    ];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $mParentType	= CoreGlobal::TYPE_COMMENT;

	public $captcha;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

    use CreateModifyTrait;
    use DataTrait;
	use ResourceTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

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

	// yii\base\Model ---------

    /**
     * @inheritdoc
     */
    public function rules() {

        // model rules
        $rules = [
            [ [ 'parentId', 'parentType', 'name', 'email' ], 'required' ],
            [ [ 'id', 'content', 'data' ], 'safe' ],
            [ 'email', 'email' ],
            [ [ 'parentType', 'type', 'name', 'ip' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
            [ [ 'agent' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
            [ [ 'status', 'rating', 'fragment' ], 'number', 'integerOnly' => true, 'min' => 0 ],
            [ [ 'avatarUrl', 'websiteUrl' ], 'url' ],
            // Check captcha need for testimonial and review
            [ 'content', 'required', 'on' => [ self::TYPE_COMMENT, self::TYPE_TESTIMONIAL ] ],
            [ [ 'content', 'rating' ], 'required', 'on' => [ self::TYPE_REVIEW ] ],
            [ 'captcha', 'captcha', 'captchaAction' => '/core/site/captcha', 'on' => 'captcha' ],
            [ [ 'parentId', 'baseId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt', 'approvedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

		// Enable captcha for non-logged in users
		$user = Yii::$app->user->getIdentity();

		if( !isset( $user ) ) {

			$rules[] = [ 'captcha', 'required' ];
		}

        // trim if required
        if( Yii::$app->core->trimFieldValue ) {

            $trim[] = [ [ 'name', 'email', 'avatarUrl', 'websiteUrl' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

            return ArrayHelper::merge( $trim, $rules );
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'baseId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
            'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
            'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
            'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ADDRESS_TYPE ),
            'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
            'email' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
            'avatarUrl' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AVATAR_URL ),
            'websiteUrl' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_WEBSITE ),
            'ip' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_IP ),
            'agent' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AGENT_BROWSER ),
            'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
            'rating' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_RATING ),
            'featured' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FEATURED ),
            'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MESSAGE ),
            'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA )
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

    // ModelComment --------------------------

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

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_MODEL_COMMENT;
    }

	// CMG parent classes --------------------

	// ModelComment --------------------------

	// Read - Query -----------

	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'creator', 'modifier' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

    public static function queryByParentConfig( $parentId, $parentType, $config = [] ) {

		$type	= isset( $config[ 'type' ] ) ? $config[ 'type' ] : self::TYPE_COMMENT;
		$status	= isset( $config[ 'status' ] ) ? $config[ 'status' ] : self::STATUS_APPROVED;

        return self::queryByParent( $parentId, $parentType )->andWhere( [ 'type' => $type, 'status' => $status ] );
    }

    public static function queryByParentTypeConfig( $parentType, $config = [] ) {

		$type	= isset( $config[ 'type' ] ) ? $config[ 'type' ] : self::TYPE_COMMENT;
		$status	= isset( $config[ 'status' ] ) ? $config[ 'status' ] : self::STATUS_APPROVED;

        return self::find()->where( [ 'parentType' => $parentType, 'type' => $type, 'status' => $status ] );
    }

    public static function queryByBaseId( $baseId, $config = [] ) {

		$type	= isset( $config[ 'type' ] ) ? $config[ 'type' ] : self::TYPE_COMMENT;
		$status	= isset( $config[ 'status' ] ) ? $config[ 'status' ] : self::STATUS_APPROVED;

        return self::find()->where( [ 'baseId' => $baseId, 'type' => $type, 'status' => $status ] );
    }

    public static function queryByEmail( $email ) {

        return self::find()->where( [ 'email' => $email ] );
    }

	public static function queryL0Approved( $parentId, $parentType, $type ) {

		return self::queryByParentConfig( $parentId, $parentType, [ 'type' => $type ] )->andWhere( [ 'baseId' => null ] );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}
