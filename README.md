language switcher plugin for atk4 with yaml file support

usage:

      $this->add('rvadym/language_switcher/Controller_LanguageSwitcher',array(
          'languages'=>array('ua','ru','en'),
          'default_language'=>'en',
       ));

you can store translations in database or in yml files
if DB

    $this->language_switcher->setModel('Translations');

and translation code is

    $this->api->_('Home');

If you want all system messages of Agile toolkit to be translated just add this code to your api class.

    // translations
    public $language_switcher = false;
    function _($string) {
        if (!$this->language_switcher) {
            $this->add('rvadym/language_switcher/Controller_LanguageSwitcher',array(
                'languages'=>array('en','ru','lv','ua'),
                'default_language'=>'en',
            ));
            //$this->language_switcher->setModel('Translations');
        }
        return $this->language_switcher->__($string);
    }

You can also change look of switcher.
Extend your own switcher View from rvadym\language_switcher\View_LanguageSwitcher and change template and method showSwitcher() .

To set redirect to homepage after language changing set parameter to_same_page to false.

	$this->add('rvadym/language_switcher/Controller_LanguageSwitcher',array(
		'to_same_page'=>false,   //   <------
		'languages'=>array('en','ru','lv','ua'),
		'default_language'=>'en',
	));

You can change name of $_GET variable. Same name will be used for session storage.

	$this->add('rvadym/language_switcher/Controller_LanguageSwitcher',array(
		'var_name'=>'change_language_to',   //   <------
		'languages'=>array('en','ru','lv','ua'),
		'default_language'=>'en',
	));

Add $config['rvadym']['language_switcher']['debug']=true; to see all not translated words.