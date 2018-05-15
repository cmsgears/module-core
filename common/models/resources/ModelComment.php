<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\resources;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\base\IAuthor;
use cmsgears\core\common\models\interfaces\base\IFeatured;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;
use cmsgears\core\common\models\interfaces\mappers\IFile;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\base\ModelResource;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\FeaturedTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\mappers\FileTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * The comment model stores the comment for relevant parent models supporting comment feature.
 *
 * @property integer $id
 * @property integer $baseId
 * @property integer $bannerId
 * @property integer $videoId
 * @property integer $parentId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $parentType
 * @property string $name
 * @property string $email
 * @property string $avatarUrl
 * @property string $websiteUrl
 * @property string $ip
 * @property integer $ipNum
 * @property string $agent
 * @property integer $status
 * @property string $type
 * @property integer $fragment
 * @property integer $rating
 * @property integer $order
 * @property boolean $pinned
 * @property boolean $featured
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $approvedAt
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class ModelComment extends ModelResource implements IAuthor, IData, IFeatured, IFile, IGridCache {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Model Comments
	const TYPE_COMMENT		=  'comment'; // quick comment
	const TYPE_REVIEW		=  'review'; // detailed review
	// User experience specific to application
	const TYPE_FEEDBACK		=  'feedback'; // enhancements, improvement
	const TYPE_TESTIMONIAL	=  'testimonial'; // user satisfaction

	const STATUS_NEW		=  500;
	const STATUS_SPAM		=  600;
	const STATUS_BLOCKED	=  700;
	const STATUS_APPROVED	=  800;
	const STATUS_TRASH		=  900;

	// Public -----------------

	public static $typeMap = [
		self::TYPE_COMMENT => 'Comment',
		self::TYPE_REVIEW => 'Review',
		self::TYPE_FEEDBACK => 'Feedback',
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

	public $captcha;

	// Protected --------------

	protected $modelType = CoreGlobal::TYPE_COMMENT;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AuthorTrait;
	use DataTrait;
	use FeaturedTrait;
	use FileTrait;
	use GridCacheTrait;

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
				'class' => AuthorBehavior::class
			],
			'timestampBehavior' => [
				'class' => TimestampBehavior::class,
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

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'parentId', 'parentType', 'name', 'email' ], 'required' ],
			[ [ 'id', 'content', 'data', 'gridCache' ], 'safe' ],
			// Email
			[ 'email', 'email' ],
			// Text Limit
			[ [ 'parentType', 'type', 'ip' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'name', 'email', 'agent' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ [ 'avatarUrl', 'websiteUrl' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			// Check captcha need for testimonial and review
			[ 'content', 'required', 'on' => [ self::TYPE_COMMENT, self::TYPE_TESTIMONIAL ] ],
			[ [ 'content', 'rating' ], 'required', 'on' => [ self::TYPE_REVIEW ] ],
			[ 'captcha', 'captcha', 'captchaAction' => '/core/site/captcha', 'on' => 'captcha' ],
			// Other
			[ [ 'avatarUrl', 'websiteUrl' ], 'url' ],
			[ [ 'pinned', 'featured', 'gridCacheValid' ], 'boolean' ],
			[ [ 'ipNum', 'status', 'fragment', 'rating', 'order' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'baseId', 'bannerId', 'videoId', 'parentId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'approvedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		// Enable captcha for non-logged in users
		$user = Yii::$app->user->getIdentity();

		if( !isset( $user ) ) {

			$rules[] = [ 'captcha', 'required' ];
		}

		// Trim Text
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
			'ipNum' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_IP_NUM ),
			'agent' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AGENT_BROWSER ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'rating' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_RATING ),
			'pinned' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PINNED ),
			'featured' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FEATURED ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MESSAGE ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ModelComment --------------------------

	/**
	 * Return the immediate parent comment.
	 *
	 * @return ModelComment
	 */
	public function getBaseComment() {

		return $this->hasOne( ModelComment::class, [ 'id' => 'baseId' ] );
	}

	/**
	 * Return all the immediate child comments.
	 *
	 * @return ModelComment
	 */
	public function getChildComments() {

		return $this->hasMany( ModelComment::class, [ 'baseId' => 'id' ] );
	}

	/**
	 * Returns string representation of status.
	 *
	 * @return string
	 */
	public function getStatusStr() {

		return self::$statusMap[ $this->status ];
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_MODEL_COMMENT );
	}

	// CMG parent classes --------------------

	// ModelComment --------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'creator', 'modifier' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the comments by type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $type
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by type.
	 */
	public static function queryByType( $parentId, $parentType, $type = ModelComment::TYPE_COMMENT, $config = [] ) {

		//$type	= isset( $config[ 'type' ] ) ? $config[ 'type' ] : self::TYPE_COMMENT;
		$status	= isset( $config[ 'status' ] ) ? $config[ 'status' ] : self::STATUS_APPROVED;

		return self::queryByParent( $parentId, $parentType, $config )->andWhere( [ 'type' => $type, 'status' => $status ] );
	}

	/**
	 * Return query to find the comments by parent type.
	 *
	 * @param string $parentType
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by parent type.
	 */
	public static function queryByParentType( $parentType, $config = [] ) {

		$type	= isset( $config[ 'type' ] ) ? $config[ 'type' ] : self::TYPE_COMMENT;
		$status	= isset( $config[ 'status' ] ) ? $config[ 'status' ] : self::STATUS_APPROVED;

		return self::find()->where( [ 'parentType' => $parentType, 'type' => $type, 'status' => $status ] );
	}

	/**
	 * Return query to find the child comments.
	 *
	 * @param integer $baseId
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query child comments.
	 */
	public static function queryByBaseId( $baseId, $config = [] ) {

		$type	= isset( $config[ 'type' ] ) ? $config[ 'type' ] : self::TYPE_COMMENT;
		$status	= isset( $config[ 'status' ] ) ? $config[ 'status' ] : self::STATUS_APPROVED;

		return self::find()->where( [ 'baseId' => $baseId, 'type' => $type, 'status' => $status ] );
	}

	/**
	 * Return query to find the comments by email.
	 *
	 * @param string $email
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by email.
	 */
	public static function queryByEmail( $email, $config = [] ) {

		return self::find()->where( [ 'email' => $email ] );
	}

	/**
	 * Return query to find top level approved comments.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $type
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by email.
	 */
	public static function queryL0Approved( $parentId, $parentType, $type = ModelComment::TYPE_COMMENT, $config = [] ) {

		return self::queryByType( $parentId, $parentType, $type, $config )->andWhere( [ 'baseId' => null ] );
	}

	// Read - Find ------------

	/**
	 * Find and return the comment for given user id.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $userId
	 * @param string $type
	 * @return ModelComment
	 */
	public static function findByUser( $parentId, $parentType, $userId, $type = ModelComment::TYPE_COMMENT ) {

		return static::find()->where( 'parentId=:pid AND parentType=:ptype AND createdBy=:uid AND type=:type', [ ':pid' => $parentId, ':ptype' => $parentType, ':uid' => $userId, ':type' => $type ] )->one();
	}

	/**
	 * Check whether comment already exist for given user id.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $userId
	 * @param string $type
	 * @return boolean
	 */
	public static function isExistByUser( $parentId, $parentType, $userId, $type = ModelComment::TYPE_COMMENT ) {

		$comment	= static::findByUser( $parentId, $parentType, $userId, $type );

		$isExist	= isset( $comment );

		return $isExist;
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
