<?php
use \Yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title	= $coreProperties->getSiteTitle() . ' | Login';
?>
<h1>Login</h1>
<?php
	if( $coreProperties->isPublicRegister() ) {

		$form = ActiveForm::begin( [ 'id' => 'frm-login' ] );
?>
	<ul>
		<li>
			<?= $form->field( $model, 'email' )->textInput( [ 'placeholder' => 'Email*' ] ) ?>
		</li>
		<li>
			<?= $form->field( $model, 'password' )->passwordInput( array( 'placeholder' => 'Password*' ) ) ?>
		</li>
		<li>
			<?= $form->field( $model, 'rememberMe' )->checkbox() ?>
		</li>
		<li>
			<?= Html::a( "Forgot your Password ?", [ '/forgot-password' ] ) ?>
		</li>
		<li class="clearfix align-center">
			<input type="submit" value="Login" />
		</li>
	</ul>
<?php
		ActiveForm::end();
	}
	else {
?>
	<p class="warning">Site login is disabled by Admin.</p>
<?php } ?>