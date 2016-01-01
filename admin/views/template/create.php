<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\Editor;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Add Template';

// Sidebar and Return URL
$sidebar						= $this->context->sidebar;
$returnUrl						= $this->context->returnUrl;
$this->params['sidebar-parent'] = $sidebar[ 'parent' ];
$this->params['sidebar-child'] 	= $sidebar[ 'child' ];

Editor::widget( [ 'selector' => '.content-editor' ] );
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Add Template</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-template-create', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'name' ) ?>  
    	<?= $form->field( $model, 'description' )->textarea() ?> 
    	<?= $form->field( $model, 'layout' ) ?>  
		<?= $form->field( $model, 'viewPath' ) ?>
		<?= $form->field( $model, 'adminView' ) ?>
		<?= $form->field( $model, 'frontendView' ) ?>

    	<h4>Template Content</h4>
    	<?= $form->field( $model, 'content' )->textarea( [ 'class' => 'content-editor' ] )->label( false ) ?>

		<?=Html::a( "Back", $returnUrl, ['class' => 'btn' ] );?>
		<input type="submit" value="Create" />

		<?php ActiveForm::end(); ?>
	</div>
</section>