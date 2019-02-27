<?php
use yii\helpers\Html;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;" />
	<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE;" />
	<title><?= Html::encode( $this->title ) ?></title>
	<?php include dirname( __FILE__ ) . '/styles.php'; ?>
	<?php $this->head() ?>
</head>
<body style="width: 100% !important; text-align: center; margin: 0; padding: 0; size-adjust: 100%; -ms-text-size-adjust: 100%;">
	<?php $this->beginBody() ?>
	<?= $content ?>
	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
