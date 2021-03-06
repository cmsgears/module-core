<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title	= $coreProperties->getSiteTitle() . ' | Login';
?>
<h1>Login</h1>
<?php
	if( $coreProperties->isLogin() ) {

		$form = ActiveForm::begin( [ 'id' => 'frm-login' ] );
?>
	<ul>
		<li>
			<?= $form->field( $model, 'email' )->textInput( [ 'placeholder' => 'Email / Username *' ] ) ?>
		</li>
		<li>
			<?= $form->field( $model, 'password' )->passwordInput( [ 'placeholder' => 'Password *' ] ) ?>
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
