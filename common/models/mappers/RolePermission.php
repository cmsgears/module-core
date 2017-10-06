<?php
namespace cmsgears\core\common\models\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

/**
 * RolePermission Entity
 *
 * @property long $roleId
 * @property long $permissionId
 */
class RolePermission extends \cmsgears\core\common\models\base\Entity {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	/**
	 * @inheritdoc
	 */
	public function rules() {

		return [
			// Required, Safe
			[ [ 'roleId', 'permissionId' ], 'required' ],
			// Unique
			[ [ 'roleId', 'permissionId' ], 'unique', 'targetAttribute' => [ 'roleId', 'permissionId' ] ],
			// Other
			[ [ 'roleId', 'permissionId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'roleId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ROLE ),
			'permissionId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PERMISSION )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// RolePermission ------------------------

	/**
	 * @return Role - from the mapping.
	 */
	public function getRole() {

		return $this->hasOne( Role::className(), [ 'id' => 'roleId' ] );
	}

	/**
	 * @return Permission - from the mapping.
	 */
	public function getPermission() {

		return $this->hasOne( Permission::className(), [ 'id' => 'permissionId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::TABLE_ROLE_PERMISSION;
	}

	// CMG parent classes --------------------

	// RolePermission ------------------------

	// Read - Query -----------

	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'role', 'permission' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryWithRole( $config = [] ) {

		$config[ 'relations' ]	= [ 'role' ];

		return parent::queryWithAll( $config );
	}

	public static function queryWithPermission( $config = [] ) {

		$config[ 'relations' ]	= [ 'permission' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	/**
	 * Delete the mappings by given role id.
	 */
	public static function deleteByRoleId( $roleId ) {

		self::deleteAll( 'roleId=:id', [ ':id' => $roleId ] );
	}

	/**
	 * Delete the mappings by given permission id.
	 */
	public static function deleteByPermissionId( $permissionId ) {

		self::deleteAll( 'permissionId=:id', [ ':id' => $permissionId ] );
	}
}
