<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Register";
?>
<div class="common-public-wrapper clearfix">
	<div class="page-wrapper clearfix">
		<div class="form-block-wrapper clearfix">
			<div class="module-header">
				<h1 class="align-middle">Register</h1>
			</div>
			<div class="module-content">
				<?php if( Yii::$app->session->hasFlash( "success" ) ) { ?>
					<div class='frm-message'><p> <?php echo Yii::$app->session->getFlash( "success" ); ?> </p></div>
				<?php
					}
					else {
		
		        		$form 	= ActiveForm::begin( ['id' => 'frm-registration', 'options' => [ 'class' => 'frm-medium' ] ] ); 
						$terms	= "I agree to the " . Html::a( "Terms", [ '/terms' ], null ) . " and " . Html::a( "Privacy Policy", [ '/privacy' ], null ) . ".";
		        ?>
			        	
			        	<ul>	
			        		<li class="clearfix">
			        			<?= $form->field( $model, 'email' )->textInput( [ 'placeholder' => 'Email*' ] )->label( false ) ?>
			        		</li>
			        		<li class="clearfix">
			        			<?= $form->field( $model, 'password' )->passwordInput( [ 'placeholder' => 'Password*' ] )->label( false ) ?>
			        		</li>
			        		<li class="clearfix">
			        			<?= $form->field( $model, 'password_repeat' )->passwordInput([ 'placeholder' => 'Confirm Password*' ] )->label( false ) ?>
			        		</li>
			        		<li class="clearfix">
			        			<?= $form->field( $model, 'nickName' )->textInput( [ 'placeholder' => 'Username' ] )->label( false ) ?>
			        		</li>
			        		<li class="clearfix">
			        			<?= $form->field( $model, 'firstName' )->textInput( [ 'placeholder' => 'First Name' ] )->label( false ) ?>
			        		</li>
			        		<li class="clearfix">
			        			<?= $form->field( $model, 'lastName' )->textInput( [ 'placeholder' => 'Last Name' ] )->label( false ) ?>
			        		</li>
			        		<li class="clearfix">
			        			<?= $form->field( $model, 'terms' )->checkbox( [ 'label' => $terms ] ) ?>
			        		</li>
			        		<li class="clearfix">
			        			<?= $form->field( $model, 'newsletter' )->checkbox() ?>
			        		</li>
			        		<li class="clearfix align-center">
			        			<input type="submit" value="Register" />
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