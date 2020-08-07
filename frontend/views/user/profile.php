<?php
use cmsgears\core\common\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();

$this->title = $coreProperties->getSiteTitle() . " | Profile";
?>

<?php $form = ActiveForm::begin( [ 'id' => 'frm-user-update', 'options' => [ 'class' => 'frm-split-40-60' ] ] );?>

<?php if( !$coreProperties->isChangeEmail() ) { ?>
	<?= $form->field( $model, 'email' )->textInput( [ 'readonly' => true ] ) ?>
<?php } else { ?>
	<?= $form->field( $model, 'email' ) ?>
<?php } ?>

<?php if( !$coreProperties->isChangeUsername() ) { ?>
	<?= $form->field( $model, 'username' )->textInput( [ 'readonly' => true ] ) ?>
<?php } else { ?>
	<?= $form->field( $model, 'username' ) ?>
<?php } ?>

<?= $form->field( $model, 'firstName' ) ?>
<?= $form->field( $model, 'lastName' ) ?>
<?= $form->field( $model, 'genderId' )->dropDownList( $genderMap )	?>
<?= $form->field( $model, 'phone' ) ?>

<input type="submit" value="Update" />

<?php ActiveForm::end(); ?>
