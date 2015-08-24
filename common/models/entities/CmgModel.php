<?php
namespace cmsgears\core\common\models\entities;

class CmgModel extends CmgEntity {

	/**
	 * @return array - categories by given parent id and type.
	 */
	public static function findByParentIdType( $parentId, $parentType ) {

		return self::find()->where( 'parentId=:id AND parentType=:type', [ ':id' => $parentId, ':type' => $parentType ] )->all();
	}

	// Delete ----

	/**
	 * Delete categories by given parent id and type.
	 */
	public static function deleteByParentIdType( $parentId, $parentType ) {

		self::deleteAll( 'parentId=:id AND parentType=:type', [ ':id' => $parentId, ':type' => $parentType ] );
	}
}