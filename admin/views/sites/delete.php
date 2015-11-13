<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use cmsgears\files\widgets\AvatarUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Delete Site';

// Sidebar
$sidebar						= $this->context->sidebar;
$this->params['sidebar-parent'] = $sidebar[ 'parent' ];
$this->params['sidebar-child'] 	= $sidebar[ 'child' ];
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Delete Site</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-site-delete', 'options' => [ 'class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => true ] ) ?>  
    	<?= $form->field( $model, 'order' )->textInput( [ 'readonly' => true ] ) ?> 

    	<h4>Site Avatar</h4>
		<?=AvatarUploader::widget( [ 'options' => [ 'id' => 'avatar-site', 'class' => 'file-uploader' ], 'model' => $avatar, 'modelClass' => 'Avatar',  'directory' => 'avatar', 'btnChooserIcon' => 'icon-action icon-action-edit' ] );?>

    	<h4>Site Banner</h4>
		<?=AvatarUploader::widget( [ 'options' => [ 'id' => 'banner-site', 'class' => 'file-uploader' ], 'model' => $banner, 'modelClass' => 'Banner',  'directory' => 'avatar', 'btnChooserIcon' => 'icon-action icon-action-edit' ] );?>

		<div class="box-filler"></div>

		<?=Html::a( 'Cancel', 'all', [ 'class' => 'btn' ] );?>
		<input type="submit" value="Delete" />

		<?php ActiveForm::end(); ?>
	</div>
</section>