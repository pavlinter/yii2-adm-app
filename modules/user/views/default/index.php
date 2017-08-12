<?php

/**
 * @var \yii\web\View $this
 * @var string $content
 */

?>

<!-- begin page-header -->
<h1 class="page-header">Blank Page <small>header small text goes here...</small></h1>
<!-- end page-header -->

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                </div>
                <h4 class="panel-title">Panel Title here</h4>
            </div>
            <div class="panel-body">
                <div class="user-default-index">
                    <h1><?= $this->context->action->uniqueId ?></h1>
                    <p>
                        This is the view content for action "<?= $this->context->action->id ?>".
                        The action belongs to the controller "<?= get_class($this->context) ?>"
                        in the "<?= $this->context->module->id ?>" module.
                    </p>
                    <p>
                        You may customize this page by editing the following file:<br>
                        <code><?= __FILE__ ?></code>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>


