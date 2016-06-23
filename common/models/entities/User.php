<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii\base\NotSupportedException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\config\CoreProperties;

use cmsgears\core\common\models\interfaces\IApproval;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Option;
use cmsgears\core\common\models\mappers\SiteMember;

use cmsgears\core\common\models\traits\interfaces\ApprovalTrait;
use cmsgears\core\common\models\traits\resources\AttributeTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\VisualTrait;
use cmsgears\core\common\models\traits\mappers\AddressTrait;
use cmsgears\core\common\models\traits\mappers\FileTrait;

/**
 * User Entity - The primary class.
 *
 * @property long $id
 * @property long localeId
 * @property long genderId
 * @property long avatarId
 * @property short $status
 * @property string $email
 * @property string $username
 * @property string $passwordHash
 * @property string $firstName
 * @property string $lastName
 * @property string $dob
 * @property string $phone
 * @property string $avatarUrl
 * @property string $websiteUrl
 * @property string verifyToken
 * @property string resetToken
 * @property string registeredAt
 * @property datetime lastLoginAt
 * @property datetime lastActivityAt
 * @property string authKey
 * @property string accessToken
 * @property datetime accessTokenCreatedAt
 * @property datetime accessTokenAccessedAt
 * @property string data
 */
class User extends \cmsgears\core\common\models\base\Entity implements IdentityInterface, IApproval {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    /**
     * The status map having string form of status available for admin to make status change.
     */
    public static $statusMapUpdate = [
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_FROJEN => 'Frozen',
        self::STATUS_BLOCKED => 'Blocked',
        self::STATUS_TERMINATED => 'Terminated'
    ];

    // Public -------------

    public $parentType  = CoreGlobal::TYPE_USER;
    public $permissions = [];

    // Private/Protected --

    // Traits ------------------------------------------------------

	use AddressTrait;
	use ApprovalTrait;
	use AttributeTrait;
	use DataTrait;
	use FileTrait;
	use VisualTrait;

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    // yii\web\IdentityInterface ----------

    /**
     * @inheritdoc
     */
    public function getId() {

        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {

        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey( $authKey ) {

        return $this->authKey === $authKey;
    }

    // yii\base\Component ----------------

    // yii\base\Model --------------------

    /**
     * @inheritdoc
     */
    public function rules() {

        // model rules
        $rules = [
            [ [ 'email' ], 'required' ],
            [ [ 'id', 'phone', 'data' ], 'safe' ],
            [ 'email', 'email' ],
            [ 'email', 'validateEmailCreate', 'on' => [ 'create' ] ],
            [ 'email', 'validateEmailUpdate', 'on' => [ 'update', 'profile' ] ],
            [ 'email', 'validateEmailChange', 'on' => [ 'profile' ] ],
            [ 'username', 'alphanumdotu' ],
            [ 'username', 'validateUsernameCreate', 'on' => [ 'create' ] ],
            [ 'username', 'validateUsernameUpdate', 'on' => [ 'update', 'profile' ] ],
            [ 'username', 'validateUsernameChange', 'on' => [ 'profile' ] ],
            [ [ 'firstName', 'lastName' ], 'alphanumspace' ],
            [ [ 'id', 'localeId', 'genderId', 'avatarId', 'status' ], 'number', 'integerOnly' => true ],
            [ 'dob', 'date', 'format' => Yii::$app->formatter->dateFormat ],
            [ [ 'avatarUrl', 'websiteUrl' ], 'url' ],
            [ [ 'registeredAt', 'lastLoginAt', 'lastActivityAt', 'accessTokenCreatedAt', 'accessTokenAccessedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ],
            [ [ 'email', 'username', 'passwordHash', 'firstName', 'lastName', 'phone', 'avatarUrl', 'websiteUrl', 'verifyToken', 'resetToken', 'authKey', 'accessToken' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->extraLargeText ]
        ];

        // trim if required
        if( Yii::$app->cmgCore->trimFieldValue ) {

            $trim[] = [ [ 'email', 'username', 'phone', 'firstName', 'lastName', 'avatarUrl', 'websiteUrl' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

            return ArrayHelper::merge( $trim, $rules );
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'localeId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LOCALE ),
            'genderId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_GENDER ),
            'avatarId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_AVATAR ),
            'status' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
            'email' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
            'username' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_USERNAME ),
            'firstName' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_FIRSTNAME ),
            'lastName' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LASTNAME ),
            'dob' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DOB ),
            'phone' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PHONE ),
            'avatarUrl' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_AVATAR_URL ),
            'websiteUrl' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_WEBSITE )
        ];
    }

    // User ------------------------------

    /**
     * Validates user email to ensure that only one user exist with the given email.
     */
    public function validateEmailCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByEmail( $this->email ) ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EMAIL_EXIST ) );
            }
        }
    }

    /**
     * Validates user email to ensure that only one user exist with the given email.
     */
    public function validateEmailUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            $existingUser = self::findByEmail( $this->email );

            if( isset( $existingUser ) && $this->id != $existingUser->id ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EMAIL_EXIST ) );
            }
        }
    }

    /**
     * Validates user email to ensure that it does not allow to change while changing user profile.
     */
    public function validateEmailChange( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            $properties     = CoreProperties::getInstance();
            $existingUser   = self::findById( $this->id );

            if( isset( $existingUser ) && strcmp( $existingUser->email, $this->email ) != 0 && !$properties->isChangeEmail() ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_CHANGE_EMAIL ) );
            }
        }
    }

    /**
     * Validates user email to ensure that only one user exist with the given username.
     */
    public function validateUsernameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByUsername( $this->username ) ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_USERNAME_EXIST ) );
            }
        }
    }

    /**
     * Validates user email to ensure that only one user exist with the given username.
     */
    public function validateUsernameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            $existingUser = self::findByUsername( $this->username );

            if( isset( $existingUser ) && $this->id != $existingUser->id ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_USERNAME_EXIST ) );
            }
        }
    }

    /**
     * Validates user email to ensure that it does not allow to change while changing user profile.
     */
    public function validateUsernameChange( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            $properties     = CoreProperties::getInstance();
            $existingUser   = self::findById( $this->id );

            if( isset( $existingUser ) && strcmp( $existingUser->username, $this->username ) != 0  && !$properties->isChangeUsername() ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_CHANGE_USERNAME ) );
            }
        }
    }

    /**
     * Validates user password while login
     */
    public function validatePassword( $password ) {

        if( isset( $password ) && isset( $this->passwordHash ) ) {

            return Yii::$app->getSecurity()->validatePassword( $password, $this->passwordHash );
        }

        return false;
    }

    /**
     * @return user name from email in case it's not set by user
     */
    public function getName() {

        $name   = $this->firstName . ' ' . $this->lastName;

        if( !isset( $name ) || strlen( $name ) <= 1 ) {

            $name   = $this->username;

            if( !isset( $name ) || strlen( $name ) <= 1 ) {

                $name   = preg_split( '/@/', $this->email );
                $name   = $name[ 0 ];
            }
        }

        return $name;
    }

    /**
     * @return Site Member - assigned to User.
     */
    public function getSiteMember() {

        return $this->hasOne( SiteMember::className(), [ 'userId' => 'id' ] );
    }

    /**
     * @return Role - assigned to User.
     */
    public function getRole() {

        $roleTable          = CoreTables::TABLE_ROLE;
        $siteTable          = CoreTables::TABLE_SITE;
        $siteMemberTable    = CoreTables::TABLE_SITE_MEMBER;

        return Role::find()
                    ->leftJoin( $siteMemberTable, "$siteMemberTable.roleId = $roleTable.id" )
                    ->leftJoin( $siteTable, "$siteTable.id = $siteMemberTable.siteId" )
                    ->where( "$siteMemberTable.userId=:id AND $siteTable.slug=:slug", [ ':id' => $this->id, ':slug' => Yii::$app->cmgCore->getSiteSlug() ] );
    }

    /**
     * @return Locale - assigned to User.
     */
    public function getLocale() {

        return $this->hasOne( Locale::className(), [ 'id' => 'localeId' ] );
    }

    /**
     * @return Option - from Category 'gender' assigned to user.
     */
    public function getGender() {

        return $this->hasOne( Option::className(), [ 'id' => 'genderId' ] );
    }

    /**
     * @return string representation of gender.
     */
    public function getGenderStr() {

        $option = $this->gender;

        if( isset( $option ) ) {

            return $option->value;
        }

        return '';
    }

    /**
     * @return string representation of status.
     */
    public function getStatusStr() {

        return self::$statusMap[ $this->status ];
    }

    /**
     * Generate and set user password using the yii security mechanism.
     */
    public function generatePassword( $password ) {

        $this->passwordHash = Yii::$app->security->generatePasswordHash( $password );
    }

    /**
     * Generate and set user verification token using the yii security mechanism.
     */
    public function generateVerifyToken() {

        $this->verifyToken = Yii::$app->security->generateRandomString();
    }

    /**
     * unset user verification token on user verification.
     */
    public function unsetVerifyToken() {

        $this->verifyToken = null;
    }

    public function isVerifyTokenValid( $token ) {

        return strcmp( $this->verifyToken, $token ) == 0;
    }

    /**
     * Generate and set user password reset token using the yii security mechanism.
     */
    public function generateResetToken() {

        $this->resetToken = Yii::$app->security->generateRandomString();
    }

    /**
     * unset user reset token on user password change.
     */
    public function unsetResetToken() {

        $this->resetToken = null;
    }

    public function isResetTokenValid( $token ) {

        return strcmp( $this->resetToken, $token ) == 0;
    }

    /**
     * Generate and set user auth key using the yii security mechanism.
     */
    public function generateAuthKey() {

        $this->authKey = Yii::$app->security->generateRandomString();
    }

    /**
     * Load the permissions assigned to user.
     */
    public function loadPermissions() {

        $role = $this->role;

        if( isset( $role ) ) {

            $this->permissions  = $role->getPermissionsSlugList();
        }
        else {

            throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_ALLOWED ) );
        }
    }

    /**
     * @return boolean whether a permission is assigned to the user.
     */
    public function isPermitted( $permission ) {

        return in_array( $permission, $this->permissions );
    }

    // Static Methods ----------------------------------------------

    // yii\web\IdentityInterface ---------

    /**
     * The method finds the user identity using the given id and also loads the available permissions if rbac is enabled for the application.
     * @param integer $id
     * @return a valid user based on given id.
     */
    public static function findIdentity( $id ) {

        // Find valid User
        $user   = static::findById( $id );

        // Load User Permissions
        if( isset( $user ) ) {

            if( Yii::$app->cmgCore->isRbac() ) {

                $user->loadPermissions();
            }
        }
        else {

            throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
        }

        return $user;
    }

    /**
     * The method finds the user identity using the given access token and also loads the available permissions if rbac is enabled for the application.
     * @param string $token
     * @param string $type
     * @return a valid user based on given token and type
     */
    public static function findIdentityByAccessToken( $token, $type = null ) {

        if( Yii::$app->cmgCore->isApis() ) {

            // Find valid User
            $user   = static::findByAccessToken( $token );

            // Load User Permissions
            if( isset( $user ) ) {

                if( Yii::$app->cmgCore->isRbac() ) {

                    $user->loadPermissions();
                }
            }
            else {

                throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
            }

            return $user;
        }

        throw new NotSupportedException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_APIS_DISABLED ) );
    }

    // yii\db\ActiveRecord ---------------

    /**
     * @return string - db table name
     */
    public static function tableName() {

        return CoreTables::TABLE_USER;
    }

    // User ------------------------------

    // Create -------------

    // Read ---------------

    /**
     * @return ActiveRecord - with site member and role.
     */
    public static function findWithSiteMember() {

        return self::find()->joinWith( 'siteMember' )->joinWith( 'siteMember.site' )->joinWith( 'siteMember.role' );
    }

    /**
     * @return ActiveRecord - with site member, role and permission. It works by specifying a filter for permission name.
     */
    public static function findWithSiteMemberPermission() {

        return self::find()->joinWith( 'siteMember' )->joinWith( 'siteMember.site' )->joinWith( 'siteMember.role' )->joinWith( 'siteMember.role.permissions' );
    }

    /**
     * @param string $token
     * @return User - by accessToken
     */
    public static function findByAccessToken( $token ) {

        return self::find()->where( 'accessToken=:token', [ ':token' => $token ] )->one();
    }

    /**
     * @param string $email
     * @return User - by email
     */
    public static function findByEmail( $email ) {

        return self::find()->where( 'email=:email', [ ':email' => $email ] )->one();
    }

    /**
     * @param string $email
     * @return User - check whether user exist by email
     */
    public static function isExistByEmail( $email ) {

        $user = self::find()->where( 'email=:email', [ ':email' => $email ] )->one();

        return isset( $user );
    }

    /**
     * @param string $username
     * @return User - by username
     */
    public static function findByUsername( $username ) {

        return self::find()->where( 'username=:username', [ ':username' => $username ] )->one();
    }

    /**
     * @param string $username
     * @return User - check whether user exist by username
     */
    public static function isExistByUsername( $username ) {

        $user = self::find()->where( 'username=:username', [ ':username' => $username ] )->one();

        return isset( $user );
    }

    // Update -------------

    // Delete -------------
}

?>