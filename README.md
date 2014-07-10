Language switcher add-on for atk4 with yaml file support.
Add-on requires ATK version 4.3 or higher.

-------------
Installation:
-------------
1. Add object to sandbox_addons.json and set such properties:
    "name":                 "rvadym/languages",
    "addon_full_path":      "../vendor/rvadym/languages",
    "addon_symlink_name":   "rvadym_languages",
    "addon_public_symlink": "public/rvadym_languages"

2. Configure add-on in config.php:
All config settings must be placed into array : $config['rvadym']['languages']  = array(). If there is no option, it would set to default values.
You may use such options:
'languages'            => array('en','ru','ua'),      // list of used languages. Default: array('en')
'default_language'     => 'en',                       // Default: the first element of array 'languages'
'switcher_type'        => 'session',                  // 'session' or 'url'. Default: 'session'
'translation_dir_path' => 'translations',             // Directory of translation files. Relative to api dir path
'store_type'           => 'file',                     // file | db. Default: 'file'
'model'    `           => 'Translation',              // if 'store_type' == db provide name of Model. Default: 'Translation'
'view_class'           => 'View_MyCustomView',        // custom view class for switcher.
'switcher_tag'         => 'language_switcher_panel',  // name of spot for template. Default: lang_switch
'to_same_page'         => true,                       // To set redirect to homepage after language changing set it to false.
'var_name'             => 'lang',                     // You can change name of $_GET variable. Same name will be used for session storage.
'debug'                => true,                       // Used to show untranslated words

-------------
Usage:
-------------

Place the line into frontend class init to add switcher :
     $this->languages->addLangSwitcher($this);

You can also change look of switcher.
Extend your own switcher View from rvadym\languages\View_LanguageSwitcher and change template and method showSwitcher() .
To use custom view for switcher you must set option in config.php or set second parameter of addLangSwitcher function (more priority).

Value of View instances (View, H1, P etc.) would be translated automatically after set.
To translate value of wrapped elements like Text use "api->_('value')" or system message like confirmation:
 $this->add('Text')->set($this->api->_('some value'));  //for text value
 $button->js('click')->univ()->confirm($this->api->_('Some process will be started. Continue?')));  //for confirmation

You can store translations in database or in yml files (use config 'store_type').
To store in database you need to follow instructions above:
- use the sql model doc/languages.sql to create necessary tables
- add every item from array languages as columns of databese table 'translation'.  //Column 'en' is already set to table in script.
It would translate value of column 'value' to the value of chosen language column (like 'en').

To customize model, extend it from Model_Translation.