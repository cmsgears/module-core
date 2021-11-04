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
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\base\IApproval;
use cmsgears\core\common\models\interfaces\base\IAuthor;
use cmsgears\core\common\models\interfaces\base\IFeatured;
use cmsgears\core\common\models\interfaces\base\IMultiSite;
use cmsgears\core\common\models\interfaces\base\INameType;
use cmsgears\core\common\models\interfaces\base\IOwner;
use cmsgears\core\common\models\interfaces\base\ISlugType;
use cmsgears\core\common\models\interfaces\base\IVisibility;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;
use cmsgears\core\common\models\interfaces\resources\ITemplate;
use cmsgears\core\common\models\interfaces\mappers\IFile;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\traits\base\ApprovalTrait;
use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\FeaturedTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\base\NameTypeTrait;
use cmsgears\core\common\models\traits\base\OwnerTrait;
use cmsgears\core\common\models\traits\base\SlugTypeTrait;
use cmsgears\core\common\models\traits\base\VisibilityTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\resources\TemplateTrait;
use cmsgears\core\common\models\traits\mappers\FileTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Gallery can be used to group files especially images.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $userId
 * @property integer $templateId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $code
 * @property string $slug
 * @property string $type
 * @property string $icon
 * @property string $title
 * @property string $description
 * @property integer $status
 * @property integer $visibility
 * @property integer $order
 * @property boolean $pinned
 * @property boolean $featured
 * @property boolean $popular
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
class Gallery extends \cmsgears\core\common\models\base\Resource implements IApproval, IAuthor,
	IData, IFeatured, IFile, IGridCache, IMultiSite, INameType, IOwner, ISlugType, ITemplate, IVisibility {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CoreGlobal::TYPE_GALLERY;

	// Private ----------------

	// Traits ------------------------------------------------------

	use ApprovalTrait;
	use AuthorTrait;
	use DataTrait;
	use FeaturedTrait;
	use FileTrait;
	use GridCacheTrait;
	use MultiSiteTrait;
	use NameTypeTrait;
	use OwnerTrait;
	use SlugTypeTrait;
	use TemplateTrait;
	use VisibilityTrait;

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
			'sluggableBehavior' => [
				'class' => SluggableBehavior::class,
				'attribute' => 'name',
				'slugAttribute' => 'slug', // Unique for Site Id
				'immutable' => true,
				'ensureUnique' => true,
				'uniqueValidator' => [ 'targetAttribute' => [ 'siteId', 'slug' ] ]
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
			[ [ 'siteId', 'name' ], 'required' ],
			[ [ 'id', 'content' ], 'safe' ],
			// Unique
			[ 'code', 'unique' ],
			[ 'slug', 'unique', 'targetAttribute' => [ 'siteId', 'slug' ], 'message' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SLUG ) ],
			// Text Limit
			[ 'type', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'code', 'icon' ], 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'slug', 'string', 'min' => 0, 'max' => Yii::$app->core->xxLargeText ],
			[ 'title', 'string', 'min' => 0, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 0, 'max' => Yii::$app->core->xtraLargeText ],
			//Other
			[ [ 'pinned', 'featured', 'popular', 'gridCacheValid' ], 'boolean' ],
			[ 'templateId', 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'status', 'visibility' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'siteId', 'userId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'title', 'description' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'templateId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TEMPLATE ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'code' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CODE ),
			'slug' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'visibility' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VISIBILITY ),
			'order' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'pinned' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PINNED ),
			'featured' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FEATURED ),
			'popular' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_POPULAR ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
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

			if( $this->templateId <= 0 ) {

				$this->templateId = null;
			}

			// Default Order - zero
			if( empty( $this->order ) || $this->order <= 0 ) {

				$this->order = 0;
			}

			// Default Type - Default
			$this->type = $this->type ?? CoreGlobal::TYPE_DEFAULT;

			// Default Visibility - Private
			$this->visibility = $this->visibility ?? self::VISIBILITY_PUBLIC;

			return true;
		}

		return false;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Gallery -------------------------------

	/**
	 * The method belongsTo check whether the gallery belongs to given model. The model
	 * must have galleryId column.
	 *
	 * @return boolean
	 */
	public function belongsTo( $model ) {

		return $this->id == $model->galleryId;
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_GALLERY );
	}

	// CMG parent classes --------------------

	// Gallery -------------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'site', 'template', 'creator', 'modifier' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
