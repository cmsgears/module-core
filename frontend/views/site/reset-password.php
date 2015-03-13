<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Reset Password";
?>
<div class="common-public-wrapper clearfix">
	<div class="page-wrapper clearfix">
		<div class="form-block-wrapper clearfix">
			<div class="module-header">
				<h1 class="align-middle">Reset Password</h1>
			</div>
			<div class="module-content">	
				<?php if( Yii::$app->session->hasFlash( "success" ) ) { ?>
					<div class='frm-message'><p> <?php echo Yii::$app->session->getFlash( "success" ); ?> </p></div>
				<?php
					}
					else {
		
		        		$form = ActiveForm::begin( [ 'id' => 'frm-reset-password', 'options' => [ 'class' => 'frm-medium' ] ] ); 
		        ?>
			        	
			        	<ul>
			        		<li class="clearfix">
			        			<?= $form->field( $model, 'email' )->textInput( [ 'placeholder' => 'Email*' ] )->label( false ) ?>
			        		</li>
			        		<li class="clearfix">
			        			<?= $form->field( $model, 'password' )->passwordInput( [ 'placeholder' => 'Password*' ] )->label( false ) ?>
			        		</li>
			        		<li class="clearfix">
			        			<?= $form->field( $model, 'password_repeat' )->passwordInput( [ 'placeholder' => 'Repeat Password*' ] )->label( false ) ?>
			        		</li>
			        		<li class="clearfix align-center">
			        			<input type="submit" value="Reset" />
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