<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use cmsgears\widgets\other\Editor;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Update Newsletter';

Editor::widget( [ 'selector' => '.content-editor' ] );
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Update Newsletter</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-newsletter-create', 'options' => ['class' => 'frm-split form-with-editor' ] ] );?>

    	<?= $form->field( $model, 'newsletter_name' ) ?>
    	<?= $form->field( $model, 'newsletter_desc' )->textarea() ?>
 
    	<h4>Newsletter Content</h4>
    	<?= $form->field( $model, 'newsletter_content' )->textarea( [ 'class' => 'content-editor' ] ) ?>

		<div class="box-filler"></div>

		<?=Html::a( "Back", [ '/cmgcore/newsletter/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Update" />

		<?php ActiveForm::end(); ?>
	</div>
</section>

<script type="text/javascript">
	initSidebar( "sidebar-newsletter", -1 );
</script>