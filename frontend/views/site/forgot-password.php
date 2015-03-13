<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Forgot Password";
?>
<div class="common-public-wrapper clearfix">
	<div class="page-wrapper clearfix">
		<div class="form-block-wrapper clearfix">
			<div class="module-header">
				<h1 class="align-middle">Forgot Password</h1>
			</div>
			<div class="module-content">
				<?php if( Yii::$app->session->hasFlash( "success" ) ) { ?>
					<div class='frm-message'><p> <?php echo Yii::$app->session->getFlash( "success" ); ?> </p></div>
				<?php
					}
					else {
		
		        		$form = ActiveForm::begin( [ 'id' => 'frm-forgot-password', 'options' => [ 'class' => 'frm-medium' ] ] ); 
		        ?>
			        	
			        	<ul>
			        		<li class="clearfix">
			        			<p> Please provide the email address that you used when you signed up for your CMS Gears account.</p>
			        		</li>	
			        		<li class="clearfix">
			        			<?= $form->field( $model, 'email' )->textInput( [ 'placeholder' => 'Email*' ] )->label( false ) ?>
			        		</li>
			        		
			        		<li class="clearfix align-center">
			        			<input type="submit"  value="Submit" />
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