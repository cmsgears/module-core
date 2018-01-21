<?php
namespace cmsgears\core\common\services\interfaces\cache;

interface IFileCacheable extends ICacheable {

	public function cacheFile( $model, $config = [] );
}
