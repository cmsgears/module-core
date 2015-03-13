<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Activate Account";
?>
<div class="common-public-wrapper clearfix">
	<div class="page-wrapper clearfix">
		<div class="form-block-wrapper clearfix">
			<div class="module-header">
				<h1 class="align-middle">Activate Account</h1>
			</div>
			<div class="module-content">
				<?php if( Yii::$app->session->hasFlash( "message" ) ) { ?>
					<p> <?php echo Yii::$app->session->getFlash( "message" ); ?> </p>
				<?php		
					}
					else {
						
		        		$form = ActiveForm::begin( ['id' => 'frm-activate-account'] );
		        ?>
		        		<ul>
		        			<li class="clearfix">
		        				<?= $form->field( $model, 'password' )->passwordInput() ?>
		        			</li>
		        			<li class="clearfix">
		        				<?= $form->field( $model, 'password_repeat' )->passwordInput() ?>
		        			</li>
		        			<li class="clearfix">
		        				<input type="submit" value="Activate" />
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