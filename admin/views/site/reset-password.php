<?php
use yii\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Reset Password";
?>
<section class="wrap-content wrap-content-full container clearfix">
	<div class="cud-box wrap-login-box frm-split">
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
		    	<?= $form->field( $model, 'email' )->textInput( [ 'placeholder' => 'Email*' ] ) ?>
		    	<?= $form->field( $model, 'password' )->passwordInput( [ 'placeholder' => 'Password*' ] ) ?>
		    	<?= $form->field( $model, 'password_repeat' )->passwordInput( [ 'placeholder' => 'Repeat Password*' ] ) ?>

				<div class="box-filler"></div>
				
				<input type="submit" value="Submit" />
		<?php
				ActiveForm::end();
			}
		?>
	</div>
</section>