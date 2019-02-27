<?php

$common = Yii::getAlias("@common");

return yii\helpers\ArrayHelper::merge(
	require( $common . '/config/main.php' ),
	require( $common . '/config/main-env.php' ),
	require( $common . '/config/test.php' ),
	require( $common . '/config/test-env.php' )
	
);
