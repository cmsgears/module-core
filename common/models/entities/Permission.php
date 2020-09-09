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

use cmsgears\core\common\models\interfaces\base\IAuthor;
use cmsgears\core\common\models\interfaces\base\INameType;
use cmsgears\core\common\models\interfaces\base\ISlugType;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;
use cmsgears\core\common\models\interfaces\resources\IHierarchy;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\mappers\RolePermission;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\NameTypeTrait;
use cmsgears\core\common\models\traits\base\SlugTypeTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\resources\HierarchyTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * All the users will be assigned a role based on which they can perform actions.
 *
 * @property integer $id
 * @property integer $createdBy
 * @property long $modifiedBy
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $icon
 * @property string $description
 * @property boolean $group
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class Permission extends \cmsgears\core\common\models\base\Entity implements IAuthor, IData, IGridCache, IHierarchy, INameType, ISlugType {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CoreGlobal::TYPE_PERMISSION;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AuthorTrait;
	use DataTrait;
	use GridCacheTrait;
	use HierarchyTrait;
	use NameTypeTrait;
	use SlugTypeTrait;

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
				'slugAttribute' => 'slug', // Unique irrespective of type
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
			[ [ 'name' ], 'required' ],
			[ [ 'id' ], 'safe' ],
			// Unique
			[ 'name', 'unique', 'targetAttribute' => [ 'type', 'name' ], 'message' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NAME ) ],
			[ 'slug', 'unique' ],
			// Text Limit
			[ 'type', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'icon', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'slug', 'string', 'min' => 0, 'max' => Yii::$app->core->xxLargeText ],
			[ 'description', 'string', 'min' => 0, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'group', 'gridCacheValid' ], 'boolean' ],
			[ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'description' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'slug' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
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

			// Default Type - Default
			$this->type = $this->type ?? CoreGlobal::TYPE_SYSTEM;

	        return true;
	    }

		return false;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Permission ----------------------------

	/**
	 * Return list of roles to which this permission is mapped.
	 *
	 * @return Role[]
	 */
	public function getRoles() {

		return $this->hasMany( Role::class, [ 'id' => 'roleId' ] )
			->viaTable( CoreTables::getTableName( CoreTables::TABLE_ROLE_PERMISSION ), [ 'permissionId' => 'id' ] );
	}

	/**
	 * Return mapping list of roles mapped to this permission.
	 *
	 * @return RolePermission[]
	 */
	public function getRoleMappingList() {

		return $this->hasMany( RolePermission::class, [ 'permissionId' => 'id' ] );
	}

	/**
	 * Generate and return the array having id of mapped roles.
	 *
	 * @return array
	 */
	public function getRolesIdList() {

		$roleMappings	= $this->roleMappingList;
		$rolesIdList	= [];

		foreach( $roleMappings as $roleMapping ) {

			array_push( $rolesIdList, $roleMapping->roleId );
		}

		return $rolesIdList;
	}

	/**
	 * Returns string representation of group flag.
	 *
	 * @return string
	 */
    public function getGroupStr() {

        return Yii::$app->formatter->asBoolean( $this->group );
    }

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_PERMISSION );
	}

	// CMG parent classes --------------------

	// Permission ----------------------------

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
	 * Return query to find the permission with roles.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with roles.
	 */
	public static function queryWithRoles( $config = [] ) {

		$config[ 'relations' ] = [ 'roles' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * Find and return the children of given permissions id.
	 *
	 * @param array $ids L0 id list
	 * @param array $config
	 * @return Permission[]
	 */
	public static function findGroupPermissions( $ids = [], $config = [] ) {

		$permissionTable	= CoreTables::getTableName( CoreTables::TABLE_PERMISSION );
		$hierarchyTable		= CoreTables::getTableName( CoreTables::TABLE_MODEL_HIERARCHY );

		$idStr = join( ",", $ids );

		return Permission::find()->leftJoin( $hierarchyTable, "`$permissionTable`.`id` = `$hierarchyTable`.`childId`" )
			->where( "`$hierarchyTable`.`parentType` = 'permission' AND `$hierarchyTable`.`parentId` IN ($idStr)" )->all();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
