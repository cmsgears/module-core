<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\entities;

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
use cmsgears\core\common\models\interfaces\resources\IComment;
use cmsgears\core\common\models\interfaces\resources\IContent;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;
use cmsgears\core\common\models\interfaces\resources\IHierarchy;
use cmsgears\core\common\models\interfaces\resources\ISocialLink;
use cmsgears\core\common\models\interfaces\resources\IMeta;
use cmsgears\core\common\models\interfaces\resources\ITemplate;
use cmsgears\core\common\models\interfaces\resources\IVisual;
use cmsgears\core\common\models\interfaces\mappers\ICategory;
use cmsgears\core\common\models\interfaces\mappers\IFile;
use cmsgears\core\common\models\interfaces\mappers\IGallery;
use cmsgears\core\common\models\interfaces\mappers\IObject;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\base\Entity;
use cmsgears\core\common\models\resources\ObjectMeta;
use cmsgears\core\common\models\mappers\ModelObject;

use cmsgears\core\common\models\traits\base\ApprovalTrait;
use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\FeaturedTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\base\NameTypeTrait;
use cmsgears\core\common\models\traits\base\OwnerTrait;
use cmsgears\core\common\models\traits\base\SlugTypeTrait;
use cmsgears\core\common\models\traits\base\VisibilityTrait;
use cmsgears\core\common\models\traits\resources\CommentTrait;
use cmsgears\core\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\resources\HierarchyTrait;
use cmsgears\core\common\models\traits\resources\SocialLinkTrait;
use cmsgears\core\common\models\traits\resources\MetaTrait;
use cmsgears\core\common\models\traits\resources\TemplateTrait;
use cmsgears\core\common\models\traits\resources\VisualTrait;
use cmsgears\core\common\models\traits\mappers\CategoryTrait;
use cmsgears\core\common\models\traits\mappers\FileTrait;
use cmsgears\core\common\models\traits\mappers\GalleryTrait;
use cmsgears\core\common\models\traits\mappers\ObjectTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * ObjectData Entity
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $themeId
 * @property integer $templateId
 * @property integer $userId
 * @property integer $parentId
 * @property integer $avatarId
 * @property integer $bannerId
 * @property integer $mbannerId
 * @property integer $videoId
 * @property integer $galleryId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $icon
 * @property string $texture
 * @property string $title
 * @property string $description
 * @property string $classPath
 * @property string $viewPath
 * @property string $link
 * @property integer $status
 * @property integer $visibility
 * @property integer $order
 * @property boolean $pinned
 * @property boolean $featured
 * @property boolean $popular
 * @property boolean $shared
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $htmlOptions
 * @property string $summary
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class ObjectData extends Entity implements IApproval, IAuthor, ICategory, IComment, IContent,
	IData, IFeatured, IFile, IGallery, IGridCache, IHierarchy, IMeta, IMultiSite, INameType,
	IObject, IOwner, ISlugType, ISocialLink, ITemplate, IVisibility, IVisual {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CoreGlobal::TYPE_OBJECT;

	protected $testOwner = false;

	protected $metaClass;

	// Private ----------------

	// Traits ------------------------------------------------------

	use ApprovalTrait;
	use AuthorTrait;
	use CategoryTrait;
	use CommentTrait;
	use ContentTrait;
	use DataTrait;
	use FeaturedTrait;
	use FileTrait;
	use GalleryTrait;
	use GridCacheTrait;
	use HierarchyTrait;
	use MetaTrait;
	use MultiSiteTrait;
	use NameTypeTrait;
	use ObjectTrait;
	use OwnerTrait;
	use SlugTypeTrait;
	use SocialLinkTrait;
	use TemplateTrait;
	use VisibilityTrait;
	use VisualTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( $config = [] ) {

		$this->metaClass = ObjectMeta::class;

		parent::__construct();
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	/**
	 * @inheritdoc
	 */
	public function behaviors() {

		return [
			AuthorBehavior::class,
			'sluggableBehavior' => [
				'class' => SluggableBehavior::class,
				'attribute' => 'name',
				'slugAttribute' => 'slug', // Unique for combination of Site Id and Theme Id
				'immutable' => true,
				'ensureUnique' => true,
				'uniqueValidator' => [ 'targetAttribute' => [ 'siteId', 'themeId', 'type', 'slug' ] ]
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
			[ [ 'name', 'type' ], 'required' ],
			[ [ 'id', 'htmlOptions', 'summary', 'content' ], 'safe' ],
			// Unique
			// Unique name and slug
			//[ 'name', 'unique', 'targetAttribute' => [ 'siteId', 'themeId', 'type', 'name' ], 'message' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NAME ) ],
			[ 'slug', 'unique', 'targetAttribute' => [ 'siteId', 'themeId', 'type', 'slug' ], 'message' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SLUG ) ],
			// Text Limit
			[ 'type', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'icon', 'texture' ], 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ 'name', 'string', 'min' => 0, 'max' => Yii::$app->core->xLargeText ],
			[ 'slug', 'string', 'min' => 0, 'max' => Yii::$app->core->xxLargeText ],
			[ [ 'title', 'classPath', 'viewPath', 'link' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 0, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'pinned', 'featured', 'popular', 'shared', 'gridCacheValid' ], 'boolean' ],
			[ [ 'visibility', 'status', 'order' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'themeId', 'templateId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'siteId', 'userId', 'avatarId', 'bannerId', 'mbannerId', 'videoId', 'galleryId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
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
			'themeId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_THEME ),
			'templateId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TEMPLATE ),
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'avatarId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AVATAR ),
			'bannerId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_BANNER ),
			'mbannerId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_BANNER_M ),
			'videoId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VIDEO ),
			'galleryId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GALLERY ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'slug' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'texture' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TEXTURE ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'classPath' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CLASSPATH ),
			'viewPath' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VIEW_PATH ),
			'link' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LINK ),
			'visibility' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VISIBILITY ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'order' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'pinned' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PINNED ),
			'featured' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FEATURED ),
			'shared' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SHARED ),
			'htmlOptions' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_HTML_OPTIONS ),
			'summary' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SUMMARY ),
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

			if( $this->siteId <= 0 ) {

				$this->siteId = null;
			}

			if( $this->themeId <= 0 ) {

				$this->themeId = null;
			}

			if( $this->templateId <= 0 ) {

				$this->templateId = null;
			}

			if( $this->userId <= 0 ) {

				$this->userId = null;
			}

			// Default Status - New
			if( empty( $this->status ) || $this->status <= 0 ) {

				$this->status = self::STATUS_NEW;
			}

			// Default Order - zero
			if( empty( $this->order ) || $this->order <= 0 ) {

				$this->order = 0;
			}

			// Default Type - Default
			$this->type = $this->type ?? CoreGlobal::TYPE_DEFAULT;

			// Default Visibility - Private
			$this->visibility = $this->visibility ?? self::VISIBILITY_PRIVATE;

			return true;
		}

		return false;
	}

	// CMG interfaces ------------------------

	// IOwner -----------------

	/**
	 * Check whether given user is owner of this object.
	 *
	 * @param User $user
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isOwner( $user = null, $strict = false ) {

		if( $this->testOwner ) {

			if( !isset( $user ) && !$strict ) {

				$user = Yii::$app->core->getUser();
			}

			if( isset( $user ) && isset( $this->userId ) ) {

				return $user->id == $this->userId;
			}

			if( isset( $user ) && isset( $this->createdBy ) ) {

				return $user->id == $this->createdBy;
			}
		}

		return false;
	}

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ObjectData ----------------------------

	/**
	 * Returns theme to which this object belongs. A object can also exist without assigning theme.
	 *
	 * @return Theme|null
	 */
	public function getTheme() {

		return $this->hasOne( Theme::class, [ 'id' => 'themeId' ] );
	}

	/**
	 * Returns the corresponding user.
	 *
	 * @return User
	 */
	public function getUser() {

		return $this->hasOne( User::class, [ 'id' => 'userId' ] );
	}

	/**
	 * Returns the immediate parent.
	 *
	 * Notes: Override in child classes to get the exact class object if required.
	 *
	 * @return ObjectData
	 */
	public function getParent() {

		return $this->hasOne( ObjectData::class, [ 'id' => 'parentId' ] );
	}

	/**
	 * Returns string representation of [[$shared]].
	 *
	 * @return boolean
	 */
	public function getSharedStr() {

		return Yii::$app->formatter->asBoolean( $this->shared );
	}

	/**
	 * Returns the child objects mapped to it.
	 *
	 * @return ObjectData[]
	 */
    public function getObjects() {

        return $this->hasMany( ModelObject::class, [ 'parentId' => 'id' ] );
    }

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_OBJECT_DATA );
	}

	// CMG parent classes --------------------

	// ObjectData ----------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [
			'site', 'theme', 'template', 'user',
			'avatar', 'banner', 'video'
		];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the object with objects assigned to it.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with assigned objects.
	 */
    public static function queryWithModelObjects( $config = [] ) {

        $config[ 'relations' ] = [ 'objects' ];

        return parent::queryWithAll( $config );
    }

	/**
	 * Return query to find objects by type.
	 *
	 * @param string $type
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by type.
	 */
	public static function queryByType( $type, $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			return static::find()->where( 'type=:type AND siteId=:siteId', [ ':type' => $type, ':siteId' => $siteId ] )->orderBy( [ 'order' => SORT_DESC ] );
		}
		else {

			return static::find()->where( 'type=:type', [ ':type' => $type ] )->orderBy( [ 'order' => SORT_DESC ] );
		}
	}

	/**
	 * Return query to find top level objects by type.
	 *
	 * @param string $type
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by type.
	 */
	public static function queryL0ByType( $type, $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			return static::find()->where( 'parentId IS NULL AND type=:type AND siteId=:siteId', [ ':type' => $type, ':siteId' => $siteId ] )->orderBy( [ 'order' => SORT_DESC ] );
		}
		else {

			return static::find()->where( 'parentId IS NULL AND type=:type', [ ':type' => $type ] )->orderBy( [ 'order' => SORT_DESC ] );
		}
	}

	/**
	 * Return query to find top level objects by parent id and type.
	 *
	 * @param integer $parentId
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by parent id and type.
	 */
	public static function queryByParentId( $parentId, $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			return static::find()->where( 'parentId=:pid AND siteId=:siteId', [ ':pid' => $parentId, ':siteId' => $siteId ] )->orderBy( [ 'order' => SORT_DESC ] );
		}
		else {

			return static::find()->where( 'parentId=:pid', [ ':pid' => $parentId ] )->orderBy( [ 'order' => SORT_DESC ] );
		}
	}

	// Read - Find ------------

	/**
	 * Find and returns the objects with given type.
	 *
	 * @param string $type
	 * @param array $config
	 * @return ObjectData[]
	 */
	public static function findByType( $type, $config = [] ) {

		return static::queryByType( $type, $config )->all();
	}

	/**
	 * Find and returns the top level objects with given type.
	 *
	 * @param string $type
	 * @param array $config
	 * @return ObjectData[]
	 */
	public static function findL0ByType( $type, $config = [] ) {

		return static::queryL0ByType( $type, $config )->all();
	}

	/**
	 * Find and returns the top level objects with given type.
	 *
	 * @param integer $parentId
	 * @param array $config
	 * @return ObjectData[]
	 */
	public static function findByParentId( $parentId, $config = [] ) {

		return static::queryByParentId( $parentId, $config )->all();
	}

	/**
	 * Find and return the model using given slug, type, and theme id. It assumes that site id is NULL.
	 *
	 * @param string $slug
	 * @param string $type
	 * @param integer $themeId
	 * @return \cmsgears\core\common\models\entities\ObjectData
	 */
	public static function findByThemeId( $slug, $type, $themeId, $config = [] ) {

		$config[ 'ignoreSite' ] = isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : true;

		return self::queryBySlugType( $slug, $type, $config )->andWhere( 'themeId=:themeId', [ ':themeId' => $themeId ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
