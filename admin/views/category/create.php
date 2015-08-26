<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use cmsgears\widgets\cleditor\ClEditor;
use cmsgears\files\widgets\AvatarUploader;

if( $dropDown ) {
	
	$dropDown = 'Dropdown';
}
else {
	
	$dropDown = 'Category';
}
$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Add '.$dropDown;

// Sidebar
$this->params['sidebar-parent'] = 'sidebar-dropdown';
$this->params['sidebar-child'] 	= 'dropdown';

ClEditor::widget( [ 'selector' => '.content-editor' ] );
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Add <?=$dropDown?></h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-dropdown-create', 'options' => ['class' => 'frm-split form-with-editor' ] ] );?>

    	<?= $form->field( $model, 'name' ) ?>  
    	<?= $form->field( $model, 'description' ) ?> 
    	<?= $form->field( $model, 'icon' ) ?>  

    	<h4><?=$dropDown?> Avatar</h4>
		<?=AvatarUploader::widget( [ 'options' => [ 'id' => 'avatar-dropdown', 'class' => 'file-uploader' ], 'model' => $avatar, 'modelClass' => 'Avatar',  'directory' => 'avatar', 'btnChooserIcon' => 'icon-action icon-action-edit' ] );?>
  
		<div class="box-filler"></div>

		<?=Html::a( "Cancel", $returnUrl, ['class' => 'btn' ] );?>
		<input type="submit" value="Create" />

		<?php ActiveForm::end(); ?>
	</div>
</section>