<?php
namespace cmsgears\core\common\models\mappers;

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
 * SiteMember Entity - A user can have only one role specific to a site, though a role can have multiple permissions.
 *
 * @property long $id
 * @property long $siteId
 * @property long $userId
 * @property long $roleId
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 */
class SiteMember extends \cmsgears\core\common\models\base\Mapper {

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
            [ [ 'siteId', 'userId', 'roleId' ], 'required' ],
            [ [ 'siteId', 'userId', 'roleId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'siteId', 'userId', 'roleId' ], 'unique', 'targetAttribute' => [ 'siteId', 'userId', 'roleId' ] ],
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
            'roleId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ROLE )
        ];
    }

    // CMG interfaces ------------------------

    // CMG parent classes --------------------

    // Validators ----------------------------

    // SiteMember ----------------------------

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

        return CoreTables::TABLE_SITE_MEMBER;
    }

    // CMG parent classes --------------------

    // SiteMember ----------------------------

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
