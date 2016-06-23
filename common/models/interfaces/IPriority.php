<?php
namespace cmsgears\core\common\models\interfaces;

interface IPriority {

	const PRIORITY_LOW		=    0;
	const PRIORITY_MEDIUM	= 1000;
	const PRIORITY_HIGH		= 1500;

	public function isPriorityLow(  $strict = true );

	public function isPriorityMedium( $strict = true );

	public function isPriorityHigh( $strict = true );
}

?>