<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Add Gallery';
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Add Gallery</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-gallery-create', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'name' ) ?>
    	<?= $form->field( $model, 'description' )->textarea() ?>

		<div class="box-filler"></div>
		<?=Html::a( "Cancel", [ '/cmgcore/gallery/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Create" />

		<?php ActiveForm::end(); ?>
	</div>
</section>
<script type="text/javascript">
	initSidebar( "sidebar-gallery", -1 );
</script>