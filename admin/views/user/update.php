<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

use cmsgears\files\widgets\FileUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Update User";

// Sidebar and Return URL
$sidebar						= $this->context->sidebar;
$returnUrl						= $this->context->returnUrl;
$this->params['sidebar-parent'] = $sidebar[ 'parent' ];
$this->params['sidebar-child'] 	= $sidebar[ 'child' ];
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

    	<?php if( isset( $roleMap ) ) { ?>
			<?= $form->field( $siteMember, 'roleId' )->dropDownList( $roleMap )  ?>
		<?php } else { ?>
			<?= $form->field( $siteMember, 'roleId' )->hiddenInput()->label( false )  ?>
		<?php } ?>

		<?= $form->field( $model, 'status' )->dropDownList( $status ) ?>

		<?=Html::a( 'Back', $returnUrl, [ 'class' => 'btn' ] );?>
		<input type="submit" value="Update" />

		<?php ActiveForm::end(); ?>
	</div>	
</section>