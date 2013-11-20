<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 11/20/13
 * Time: 10:09 AM
 * To change this template use File | Settings | File Templates.
 */
namespace rvadym\language_switcher;
class Settings {
    public static function postUpdate(Event $event) {
        $composer = $event->getComposer();
        // do stuff
    }

    public static function postPackageInstall(Event $event) {
        $installedPackage = $event->getOperation()->getPackage();
        // do stuff
        var_dump($installedPackage);
    }

    public static function warmCache(Event $event) {
        // make cache toasty
    }
}