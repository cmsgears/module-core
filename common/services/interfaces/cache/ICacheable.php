<?php
namespace cmsgears\core\common\services\interfaces\cache;

interface ICacheable {

	public function cache( $model, $config = [] );
}
