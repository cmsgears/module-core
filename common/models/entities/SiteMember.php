<?php
namespace cmsgears\core\common\models\entities;

/**
 * SiteMember Entity
 *
 * @property int $siteId
 * @property int $memberId
 * @property int $roleId
 * @property datetime $createdAt
 */
class SiteMember extends CmgEntity {

	// Instance Methods --------------------------------------------

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

	// yii\base\Model --------------------

	/**
	 * Validation rules
	 */
	public function rules() {

        return [
            [ [ 'siteId', 'userId', 'roleId' ], 'required' ],
            [ [ 'siteId', 'userId', 'roleId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
        ];
    }

	/**
	 * Model attributes
	 */
	public function attributeLabels() {

		return [
			'siteId' => 'Site',
			'userId' => 'Member',
			'roleId' => 'Role',
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	/**
	 * @return string - db table name
	 */
	public static function tableName() {

		return CoreTables::TABLE_SITE_MEMBER;
	}

	// SiteMember ------------------------

	// Read ----

	/**
	 * @return Site - by id
	 */
	public static function findBySiteIdUserId( $siteId, $userId ) {

		return self::find()->where( 'siteId=:sid AND userId=:uid', [ ':sid' => $siteId, ':uid' => $userId ] )->one();
	}

	// Delete ----

	/**
	 * Delete the mappings by given site id.
	 */
	public static function deleteBySiteId( $siteId ) {

		self::deleteAll( 'siteId=:id', [ ':id' => $siteId ] );
	}

	/**
	 * Delete the mappings by given user id.
	 */
	public static function deleteByUserId( $memberId ) {

		self::deleteAll( 'userId=:id', [ ':id' => $memberId ] );
	}

	/**
	 * Delete the mappings by given role id.
	 */
	public static function deleteByRoleId( $roleId ) {

		self::deleteAll( 'roleId=:id', [ ':id' => $roleId ] );
	}
}

?>