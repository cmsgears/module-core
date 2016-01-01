<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use cmsgears\widgets\cleditor\ClEditor;
use cmsgears\files\widgets\AvatarUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Update Category';

// Sidebar and Return URL
$sidebar						= $this->context->sidebar;
$returnUrl						= $this->context->returnUrl;
$this->params['sidebar-parent'] = $sidebar[ 'parent' ];
$this->params['sidebar-child'] 	= $sidebar[ 'child' ];

ClEditor::widget( [ 'selector' => '.content-editor' ] );
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Update Category</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-dropdown-create', 'options' => ['class' => 'frm-split form-with-editor' ] ] );?>

    	<?= $form->field( $model, 'name' ) ?>  
    	<?= $form->field( $model, 'description' ) ?>
    	<?= $form->field( $model, 'parentId' )->dropDownList( $categoryMap ) ?>
    	<?= $form->field( $model, 'icon' ) ?>
    	<?= $form->field( $model, 'htmlOptions' )->textarea() ?>
		<?= $form->field( $model, 'featured' )->checkbox() ?>

    	<h4>Category Avatar</h4>
  		<?=AvatarUploader::widget( 
				[ 'options' => [ 'id' => 'avatar-dropdown', 'class' => 'file-uploader' ], 
				'model' => $avatar, 'modelClass' => 'Avatar', 'directory' => 'avatar', 'btnChooserIcon' => 'icon-action icon-action-edit' ] 
		);?>

		<div class="box-filler"></div>

		<?=Html::a( "Cancel", $returnUrl, ['class' => 'btn' ] );?>
		<input type="submit" value="Update" />

		<?php ActiveForm::end(); ?>
	</div>
</section>