<?php
use \Yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Register";
?>
<h1>Register</h1>
<?php
	if( $coreProperties->isPublicRegister() ) {

		if( Yii::$app->session->hasFlash( 'message' ) ) {
?>
			<p><?= Yii::$app->session->getFlash( 'message' ) ?></p>
<?php
		}
		else {

			$form 	= ActiveForm::begin( [ 'id' => 'frm-registration' ] );
			$terms	= "I agree to the " . Html::a( "Terms", [ '/terms' ], null ) . " and " . Html::a( "Privacy Policy", [ '/privacy' ], null ) . ".";
?>
    	<ul>
    		<li>
    			<?= $form->field( $model, 'email' )->textInput( [ 'placeholder' => 'Email*' ] ) ?>
    		</li>
    		<li>
    			<?= $form->field( $model, 'password' )->passwordInput( [ 'placeholder' => 'Password*' ] ) ?>
    		</li>
    		<li>
    			<?= $form->field( $model, 'password_repeat' )->passwordInput([ 'placeholder' => 'Confirm Password*' ] ) ?>
    		</li>
    		<li>
    			<?= $form->field( $model, 'username' )->textInput( [ 'placeholder' => 'Username' ] ) ?>
    		</li>
    		<li>
    			<?= $form->field( $model, 'firstName' )->textInput( [ 'placeholder' => 'First Name' ] ) ?>
    		</li>
    		<li>
    			<?= $form->field( $model, 'lastName' )->textInput( [ 'placeholder' => 'Last Name' ] ) ?>
    		</li>
    		<li>
    			<?= $form->field( $model, 'terms' )->checkbox( [ 'label' => $terms ] ) ?>
    		</li>
    		<li>
    			<input type="submit" value="Register" />
    		</li>
    	</ul>
<?php
			ActiveForm::end();
		}
	}
	else {
?>
		<p class="warning">Site registration is disabled by Admin.</p>
<?php } ?>