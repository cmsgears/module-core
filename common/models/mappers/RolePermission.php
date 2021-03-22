<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\mappers;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

/**
 * Mapper to map roles and permissions.
 *
 * @property integer $roleId
 * @property integer $permissionId
 *
 * @since 1.0.0
 */
class RolePermission extends \cmsgears\core\common\models\base\Mapper {

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

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'roleId', 'permissionId' ], 'required' ],
			// Unique
			[ [ 'roleId', 'permissionId' ], 'unique', 'targetAttribute' => [ 'roleId', 'permissionId' ] ],
			// Other
			[ [ 'roleId', 'permissionId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
		];

		return $rules;
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
	 * Return the role associated with the mapping.
	 *
	 * @return Role
	 */
	public function getRole() {

		return $this->hasOne( Role::class, [ 'id' => 'roleId' ] );
	}

	/**
	 * Return the permission associated with the mapping.
	 *
	 * @return Permission
	 */
	public function getPermission() {

		return $this->hasOne( Permission::class, [ 'id' => 'permissionId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_ROLE_PERMISSION );
	}

	// CMG parent classes --------------------

	// RolePermission ------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'role', 'permission' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the mapping with role.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with role.
	 */
	public static function queryWithRole( $config = [] ) {

		$config[ 'relations' ] = [ 'role' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the mapping with permission.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with permission.
	 */
	public static function queryWithPermission( $config = [] ) {

		$config[ 'relations' ] = [ 'permission' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * Find and return the mapping associated with given role id and permission id.
	 *
	 * @param integer $roleId
	 * @param integer $permissionId
	 * @return RolePermission
	 */
	public static function findByRoleIdPermissionId( $roleId, $permissionId ) {

		return self::find()->where( 'roleId=:rid AND $permissionId=:pid', [ ':rid' => $roleId, ':pid' => $permissionId ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	/**
	 * Delete all the mappings associated with given role id.
	 *
	 * @param type $roleId
	 * @return int the number of rows deleted.
	 */
	public static function deleteByRoleId( $roleId ) {

		return self::deleteAll( 'roleId=:id', [ ':id' => $roleId ] );
	}

	/**
	 * Delete all the mappings associated with given permission id.
	 *
	 * @param type $permissionId
	 * @return int the number of rows deleted.
	 */
	public static function deleteByPermissionId( $permissionId ) {

		return self::deleteAll( 'permissionId=:id', [ ':id' => $permissionId ] );
	}

}
