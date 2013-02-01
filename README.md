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

    __('Home')
