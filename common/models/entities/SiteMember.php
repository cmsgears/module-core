<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * SiteMember Entity
 *
 * @property int $siteId
 * @property int $userId
 * @property int $roleId
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 */
class SiteMember extends CmgEntity {

	// Instance Methods --------------------------------------------

	/**
	 * @return Site
	 */
	public function getSite() {

    	return $this->hasOne( Site::className(), [ 'id' => 'siteId' ] )->from( CoreTables::TABLE_SITE . ' site' );
	}

	/**
	 * @return User
	 */
	public function getUser() {

    	return $this->hasOne( User::className(), [ 'id' => 'userId' ] )->from( CoreTables::TABLE_USER . ' user' );
	}

	/**
	 * @return Role
	 */
	public function getRole() {

    	return $this->hasOne( Role::className(), [ 'id' => 'roleId' ] )->from( CoreTables::TABLE_ROLE . ' role' );
	}

	// yii\base\Component ----------------

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [

            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'createdAt',
 				'updatedAtAttribute' => 'modifiedAt'
            ]
        ];
    }

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'siteId', 'userId', 'roleId' ], 'required' ],
            [ [ 'siteId', 'userId', 'roleId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'siteId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_SITE ),
			'userId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'roleId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ROLE )
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
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