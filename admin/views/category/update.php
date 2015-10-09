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
$this->title 	= $coreProperties->getSiteTitle() . ' | Add Dropdown';

// Sidebar and Return URL
$sidebar						= $this->context->sidebar;
$returnUrl						= $this->context->returnUrl;
$this->params['sidebar-parent'] = $sidebar[ 'parent' ];
$this->params['sidebar-child'] 	= $sidebar[ 'child' ];

ClEditor::widget( [ 'selector' => '.content-editor' ] );
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Update Dropdown</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-dropdown-create', 'options' => ['class' => 'frm-split form-with-editor' ] ] );?>

    	<?= $form->field( $model, 'name' ) ?>  
    	<?= $form->field( $model, 'description' ) ?>  
    	<?= $form->field( $model, 'icon' ) ?>  

    	<h4>Dropdown Avatar</h4>		 
  		<?=AvatarUploader::widget( 
				[ 'options' => [ 'id' => 'avatar-dropdown', 'class' => 'file-uploader' ], 
				'model' => $avatar, 'modelClass' => 'Avatar',  
				'directory' => 'avatar', 'btnChooserIcon' => 'icon-action icon-action-edit',
				'postUploadMessage' => 'Please click on update button to save avatar.' ] 
		);?>
  		
		<div class="box-filler"></div>

		<?=Html::a( "Cancel", $returnUrl, ['class' => 'btn' ] );?>
		<input type="submit" value="Update" />

		<?php ActiveForm::end(); ?>
	</div>
</section>