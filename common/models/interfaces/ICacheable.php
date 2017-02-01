<?php
namespace cmsgears\core\common\models\interfaces;

interface ICacheable {

	public function cache( $model, $config = [] );
}
