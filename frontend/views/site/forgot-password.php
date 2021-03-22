<?php
// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title	= $coreProperties->getSiteTitle() . " | Forgot Password";
?>
<h1>Forgot Password</h1>
<?php
	if( Yii::$app->session->hasFlash( 'message' ) ) {
?>
	<p><?= Yii::$app->session->getFlash( 'message' ) ?></p>
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
				<?= $form->field( $model, 'email' )->textInput( [ 'placeholder' => 'Email *' ] ) ?>
			</li>
			<li>
				<input type="submit"  value="Submit" />
			</li>
		</ul>
<?php
		ActiveForm::end();
	}
