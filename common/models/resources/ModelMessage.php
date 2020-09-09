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
use cmsgears\core\common\models\interfaces\base\IMultiSite;
use cmsgears\core\common\models\interfaces\resources\IContent;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;
use cmsgears\core\common\models\interfaces\resources\IVisual;
use cmsgears\core\common\models\interfaces\mappers\IFile;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\resources\VisualTrait;
use cmsgears\core\common\models\traits\mappers\FileTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * The message model stores the messages for relevant parent models supporting message feature.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $baseId
 * @property integer $bannerId
 * @property integer $videoId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property string $parentType
 * @property string $title
 * @property string $type
 * @property string $ip
 * @property integer $ipNum
 * @property string $agent
 * @property boolean $consumed
 * @property boolean $trash
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class ModelMessage extends \cmsgears\core\common\models\base\ModelResource implements IAuthor,
	IContent, IData, IFile, IGridCache, IMultiSite, IVisual {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const TYPE_MESSAGE = CoreGlobal::TYPE_MESSAGE;

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CoreGlobal::TYPE_MESSAGE;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AuthorTrait;
	use ContentTrait;
	use DataTrait;
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
			// Text Limit
			[ [ 'parentType', 'type', 'ip' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'agent', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'content', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'consumed', 'trash', 'gridCacheValid' ], 'boolean' ],
			[ 'ipNum', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'siteId', 'baseId', 'bannerId', 'videoId', 'parentId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'title' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ADDRESS_TYPE ),
			'ip' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_IP ),
			'ipNum' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_IP_NUM ),
			'agent' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AGENT_BROWSER ),
			'consumed' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONSUMED ),
			'trash' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TRASH ),
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

			// Default Type - Message
			$this->type = $this->type ?? self::TYPE_MESSAGE;

	        return true;
	    }

		return false;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ModelMessage --------------------------

	/**
	 * Return the immediate parent message.
	 *
	 * @return ModelMessage
	 */
	public function getBase() {

		return $this->hasOne( ModelMessage::class, [ 'id' => 'baseId' ] );
	}

	/**
	 * Return all the immediate child messages.
	 *
	 * @return ModelMessage
	 */
	public function getChildren() {

		return $this->hasMany( ModelMessage::class, [ 'baseId' => 'id' ] );
	}

	public function isNew() {

		return !$this->consumed;
	}

	public function isConsumed() {

		return $this->consumed;
	}

	public function isTrash() {

		return $this->trash;
	}

	public function getConsumedStr() {

		return Yii::$app->formatter->asBoolean( $this->consumed );
	}

	public function getTrashStr() {

		return Yii::$app->formatter->asBoolean( $this->trash );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_MODEL_MESSAGE );
	}

	// CMG parent classes --------------------

	// ModelMessage --------------------------

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
	 * Return query to find the messages by type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by type.
	 */
	public static function queryByType( $parentId, $parentType, $config = [] ) {

		$type = isset( $config[ 'type' ] ) ? $config[ 'type' ] : self::TYPE_MESSAGE;

		return self::queryByParent( $parentId, $parentType, $config )->andWhere( [ 'type' => $type ] );
	}

	/**
	 * Return query to find top level messages.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by type.
	 */
	public static function queryL0ByType( $parentId, $parentType, $config = [] ) {

		$config[ 'type' ] = isset( $config[ 'type' ] ) ? $config[ 'type' ] : self::TYPE_MESSAGE;

		return self::queryByType( $parentId, $parentType, $type, $config )->andWhere( [ 'baseId' => null ] );
	}

	/**
	 * Return query to find the messages by user id.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $userId
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by type.
	 */
	public static function queryByUserIdType( $parentId, $parentType, $userId, $config = [] ) {

		$config[ 'type' ] = isset( $config[ 'type' ] ) ? $config[ 'type' ] : self::TYPE_MESSAGE;

		return self::queryByType( $parentId, $parentType, $type, $config )->andWhere( [ 'createdBy' => $userId ] );
	}

	/**
	 * Return query to find top level messages by user id.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $userId
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by type.
	 */
	public static function queryL0ByUserIdType( $parentId, $parentType, $userId, $config = [] ) {

		$config[ 'type' ] = isset( $config[ 'type' ] ) ? $config[ 'type' ] : self::TYPE_MESSAGE;

		return self::queryByType( $parentId, $parentType, $type, $config )->andWhere( [ 'baseId' => null, 'createdBy' => $userId ] );
	}

	/**
	 * Return query to find the child messages.
	 *
	 * @param integer $baseId
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query child messages.
	 */
	public static function queryByBaseIdType( $baseId, $config = [] ) {

		$type = isset( $config[ 'type' ] ) ? $config[ 'type' ] : self::TYPE_MESSAGE;

		return self::find()->where( [ 'baseId' => $baseId, 'type' => $type ] );
	}

	// Read - Find ------------

	/**
	 * Find and return the messages for given user id.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $userId
	 * @return ModelMessage
	 */
	public static function findByUserId( $parentId, $parentType, $userId, $config = [] ) {

		$config[ 'type' ] = isset( $config[ 'type' ] ) ? $config[ 'type' ] : self::TYPE_MESSAGE;

		return self::queryByUserIdType( $parentId, $parentType, $userId, $config )->all();
	}

	/**
	 * Find and return the messages for given user id.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $userId
	 * @return ModelMessage
	 */
	public static function findL0ByUserId( $parentId, $parentType, $userId, $config = [] ) {

		$config[ 'type' ] = isset( $config[ 'type' ] ) ? $config[ 'type' ] : self::TYPE_MESSAGE;

		return self::queryL0ByUserIdType( $parentId, $parentType, $userId, $config )->all();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
