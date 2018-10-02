<?php

namespace cmsgears\core\admin\tests\unit\services\entities;

//use common\fixtures\UserFixture;
//use common\models\entities\CashbackActivity;
/**
 * Login form test
 */
class HelloTest extends \Codeception\Test\Unit
{
	public function testHello(){
		
		expect( "hello", true )->true();
	}
}
