<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

use cmsgears\files\widgets\AvatarUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Update User';
$returnUrl		= $this->context->returnUrl;
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Update User</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-user' ] );?>

    	<?= $form->field( $model, 'email' ) ?>
    	<?= $form->field( $model, 'username' ) ?>
		<?= $form->field( $model, 'firstName' ) ?>
		<?= $form->field( $model, 'lastName' ) ?>

    	<?php if( isset( $roleMap ) ) { ?>
			<?= $form->field( $siteMember, 'roleId' )->dropDownList( $roleMap )  ?>
		<?php } else { ?>
			<?= $form->field( $siteMember, 'roleId' )->hiddenInput()->label( false )  ?>
		<?php } ?>

		<?= $form->field( $model, 'status' )->dropDownList( $status ) ?>

		<div class="box-content clearfix">
			<div class="header">User Avatar</div>
			<?= AvatarUploader::widget([
				'options' => [ 'id' => 'avatar-user', 'class' => 'file-uploader' ], 
				'model' => $avatar 
			]); ?>
		</div>

		<div class="clear filler-height"></div>

		<div class="align align-middle">
			<?=Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] );?>
			<input class="btn btn-medium" type="submit" value="Update" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>