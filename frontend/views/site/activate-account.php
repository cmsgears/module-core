<?php
use \Yii;
use yii\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Activate Account";
?>
<h1>Forgot Password</h1>
<?php 
	if( Yii::$app->session->hasFlash( "message" ) ) {
?>
	<p> <?php echo Yii::$app->session->getFlash( "message" ); ?> </p>
<?php
	}
	else {

		$form = ActiveForm::begin( ['id' => 'frm-activate-account'] );
?>
		<ul>
			<li>
				<?= $form->field( $model, 'password' )->passwordInput() ?>
			</li>
			<li>
				<?= $form->field( $model, 'password_repeat' )->passwordInput() ?>
			</li>
			<li>
				<input type="submit" value="Activate" />
			</li>
		</ul>
<?php
		ActiveForm::end();
	}
?>