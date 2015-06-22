<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

use cmsgears\files\widgets\FileUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Update User";

// Sidebar
$this->params['sidebar-parent'] = 'sidebar-identity';
$this->params['sidebar-child'] 	= 'user';
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Update User</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-user-update', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'email' ) ?>
    	<?= $form->field( $model, 'username' ) ?>
    	<h4>User Avatar</h4>
		<?=FileUploader::widget( [ 'options' => [ 'id' => 'avatar-user', 'class' => 'file-uploader' ], 'model' => $model->avatar,  'btnChooserIcon' => 'icon-action icon-action-edit' ] );?>
		<?= $form->field( $model, 'firstName' ) ?>
		<?= $form->field( $model, 'lastName' ) ?>
		<?= $form->field( $model, 'genderId' )->dropDownList( $genders )  ?>
		<?= $form->field( $model, 'phone' ) ?>
		<?= $form->field( $siteMember, 'roleId' )->dropDownList( $roles )  ?>
		<?= $form->field( $model, 'status' )->dropDownList( $status ) ?>
		<?= $form->field( $model, 'newsletter' )->checkbox() ?>

		<?=Html::a( "Back", $returnUrl, ['class' => 'btn' ] );?>
		<input type="submit" value="Update" />

		<?php ActiveForm::end(); ?>
	</div>	
</section>