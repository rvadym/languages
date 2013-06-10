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

If you want all system messages of Agile toolkit to be translated just add this code to your api class.

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

You can also change look of switcher.
Extend your own switcher View from x_ls\View_LanguageSwitcher and change template and method showSwitcher() .

To set redirect to homepage after language changing set parameter to_same_page to false.

	$this->add('x_ls/Controller_LanguageSwitcher',array(
		'to_same_page'=>false,   //   <------
		'languages'=>array('en','ru','lv','ua'),
		'default_language'=>'en',
	));

You can change name of $_GET variable. Same name will be used for session storage.

	$this->add('x_ls/Controller_LanguageSwitcher',array(
		'var_name'=>'change_language_to',   //   <------
		'languages'=>array('en','ru','lv','ua'),
		'default_language'=>'en',
	));

Add $config['x_ls']['debug']=true; to see alll not translated words.