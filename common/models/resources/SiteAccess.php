<?php
namespace cmsgears\core\common\models\resources;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\Role;

/**
 * SiteAccess Entity - Logs user stats specific to controller and action execution.
 *
 * @property long $id
 * @property long $siteId
 * @property long $userId
 * @property long $roleId
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 */
class SiteAccess extends \cmsgears\core\common\models\base\Mapper {

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

		return [
			// Required, Safe
			[ [ 'siteId', 'userId', 'roleId' ], 'required' ],
			// Text Limit
			[ 'ip', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'action', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ [ 'url', 'controller' ], 'string', 'min' => 0, 'max' => Yii::$app->core->xxLargeText ],
			// Other
			[ [ 'siteId', 'userId', 'roleId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ 'ipNum', 'number', 'integerOnly' => true ],
			[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
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
			'ipNum' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_IP ),
			'url' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_URL )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// SiteAccess ----------------------------

	/**
	 * @return Site
	 */
	public function getSite() {

		return $this->hasOne( Site::className(), [ 'id' => 'siteId' ] );
	}

	/**
	 * @return User
	 */
	public function getUser() {

		return $this->hasOne( User::className(), [ 'id' => 'userId' ] );
	}

	/**
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

		return CoreTables::TABLE_SITE_ACCESS;
	}

	// CMG parent classes --------------------

	// SiteAccess ----------------------------

	// Read - Query -----------

	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'site', 'user', 'role' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryWithSite( $config = [] ) {

		$config[ 'relations' ]	= [ 'site' ];

		return parent::queryWithAll( $config );
	}

	public static function queryWithUser( $config = [] ) {

		$config[ 'relations' ]	= [ 'user', 'role' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * @return Site - by id
	 */
	public static function findBySiteIdUserId( $siteId, $userId ) {

		return self::find()->where( 'siteId=:sid AND userId=:uid', [ ':sid' => $siteId, ':uid' => $userId ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	/**
	 * Delete the mappings by given site id.
	 */
	public static function deleteBySiteId( $siteId ) {

		self::deleteAll( 'siteId=:id', [ ':id' => $siteId ] );
	}

	/**
	 * Delete the mappings by given user id.
	 */
	public static function deleteByUserId( $userId ) {

		self::deleteAll( 'userId=:id', [ ':id' => $userId ] );
	}

	/**
	 * Delete the mappings by given role id.
	 */
	public static function deleteByRoleId( $roleId ) {

		self::deleteAll( 'roleId=:id', [ ':id' => $roleId ] );
	}
}
