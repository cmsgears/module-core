<?php
namespace cmsgears\core\common\models\traits;

// Yii Import
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * The model using this trait must have parentId and parentType columns. It allows to map anonymous table having id and unique type to another table.
 */
trait ResourceTrait {

    // Instance methods --------------------------------------------

    // Yii interfaces ------------------------

    // Yii classes ---------------------------

    // CMG interfaces ------------------------

    // CMG classes ---------------------------

    // ParentTypeTrait -----------------------

    // Validators -------------

    // Static Methods ----------------------------------------------

    // Yii classes ---------------------------

    // CMG classes ---------------------------

    // ParentTypeTrait -----------------------

    // Read - Query -----------

    // Read - Query -----------

    public static function queryByParent( $parentId, $parentType ) {

        if( static::$multiSite ) {

            $siteId	= Yii::$app->core->siteId;

            return static::find()->where( 'parentId=:pid AND parentType=:ptype AND siteId=:siteId', [ ':pid' => $parentId, ':ptype' => $parentType, ':siteId' => $siteId ] );
        }
        else {

            return static::find()->where( 'parentId=:pid AND parentType=:ptype', [ ':pid' => $parentId, ':ptype' => $parentType ] );
        }
    }

    // Read - Find ------------

    public static function findByParent( $parentId, $parentType, $first = false ) {

        if( $first ) {

            return self::queryByParent( $parentId, $parentType )->one();
        }

        return self::queryByParent( $parentId, $parentType )->all();
    }

    public static function findByParentId( $parentId ) {

        return self::find()->where( 'parentId=:id', [ ':id' => $parentId ] )->all();
    }

    public static function findByParentType( $parentType ) {

        return self::find()->where( 'parentType=:ptype', [ ':ptype' => $parentType ] )->all();
    }

    // Create -----------------

    // Update -----------------

    // Delete -----------------

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
