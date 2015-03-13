<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Login";
?>
<div class="common-public-wrapper clearfix">
	<div class="page-wrapper clearfix">
		<div class="form-block-wrapper clearfix">
			<div class="module-header">
				<h1 class="align-middle">Login</h1>
			</div>
			<div class="module-content">
			<?php if( Yii::$app->session->hasFlash( "success" ) ) { ?>
				<div class='frm-message'><p> <?php echo Yii::$app->session->getFlash( "success" ); ?> </p></div>
			<?php
				}
				else {
	
	        		$form = ActiveForm::begin( [ 'id' => 'frm-login', 'options' => [ 'class' => 'frm-medium' ] ] );
	        ?>
		        	<ul>
		        		<li class="clearfix">
		        			<?= $form->field( $model, 'email' )->textInput( [ 'placeholder' => 'Email*' ] )->label( false ) ?>
		        		</li>
		        		<li class="clearfix">
		        			<?= $form->field( $model, 'password' )->passwordInput( array( 'placeholder' => 'Password*' ) )->label( false ) ?>
		        		</li>
		        		<li>
		        			<?= $form->field( $model, 'rememberMe' )->checkbox() ?>
		        		</li>
		        		<li>
		        			<?= Html::a( "Forgot your Password ?", [ '/site/forgot-password' ] ) ?>
		        		</li>
		        		<li class="clearfix align-center">
		        			<input type="submit" value="Login" />
		        		</li>
		        	</ul>
	        <?php 
	        		ActiveForm::end();
				}
			?>
			</div>
		</div>
	</div>
</div>