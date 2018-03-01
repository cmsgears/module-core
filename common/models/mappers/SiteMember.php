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
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\Role;

/**
 * A user can have only one role specific to a site, though a role can have multiple permissions.
 *
 * @property long $id
 * @property long $siteId
 * @property long $userId
 * @property long $roleId
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 *
 * @since 1.0.0
 */
class SiteMember extends \cmsgears\core\common\models\base\Entity {

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

	/**
	 * @inheritdoc
	 */
	public function behaviors() {

		return [
			'timestampBehavior' => [
				'class' => TimestampBehavior::className(),
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
			[ [ 'siteId', 'userId', 'roleId' ], 'required' ],
			// Unique
			[ [ 'siteId', 'userId', 'roleId' ], 'unique', 'targetAttribute' => [ 'siteId', 'userId', 'roleId' ] ],
			// Other
			[ [ 'siteId', 'userId', 'roleId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'siteId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SITE ),
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'roleId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ROLE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// SiteMember ----------------------------

	/**
	 * Return corresponding site to which site member belongs.
	 *
	 * @return Site
	 */
	public function getSite() {

		return $this->hasOne( Site::className(), [ 'id' => 'siteId' ] );
	}

	/**
	 * Return corresponding user to which site member belongs.
	 *
	 * @return User
	 */
	public function getUser() {

		return $this->hasOne( User::className(), [ 'id' => 'userId' ] );
	}

	/**
	 * Return role assigned at site level to the site member.
	 *
	 * @return Role
	 */
	public function getRole() {

		return $this->hasOne( Role::className(), [ 'id' => 'roleId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::TABLE_SITE_MEMBER;
	}

	// CMG parent classes --------------------

	// SiteMember ----------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'site', 'user', 'role' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the site member with site.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with site.
	 */
	public static function queryWithSite( $config = [] ) {

		$config[ 'relations' ]	= [ 'site' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the site member with user.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with user.
	 */
	public static function queryWithUser( $config = [] ) {

		$config[ 'relations' ]	= [ 'user', 'role' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * Find and return the site member using given site id and user id.
	 *
	 * @param \cmsgears\core\common\models\entities\Site $siteId
	 * @param \cmsgears\core\common\models\entities\User $userId
	 * @return SiteMember
	 */
	public static function findBySiteIdUserId( $siteId, $userId ) {

		return self::find()->where( 'siteId=:sid AND userId=:uid', [ ':sid' => $siteId, ':uid' => $userId ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	/**
	 * Delete all the site members associated with given site id.
	 *
	 * @param integer $siteId
	 * @return int the number of rows deleted.
	 */
	public static function deleteBySiteId( $siteId ) {

		return self::deleteAll( 'siteId=:id', [ ':id' => $siteId ] );
	}

	/**
	 * Delete all the site members associated with given user id.
	 *
	 * @param integer $userId
	 * @return int the number of rows deleted.
	 */
	public static function deleteByUserId( $userId ) {

		return self::deleteAll( 'userId=:id', [ ':id' => $userId ] );
	}

	/**
	 * Delete all the site members associated with given role id.
	 *
	 * @param integer $roleId
	 * @return int the number of rows deleted.
	 */
	public static function deleteByRoleId( $roleId ) {

		return self::deleteAll( 'roleId=:id', [ ':id' => $roleId ] );
	}
}
