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
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\base\IMultiSite;
use cmsgears\core\common\models\interfaces\resources\IGridCache;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\base\Resource;
use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\Role;

use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
/**
 * Logs user stats specific to login sessions and actions executed by user.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $userId
 * @property integer $roleId
 * @property string $ip
 * @property integer $ipNum
 * @property string $controller
 * @property string $action
 * @property string $url
 * @property boolean $failed
 * @property integer $failCount
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class SiteAccess extends Resource implements IGridCache, IMultiSite {

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

	use GridCacheTrait;
	use MultiSiteTrait;

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

		return [
			// Required, Safe
			[ [ 'siteId', 'userId', 'roleId' ], 'required' ],
			[ 'gridCache', 'safe' ],
			// Text Limit
			[ 'ip', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'controller', 'action' ], 'string', 'min' => 0, 'max' => Yii::$app->core->xxLargeText ],
			[ 'url', 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			// Other
			[ [ 'siteId', 'userId', 'roleId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'ipNum', 'failCount' ], 'number', 'integerOnly' => true ],
			[ [ 'failed', 'gridCacheValid' ] => 'boolean' ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'siteId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SITE ),
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'roleId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ROLE ),
			'ip' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_IP ),
			'ipNum' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_IP_NUM ),
			'url' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_URL ),
			'failed' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FAILED ),
			'failCount' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FAIL_COUNT ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// SiteAccess ----------------------------

	/**
	 * Return the site associated with the access log.
	 *
	 * @return Site
	 */
	public function getSite() {

		return $this->hasOne( Site::class, [ 'id' => 'siteId' ] );
	}

	/**
	 * Return the user associated with the access log.
	 *
	 * @return User
	 */
	public function getUser() {

		return $this->hasOne( User::class, [ 'id' => 'userId' ] );
	}

	/**
	 * Return the role associated with the access log.
	 *
	 * @return Role
	 */
	public function getRole() {

		return $this->hasOne( Role::class, [ 'id' => 'roleId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_SITE_ACCESS );
	}

	// CMG parent classes --------------------

	// SiteAccess ----------------------------

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
	 * Return query to find the access log with site assigned to it.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with site.
	 */
	public static function queryWithSite( $config = [] ) {

		$config[ 'relations' ]	= [ 'site' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the access log with user assigned to it.
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
	 * Return all the access logs associated with given user id and site id.
	 *
	 * @param integer $siteId
	 * @param integer $userId
	 * @return SiteAccess
	 */
	public static function findBySiteIdUserId( $siteId, $userId ) {

		return self::find()->where( 'siteId=:sid AND userId=:uid', [ ':sid' => $siteId, ':uid' => $userId ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	/**
	 * Delete the access log for given site id.
	 *
	 * @param integer $siteId
	 * @return int the number of rows deleted.
	 */
	public static function deleteBySiteId( $siteId ) {

		return self::deleteAll( 'siteId=:id', [ ':id' => $siteId ] );
	}

	/**
	 * Delete the access log for given user id.
	 *
	 * @param integer $userId
	 * @return int the number of rows deleted.
	 */
	public static function deleteByUserId( $userId ) {

		return self::deleteAll( 'userId=:id', [ ':id' => $userId ] );
	}

	/**
	 * Delete the access log for given role id.
	 *
	 * @param integer $roleId
	 * @return int the number of rows deleted.
	 */
	public static function deleteByRoleId( $roleId ) {

		return self::deleteAll( 'roleId=:id', [ ':id' => $roleId ] );
	}
}
