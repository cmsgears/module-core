<?php
namespace cmsgears\core\common\models\entities;

abstract class CmgModel extends CmgEntity {

	// Read ----

	/**
	 * @return array - model by given parent type.
	 */
	public static function findByParentType( $parentType ) {

		return self::find()->where( 'parentType=:id', [ ':id' => $parentType ] )->all();
	}

	/**
	 * @return array - model by given parent id and type.
	 */
	public static function findByParent( $parentId, $parentType ) {

		return self::find()->where( 'parentId=:pid AND parentType=:type', [ ':pid' => $parentId, ':type' => $parentType ] )->all();
	}

	// Delete ----

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