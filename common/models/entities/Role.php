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
use cmsgears\core\common\models\base\Entity;
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
 * @property integer $modifiedBy
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $icon
 * @property string $description
 * @property boolean $group
 * @property string $adminUrl
 * @property string $homeUrl
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class Role extends Entity implements IAuthor, IData, IGridCache, IHierarchy, INameType, ISlugType {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CoreGlobal::TYPE_ROLE;

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
			[ [ 'id', 'data', 'gridCache' ], 'safe' ],
			// Unique
			[ 'name', 'unique', 'targetAttribute' => [ 'type', 'name' ] ],
			// Text Limit
			[ 'type', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'icon', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ [ 'slug', 'adminUrl', 'homeUrl' ], 'string', 'min' => 0, 'max' => Yii::$app->core->xxLargeText ],
			[ 'description', 'string', 'min' => 0, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'group', 'gridCacheValid' ], 'boolean' ],
			[ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'description', 'adminUrl', 'homeUrl' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
			'adminUrl' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ADMIN_URL ),
			'homeUrl' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_HOME_URL ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Role ----------------------------------

	/**
	 * Return list of permissions mapped to this role.
	 *
	 * @return Permission[]
	 */
	public function getPermissions() {

		return $this->hasMany( Permission::class, [ 'id' => 'permissionId' ] )
			->viaTable( CoreTables::getTableName( CoreTables::TABLE_ROLE_PERMISSION ), [ 'roleId' => 'id' ] );
	}

	/**
	 * Return mapping list of permissions mapped to this role.
	 *
	 * @return RolePermission[]
	 */
	public function getPermissionMappingList() {

		return $this->hasMany( RolePermission::class, [ 'roleId' => 'id' ] );
	}

	/**
	 * Generate and return the array having id of mapped permissions.
	 *
	 * @return array
	 */
	public function getPermissionsIdList() {

		$permissions		= $this->permissionMappingList;
		$permissionsList	= [];

		foreach ( $permissions as $permission ) {

			array_push( $permissionsList, $permission->permissionId );
		}

		return $permissionsList;
	}

	/**
	 * Generate and return the array having slug of mapped permissions.
	 *
	 * @return array
	 */
	public function getPermissionsSlugList() {

		$slugList	= [];
		$idList		= [];

		// TODO: Use roles hierarchy recursively to get child roles

		if( !empty( $this->gridCache ) ) {

			return $this->getGridCacheAttribute( 'permissionsSlugList' );
		}
		else {

			// Generate L0 Slugs and Ids List
			$permissions = $this->permissions;

			foreach( $permissions as $permission ) {

				if( !in_array( $permission->slug, $slugList ) ) {

					array_push( $slugList, $permission->slug );

					if( $permission->group ) {

						array_push( $idList, $permission->id );
					}
				}
			}

			// Add child permission slugs recursively till all leaf nodes get exhausted.
			while( count( $idList ) > 0 ) {

				$permissions = Permission::findGroupPermissions( $idList );

				$idList = [];

				foreach( $permissions as $permission ) {

					if( !in_array( $permission->slug, $slugList ) ) {

						array_push( $slugList, $permission->slug );

						if( $permission->group ) {

							array_push( $idList, $permission->id );
						}
					}
				}

				if( count( $idList ) == 0 ) {

					break;
				}
			};
		}

		return $slugList;
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

		return CoreTables::getTableName( CoreTables::TABLE_ROLE );
	}

	// CMG parent classes --------------------

	// Role ----------------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'creator', 'modifier' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the role with permissions.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with permissions.
	 */
	public static function queryWithPermissions( $config = [] ) {

		$config[ 'relations' ]	= [ 'permissions' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
