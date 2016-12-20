<?php

/* @var $event \pavlinter\urlmanager\UrlManagerEvent */

/* @var $urlManager \app\components\UrlManager */
$urlManager = $event->sender;

$moduleName = $urlManager->getModuleName();

if ($moduleName == null) {
    //only frontend
}

/*if ($moduleName == 'user') {
    //only for user module
}*/