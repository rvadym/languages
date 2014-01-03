<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 11/25/13
 * Time: 7:17 PM
 * To change this template use File | Settings | File Templates.
 */

/* ***********************************************************
 *   For stand alone (non afiletoolkit-bandle) compatibility
 */

namespace rvadym\languages;
class InitiatorStandAlone extends Initiator {
    public $public_location;
    protected function addLocations() {

        if (!$this->public_location) {
            $this->public_location = $this->api->pathfinder->base_location->base_url;
        }

        // Private location contains templates and php files YOU develop yourself
        /*$this->private_location = */
        $this->api->pathfinder->addLocation(array(
            'docs'      => 'docs',
            'php'       => 'lib',
            'page'      => 'page',
            'template'  => 'templates',
        ))
                ->setBasePath(dirname(dirname(__FILE__)))
        ;

        //$addon_public = $addon->get('addon_symlink_name');
        // this public location cotains YOUR js, css and images, but not templates
        /*$this->public_location = */
        $this->api->pathfinder->addLocation(array(
            'js'     => 'js',
            'css'    => 'css',
            'public' => './',
            //'public'=>'.',  // use with < ?public? > tag in your template
        ))
                //->setBasePath($base_path.$this->app_base_path.'/'.$addon->get('addon_public_symlink'))
                ->setBasePath(dirname(dirname(__FILE__)))
                ->setBaseURL($this->api->url('/').$this->public_location) // $this->api->pm->base_path
        ;

    }

    private $dummy_addon_obj_name = 'rvadym_languages';
    protected function getAddonName() {
        return $this->dummy_addon_obj_name;
    }
}