<?php
use \Yii;
use yii\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Profile";
?>

<?php $form = ActiveForm::begin( ['id' => 'frm-user-update', 'options' => ['class' => 'frm-split-40-60' ] ] );?>

<?= $form->field( $model, 'email' ) ?>
<?= $form->field( $model, 'username' ) ?>
<?= $form->field( $model, 'firstName' ) ?>
<?= $form->field( $model, 'lastName' ) ?>
<?= $form->field( $model, 'genderId' )->dropDownList( $genderMap )  ?>
<?= $form->field( $model, 'phone' ) ?>
<?= $form->field( $model, 'newsletter' )->checkbox() ?>

<input type="submit" value="Update" />

<?php ActiveForm::end(); ?>