<?php
use yii\helpers\Html;

$this->title = $name;

// Sidebar
$this->params['sidebar-parent'] = 'sidebar-error';
$this->params['sidebar-child'] 	= 'error';
?>
<div class="site-error">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>
</div>