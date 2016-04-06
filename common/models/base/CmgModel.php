<?php
namespace cmsgears\core\common\models\base;

abstract class CmgModel extends \cmsgears\core\common\models\base\CmgEntity {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    // yii\base\Component ----------------

    // yii\base\Model --------------------

    // CmgModel --------------------------

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    // CmgModel --------------------------

    // Create -------------

    // Read ---------------

    /**
     * @return array - model by given parent id.
     */
    public static function findByParentId( $parentId ) {

        return self::find()->where( 'parentId=:id', [ ':id' => $parentId ] )->all();
    }

    /**
     * @return array - model by given parent type.
     */
    public static function findByParentType( $parentType ) {

        return self::find()->where( 'parentType=:type', [ ':type' => $parentType ] )->all();
    }

    /**
     * @return array - model by given parent id and type.
     */
    public static function findByParent( $parentId, $parentType ) {

        return self::find()->where( 'parentId=:pid AND parentType=:type', [ ':pid' => $parentId, ':type' => $parentType ] )->all();
    }

    // Update -------------

    // Delete -------------

    /**
     * Delete all entries related to given parent id
     */
    public static function deleteByParentId( $parentId ) {

        self::deleteAll( 'parentId=:id', [ ':id' => $parentId ] );
    }

    /**
     * Delete all entries related to given parent type
     */
    public static function deleteByParentType( $parentType ) {

        self::deleteAll( 'parentType=:id', [ ':id' => $parentType ] );
    }

    /**
     * Delete all entries by given parent id and type.
     */
    public static function deleteByParent( $parentId, $parentType ) {

        self::deleteAll( 'parentId=:pid AND parentType=:type', [ ':pid' => $parentId, ':type' => $parentType ] );
    }
}

?>