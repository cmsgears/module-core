<?php
namespace cmsgears\core\common\models\traits;

// Yii Import
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * It provide methods to map parent and model resource.
 *
 * The model using this trait must have modelId, parentId and parentType columns. It must allow a single model mapping for same parentId and parentType combination.
 *
 * It also provide few additional methods for models having active column to mark the mapping as deleted to avoid allocating a new row each time a mapping is created.
 */
trait MapperTrait {

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

	public static function queryByModelId( $parentId, $parentType, $modelId ) {

		$tableName = self::tableName();

		return self::queryWithModel()->where( "$tableName.parentId=:pid AND $tableName.parentType=:ptype AND $tableName.modelId=:mid", [ ':pid' => $parentId, ':ptype' => $parentType, ':mid' => $modelId ] );
	}

    public static function queryByParent( $parentId, $parentType ) {

		$tableName = self::tableName();

        return self::queryWithModel()->where( "$tableName.parentId=:pid AND $tableName.parentType=:ptype", [ ':pid' => $parentId, ':ptype' => $parentType ] );
    }

	// Read - Find ------------

    public static function findAllByModelId( $modelId ) {

        return self::find()->where( 'modelId=:id', [ ':id' => $modelId ] )->all();
    }

    public static function findByModelId( $parentId, $parentType, $modelId ) {

        return self::queryByModelId( $parentId, $parentType, $modelId )->one();
    }

    public static function findByParent( $parentId, $parentType ) {

        return self::queryByParent( $parentId, $parentType )->all();
    }

    public static function findByParentId( $parentId ) {

        return self::find()->where( 'parentId=:id', [ ':id' => $parentId ] )->all();
    }

    public static function findByParentType( $parentType ) {

        return self::find()->where( 'parentType=:ptype', [ ':ptype' => $parentType ] )->all();
    }

	// Models having active column

    public static function findActiveByParent( $parentId, $parentType ) {

        return self::queryByParent( $parentId, $parentType )->where( 'active=1' )->all();
    }

    public static function findActiveByParentId( $parentId ) {

        return self::find()->where( 'parentId=:pid AND active=1', [ ':pid' => $parentId ] )->all();
    }

    public static function findActiveByParentType( $parentType ) {

        return self::find()->where( 'parentType=:ptype AND active=1', [ ':ptype' => $parentType ] )->all();
    }

    public static function findActiveByModelIdParentType( $modelId, $parentType ) {

        return self::find()->where( 'modelId=:mid AND parentType=:ptype AND active=1',  [ ':mid' => $modelId, ':ptype' => $parentType] )->all();
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

    /**
     * Delete all entries related to given model id
     */
    public static function deleteByModelId( $modelId ) {

        self::deleteAll( 'modelId=:mid', [ ':mid' => $modelId ] );
    }
}
