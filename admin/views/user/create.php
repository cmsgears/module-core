<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Create User";

// Sidebar and Return URL
$sidebar						= $this->context->sidebar;
$returnUrl						= $this->context->returnUrl;
$this->params['sidebar-parent'] = $sidebar[ 'parent' ];
$this->params['sidebar-child'] 	= $sidebar[ 'child' ];
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Create User</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-user-create', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'email' ) ?>
    	<?= $form->field( $model, 'username' ) ?>
		<?= $form->field( $model, 'firstName' ) ?>
		<?= $form->field( $model, 'lastName' ) ?>

    	<?php if( isset( $roleMap ) ) { ?>
			<?= $form->field( $siteMember, 'roleId' )->dropDownList( $roleMap )  ?>
		<?php } else { ?>
			<?= $form->field( $siteMember, 'roleId' )->hiddenInput()->label( false )  ?>
		<?php } ?>

		<?=Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn' ] );?>
		<input type="submit" value="Create" />

		<?php ActiveForm::end(); ?>
	</div>	
</section>