<?php

$common = Yii::getAlias("@common");
$backend = Yii::getAlias("@backend");

return yii\helpers\ArrayHelper::merge(
	require( $backend . '/config/main.php' ),
	require( $backend . '/config/main-env.php' ),
	require( $backend . '/config/test.php' ),
	require( $backend . '/config/test-env.php' )
	
);
