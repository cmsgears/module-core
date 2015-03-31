<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Forgot Password";
?>
<h1>Forgot Password</h1>
<?php 
	if( Yii::$app->session->hasFlash( "success" ) ) { 
?>
	<p> <?php echo Yii::$app->session->getFlash( "success" ); ?> </p>
<?php
	}
	else {

		$form = ActiveForm::begin( [ 'id' => 'frm-forgot-password' ] ); 
?>
    	
    	<ul>
    		<li>
    			Please provide the email address used while signing up.
    		</li>	
    		<li>
    			<?= $form->field( $model, 'email' )->textInput( [ 'placeholder' => 'Email*' ] ) ?>
    		</li>
    		
    		<li>
    			<input type="submit"  value="Submit" />
    		</li>
    	</ul>
<?php
		ActiveForm::end();
	}
?>