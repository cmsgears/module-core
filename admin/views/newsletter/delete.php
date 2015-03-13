<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use cmsgears\widgets\other\Editor;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Delete Newsletter';

Editor::widget( [ 'selector' => '.content-editor' ] );
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Delete Newsletter</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-newsletter-delete', 'options' => ['class' => 'frm-split form-with-editor' ] ] );?>

    	<?= $form->field( $model, 'newsletter_name' )->textInput( [ 'disabled'=>'true' ] ) ?>
    	<?= $form->field( $model, 'newsletter_desc' )->textarea( [ 'disabled'=>'true' ] ) ?>
 
    	<h4>Newsletter Content</h4>
    	<?= $form->field( $model, 'newsletter_content' )->textarea( [ 'disabled'=>'true', 'class' => 'content-editor' ] ) ?>

		<div class="box-filler"></div>

		<?=Html::a( "Cancel", [ '/cmgcore/newsletter/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Delete" />

		<?php ActiveForm::end(); ?>
	</div>
</section>

<script type="text/javascript">
	initSidebar( "sidebar-newsletter", -1 );
</script>