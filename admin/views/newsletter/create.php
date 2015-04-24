<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\widgets\Editor;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Add Newsletter';

Editor::widget( [ 'selector' => '.content-editor' ] );
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Add Newsletter</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-newsletter-create', 'options' => ['class' => 'frm-split form-with-editor' ] ] );?>

    	<?= $form->field( $model, 'name' ) ?>
    	<?= $form->field( $model, 'description' )->textarea() ?>

    	<h4>Newsletter Content</h4>
    	<?= $form->field( $model, 'content' )->textarea( [ 'class' => 'content-editor' ] ) ?>

		<?=Html::a( "Cancel", [ '/cmgcore/newsletter/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Create" />

		<?php ActiveForm::end(); ?>
	</div>
</section>

<script type="text/javascript">
	initSidebar( "sidebar-newsletter", 0 );
	initFileUploader();
</script>