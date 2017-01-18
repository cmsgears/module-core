<?php
namespace cmsgears\core\common\models\interfaces;

interface IFileCacheable extends ICacheable {

	public function cacheFile( $model, $config = [] );
}
