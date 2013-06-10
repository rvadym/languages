x_ls
====

language switcher plugin for atk4 with yaml file support

usage:

      $this->add('x_ls/Controller_LanguageSwitcher',array(
          'languages'=>array('ua','ru','en'),
          'default_language'=>'en',
       ));

you can store translations in database or in yml files
if DB

    $this->x_ls->setModel('Translations');

and translation code is

    $this->api->_('Home');

If you want all system messages of Agile toolkit to be translated just add this code to your api class

    // translations
    public $x_ls = false;
    function _($string) {
        if (!$this->x_ls) {
            $this->add('x_ls/Controller_LanguageSwitcher',array(
                'languages'=>array('en','ru','lv','ua'),
                'default_language'=>'en',
            ));
            //$this->x_ls->setModel('Translations');
        }
        return $this->x_ls->__($string);
    }

You can also change look of switcher. Extend your own switcher View from x_ls\View_LanguageSwitcher