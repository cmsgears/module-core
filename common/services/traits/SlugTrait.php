<?php
namespace cmsgears\core\common\services\traits;

/**
 * Used by services with base model having sluggable behaviour.
 */
trait SlugTrait {

	public function getBySlug( $slug ) {

		$modelClass	= self::$modelClass;

		return $modelClass::queryBySlug( $slug )->one();
    }

	public static function findBySlug( $slug ) {

		$modelClass	= static::$modelClass;

		return $modelClass::queryBySlug( $slug )->one();
    }
}

?>