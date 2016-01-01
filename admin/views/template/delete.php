<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\Editor;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Delete Template';

// Sidebar and Return URL
$sidebar						= $this->context->sidebar;
$returnUrl						= $this->context->returnUrl;
$this->params['sidebar-parent'] = $sidebar[ 'parent' ];
$this->params['sidebar-child'] 	= $sidebar[ 'child' ];

Editor::widget( [ 'selector' => '.content-editor' ] );
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Delete Template</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-template-delete', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => 'true' ] ) ?>  
    	<?= $form->field( $model, 'description' )->textarea( [ 'readonly' => 'true' ] ) ?> 
    	<?= $form->field( $model, 'layout' )->textInput( [ 'readonly' => 'true' ] ) ?>  
		<?= $form->field( $model, 'viewPath' )->textInput( [ 'readonly' => 'true' ] ) ?>
		<?= $form->field( $model, 'adminView' )->textInput( [ 'readonly' => 'true' ] ) ?>
		<?= $form->field( $model, 'frontendView' )->textInput( [ 'readonly' => 'true' ] ) ?>

    	<h4>Template Content</h4>
    	<?= $form->field( $model, 'content' )->textarea( [ 'class' => 'content-editor' ] )->label( false ) ?>

		<?=Html::a( "Cancel", $returnUrl, ['class' => 'btn' ] );?>
		<input type="submit" value="Delete" />

		<?php ActiveForm::end(); ?>
	</div>
</section>