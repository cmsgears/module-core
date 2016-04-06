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
class RolePermission extends \cmsgears\core\common\models\base\CmgEntity {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    // yii\base\Component ----------------

    // yii\base\Model --------------------

    /**
     * @inheritdoc
     */
    public function rules() {

        return [
            [ [ 'roleId', 'permissionId' ], 'required' ],
            [ [ 'roleId', 'permissionId' ], 'unique', 'targetAttribute' => [ 'roleId', 'permissionId' ] ],
            [ [ 'roleId', 'permissionId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'roleId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ROLE ),
            'permissionId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PERMISSION )
        ];
    }

    // RolePermission --------------------

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

    // yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_ROLE_PERMISSION;
    }

    // RolePermission --------------------

    // Create -------------

    // Read ---------------

    // Update -------------

    // Delete -------------

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

?>