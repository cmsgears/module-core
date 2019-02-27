<?php
use yii\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title	= $coreProperties->getSiteTitle() . ' | Reset Password';
?>
<h1>Reset Password</h1>
<?php
	if( Yii::$app->session->hasFlash( 'message' ) ) {
?>
	<p><?= Yii::$app->session->getFlash( 'message' ) ?></p>
<?php
	}
	else {

		$form = ActiveForm::begin( [ 'id' => 'frm-reset-password' ] );
?>
		<ul>
			<li>
				<?= $form->field( $model, 'email' )->textInput( [ 'placeholder' => 'Email*', 'readOnly' => true ] ) ?>
			</li>
			<li>
				<?= $form->field( $model, 'password' )->passwordInput( [ 'placeholder' => 'Password*' ] ) ?>
			</li>
			<li>
				<?= $form->field( $model, 'password_repeat' )->passwordInput( [ 'placeholder' => 'Repeat Password*' ] ) ?>
			</li>
			<li>
				<input type="submit" value="Reset" />
			</li>
		</ul>
<?php
		ActiveForm::end();
	}
?>