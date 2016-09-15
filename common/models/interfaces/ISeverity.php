<?php
namespace cmsgears\core\common\models\interfaces;

interface ISeverity {

    const SEVERITY_DEFAULT	=    0;
    const SEVERITY_LOW		=  500;
    const SEVERITY_MEDIUM	= 1000;
    const SEVERITY_HIGH		= 1500;

    public function getSeverityStr();

    public function isSeverityDefault(  $strict = true );

    public function isSeverityLow(  $strict = true );

    public function isSeverityMedium( $strict = true );

    public function isSeverityHigh( $strict = true );
}
