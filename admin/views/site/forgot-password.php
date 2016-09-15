<?php
use yii\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Forgot Password | ' . $coreProperties->getSiteTitle();
?>
<section class="wrap-content wrap-content-full container clearfix">
    <div class="cud-box wrap-login-box frm-split">
        <h1>Forgot Password</h1>
        <?php 
            if( Yii::$app->session->hasFlash( 'message' ) ) {
        ?>
            <p><?= Yii::$app->session->getFlash( 'message' ) ?></p>
        <?php
            }
            else {
        
                $form = ActiveForm::begin( [ 'id' => 'frm-forgot-password' ] );
        ?>
                <?= $form->field( $model, 'email' )->textInput( [ 'placeholder' => 'Email*' ] ) ?>
        
                <div class="box-filler"></div>
                
                <input type="submit" value="Submit" />
        <?php
                ActiveForm::end();
            }
        ?>
    </div>
</section>