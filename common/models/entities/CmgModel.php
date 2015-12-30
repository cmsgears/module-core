<?php
namespace cmsgears\core\common\models\entities;

class CmgModel extends CmgEntity {

	// Read ----

	/**
	 * @return array - model by given parent type.
	 */
	public static function findByParentType( $parentType ) {

		return self::find()->where( 'parentType=:id', [ ':id' => $parentType ] )->all();
	}

	/**
	 * @return array - model by given parent id.
	 */
	public static function findByParentId( $parentId ) {

		return self::find()->where( 'parentId=:id', [ ':id' => $parentId ] )->all();
	}

	/**
	 * @return array - model by given parent id and type.
	 */
	public static function findByParentIdParentType( $parentId, $parentType ) {

		return self::find()->where( 'parentId=:pid AND parentType=:type', [ ':pid' => $parentId, ':type' => $parentType ] )->all();
	}

	// Delete ----

	/**
	 * Delete all entries related to a parent
	 */
	public static function deleteByParentId( $parentId ) {

		self::deleteAll( 'parentId=:id', [ ':id' => $parentId ] );
	}

	/**
	 * Delete all entries by given parent id and type.
	 */
	public static function deleteByParentIdParentType( $parentId, $parentType ) {

		self::deleteAll( 'parentId=:pid AND parentType=:type', [ ':pid' => $parentId, ':type' => $parentType ] );
	}
}