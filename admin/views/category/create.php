<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Add Category';
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Add Category</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-category-create', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'category_name' ) ?>
    	<?= $form->field( $model, 'category_desc' )->textarea() ?>
    	<?= $form->field( $model, 'category_type' )->dropDownList( $typeMap ) ?>
		<div class="box-filler"></div>
		<?=Html::a( "Cancel", [ '/core/category/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Create" />

		<?php ActiveForm::end(); ?>
	</div>
</section>

<script type="text/javascript">
	initSidebar( "sidebar-category", 1 );
</script>