<?php
use yii\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Login";
?>
<section class="wrap-content wrap-content-full container clearfix">
	<div class="cud-box wrap-login-box frm-split">
		<h1>Login</h1>
		<?php $form = ActiveForm::begin( ['id' => 'frm-login' ] );?>

    	<?= $form->field( $model, 'email' ) ?>
    	<?= $form->field( $model, 'password' )->passwordInput() ?>
		
		<div class="box-filler"></div>

		<input type="submit" value="Login" />
		
		<?php ActiveForm::end(); ?>
	</div>	
</section>