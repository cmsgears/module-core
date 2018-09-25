<?php

$common = Yii::getAlias("@common");
$frontend = Yii::getAlias("@frontend");

return yii\helpers\ArrayHelper::merge(
	require( $frontend . '/config/main.php' ),
	require( $frontend . '/config/main-env.php' ),
	require( $frontend . '/config/test.php' ),
	require( $frontend . '/config/test-env.php' )
	
);
