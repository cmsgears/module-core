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
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\base\IAuthor;
use cmsgears\core\common\models\interfaces\base\IName;
use cmsgears\core\common\models\interfaces\base\ISlug;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\base\Entity;
use cmsgears\core\common\models\resources\SiteMeta;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\NameTrait;
use cmsgears\core\common\models\traits\base\SlugTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\resources\VisualTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Site Entity
 *
 * @property integer $id
 * @property integer $avatarId
 * @property integer $bannerId
 * @property integer $themeId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $slug
 * @property string $icon
 * @property string $title
 * @property string $description
 * @property integer $order
 * @property boolean $active
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class Site extends Entity implements IAuthor, IName, ISlug {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $modelType	= CoreGlobal::TYPE_SITE;

	// Traits ------------------------------------------------------

	use AuthorTrait;
	use DataTrait;
	use GridCacheTrait;
	use NameTrait;
	use SlugTrait;
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
			AuthorBehavior::class,
			'sluggableBehavior' => [
				'class' => SluggableBehavior::class,
				'attribute' => 'name',
				'slugAttribute' => 'slug',
				'immutable' => true,
				'ensureUnique' => true
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
			[ 'name', 'required' ],
			[ [ 'id', 'data', 'gridCache' ], 'safe' ],
			// Unique
			[ 'name', 'unique' ],
			// Text Limit
			[ 'icon', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'slug', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 0, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ 'order', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'active', 'gridCacheValid' ], 'boolean' ],
			[ [ 'avatarId', 'bannerId', 'themeId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ 'gridCachedAt', 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
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
			'avatarId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AVATAR ),
			'bannerId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_BANNER ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'slug' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
			'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'order' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Site ----------------------------------

	/**
	 * Return active theme configured for the site.
	 *
	 * @return Theme
	 */
	public function getTheme() {

		return $this->hasOne( Theme::class, [ 'id' => 'themeId' ] );
	}

	/**
	 * Return meta data of the site.
	 *
	 * @return \cmsgears\core\common\models\resources\SiteMeta[]
	 */
	public function getMetas() {

		return $this->hasMany( SiteMeta::class, [ 'modelId' => 'id' ] );
	}

	/**
	 * Return members of the site.
	 *
	 * @return User[]
	 */
	public function getMembers() {

		$siteMemberTable = CoreTables::getTableName( CoreTables::TABLE_SITE_MEMBER );

		return $this->hasMany( User::class, [ 'id' => 'userId' ] )
			->viaTable( $siteMemberTable, [ 'siteId' => 'id' ] );
	}

	/**
	 * Returns string representation of active flag.
	 *
	 * @return string
	 */
	public function getActiveStr() {

		return Yii::$app->formatter->asBoolean( $this->active );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_SITE );
	}

	// CMG parent classes --------------------

	// Site ----------------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		//$modelTable				= CoreTables::getTableName( CoreTables::TABLE_SITE );
		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'avatar', 'banner', 'theme' ];
		$config[ 'relations' ]	= $relations;
		//$config[ 'groups' ]		= isset( $config[ 'groups' ] ) ? $config[ 'groups' ] : [ "$modelTable.id" ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the site with theme.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with theme.
	 */
	public static function queryWithTheme( $config = [] ) {

		$config[ 'relations' ]	= [ 'avatar', 'banner', 'theme' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the site with meta.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with meta.
	 */
	public static function queryWithMetas( $config = [] ) {

		$config[ 'relations' ]	= [ 'avatar', 'banner', 'metas' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the site with members.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with members.
	 */
	public static function queryWithMembers( $config = [] ) {

		$config[ 'relations' ]	= [ 'avatar', 'banner', 'members' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
