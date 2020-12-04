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
use cmsgears\core\common\models\interfaces\base\IMultiSite;
use cmsgears\core\common\models\interfaces\resources\IContent;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;
use cmsgears\core\common\models\interfaces\resources\IVisual;
use cmsgears\core\common\models\interfaces\mappers\IFile;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\FeaturedTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\resources\VisualTrait;
use cmsgears\core\common\models\traits\mappers\FileTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * The comment model stores the comment for relevant parent models supporting comment feature.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $baseId
 * @property integer $bannerId
 * @property integer $videoId
 * @property integer $parentId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $parentType
 * @property string $title
 * @property string $name
 * @property string $email
 * @property string $mobile
 * @property string $phone
 * @property string $avatarUrl
 * @property string $websiteUrl
 * @property string $ip
 * @property integer $ipNum
 * @property string $agent
 * @property integer $status
 * @property string $type
 * @property integer $rate1
 * @property integer $rate2
 * @property integer $rate3
 * @property integer $rate4
 * @property integer $rate5
 * @property integer $rating
 * @property integer $order
 * @property boolean $pinned
 * @property boolean $featured
 * @property boolean $popular
 * @property boolean $anonymous
 * @property string $field1
 * @property string $field2
 * @property string $field3
 * @property string $field4
 * @property string $field5
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
class ModelComment extends \cmsgears\core\common\models\base\ModelResource implements IAuthor,
	IContent, IData, IFeatured, IFile, IGridCache, IMultiSite, IVisual {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Model Comments
	const TYPE_COMMENT		=  'comment'; // quick comment, discussion
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

	// Used for external docs
	public static $revStatusMap = [
		'New' => self::STATUS_NEW,
		'Spam' => self::STATUS_SPAM,
		'Blocked' => self::STATUS_BLOCKED,
		'Approved' => self::STATUS_APPROVED,
		'Trash' => self::STATUS_TRASH
	];

	// Used for url params
	public static $urlRevStatusMap = [
		'new' => self::STATUS_NEW,
		'spam' => self::STATUS_SPAM,
		'blocked' => self::STATUS_BLOCKED,
		'approved' => self::STATUS_APPROVED,
		'trash' => self::STATUS_TRASH
	];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CoreGlobal::TYPE_COMMENT;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AuthorTrait;
	use ContentTrait;
	use DataTrait;
	use FeaturedTrait;
	use FileTrait;
	use GridCacheTrait;
   	use MultiSiteTrait;
	use VisualTrait;

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
			[ [ 'parentId', 'parentType', 'content' ], 'required' ],
			[ [ 'id', 'content' ], 'safe' ],
			// Email
			[ 'email', 'email' ],
			// Text Limit
			[ [ 'phone', 'mobile' ], 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ [ 'parentType', 'type', 'ip' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'field1', 'field2', 'field3', 'field4', 'field5' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'name', 'email', 'agent' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ [ 'title', 'avatarUrl', 'websiteUrl' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			// Other
			[ [ 'avatarUrl', 'websiteUrl' ], 'url' ],
			[ [ 'pinned', 'featured', 'popular', 'anonymous', 'gridCacheValid' ], 'boolean' ],
			[ [ 'rate1', 'rate2', 'rate3', 'rate4', 'rate5' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'ipNum', 'status', 'rating', 'order' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'siteId', 'baseId', 'bannerId', 'videoId', 'parentId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'approvedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'title', 'name', 'email', 'mobile', 'phone', 'avatarUrl', 'websiteUrl' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'siteId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SITE ),
			'baseId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'bannerId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_BANNER ),
			'videoId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VIDEO ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'email' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
			'mobile' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MOBILE ),
			'phone' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PHONE ),
			'avatarUrl' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AVATAR_URL ),
			'websiteUrl' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_WEBSITE ),
			'ip' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_IP ),
			'ipNum' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_IP_NUM ),
			'agent' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AGENT_BROWSER ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'rating' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_RATING ),
			'order' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'pinned' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PINNED ),
			'featured' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FEATURED ),
			'popular' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_POPULAR ),
			'anonymous' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ANONYMOUS ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MESSAGE ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// yii\db\BaseActiveRecord

    /**
     * @inheritdoc
     */
	public function beforeSave( $insert ) {

	    if( parent::beforeSave( $insert ) ) {

			if( empty( $this->order ) || $this->order <= 0 ) {

				$this->order = 0;
			}

			// Default Type - Comment
			$this->type = $this->type ?? self::TYPE_COMMENT;

			// Generate Rating
			$this->generateRating();

	        return true;
	    }

		return false;
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
	public function getBase() {

		return $this->hasOne( ModelComment::class, [ 'id' => 'baseId' ] );
	}

	/**
	 * Return all the immediate child comments.
	 *
	 * @return ModelComment
	 */
	public function getChildren() {

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

	public function isNew() {

		return $this->status == self::STATUS_NEW;
	}

	public function isSpam() {

		return $this->status == self::STATUS_SPAM;
	}

	public function isBlocked() {

		return $this->status == self::STATUS_BLOCKED;
	}

	public function isApproved() {

		return $this->status == self::STATUS_APPROVED;
	}

	public function isTrash() {

		return $this->status == self::STATUS_TRASH;
	}

	public function generateRating() {

		$rate1 = $this->rate1 ?? 0;
		$rate2 = $this->rate2 ?? 0;
		$rate3 = $this->rate3 ?? 0;
		$rate4 = $this->rate4 ?? 0;
		$rate5 = $this->rate5 ?? 0;

		$sum = $rate1 + $rate2 + $rate3 + $rate4 + $rate5;

		if( $sum > 0 ) {

			$this->rating = floor( $sum/5 );
		}
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

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'site', 'creator', 'modifier' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the comments by type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by type.
	 */
	public static function queryByType( $parentId, $parentType, $config = [] ) {

		$type	= isset( $config[ 'type' ] ) ? $config[ 'type' ] : self::TYPE_COMMENT;
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
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by email.
	 */
	public static function queryL0Approved( $parentId, $parentType, $config = [] ) {

		$config[ 'type' ] = isset( $config[ 'type' ] ) ? $config[ 'type' ] : self::TYPE_COMMENT;

		return self::queryByType( $parentId, $parentType, $type, $config )->andWhere( [ 'baseId' => null ] );
	}

	// Read - Find ------------

	/**
	 * Find and return the comment for given user id.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $userId
	 * @return ModelComment
	 */
	public static function findByUserId( $parentId, $parentType, $userId, $config = [] ) {

		$type = isset( $config[ 'type' ] ) ? $config[ 'type' ] : self::TYPE_COMMENT;

		return static::find()->where( 'parentId=:pid AND parentType=:ptype AND createdBy=:uid AND type=:type', [ ':pid' => $parentId, ':ptype' => $parentType, ':uid' => $userId, ':type' => $type ] )->one();
	}

	/**
	 * Find and return the comments for given user id.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $userId
	 * @return ModelComment
	 */
	public static function findAllByUser( $parentId, $parentType, $userId, $config = [] ) {

		$type = isset( $config[ 'type' ] ) ? $config[ 'type' ] : self::TYPE_COMMENT;

		return static::find()->where( 'parentId=:pid AND parentType=:ptype AND createdBy=:uid AND type=:type', [ ':pid' => $parentId, ':ptype' => $parentType, ':uid' => $userId, ':type' => $type ] )->all();
	}

	/**
	 * Check whether comment already exist for given user id.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $userId
	 * @return boolean
	 */
	public static function isExistByUserId( $parentId, $parentType, $userId, $config = [] ) {

		$config[ 'type' ] = isset( $config[ 'type' ] ) ? $config[ 'type' ] : self::TYPE_COMMENT;

		$comment = static::findByUser( $parentId, $parentType, $userId, $config );

		return isset( $comment );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
