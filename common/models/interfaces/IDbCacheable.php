<?php
namespace cmsgears\core\common\models\interfaces;

interface IDbCacheable extends ICacheable {

	public function cacheDb( $model, $config = [] );
}