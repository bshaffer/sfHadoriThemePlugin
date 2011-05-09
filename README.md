sfHadoriThemePlugin
===================

sfHadoriThemePlugin is an admin generator with a beautiful-built in theme.  Hadori provides basic functionality for an administrative interface, while keeping the generated code readable and beautiful.  For that reason, Hadori does a lot, but she doesn't try to do *everything*.  When you need to break from the norm, Hadori provides you with spectacular generated code to let you hit the ground running.

Installation
------------

### With git

    git submodule add git://github.com/bshaffer/sfHadoriThemePlugin.git plugins/sfHadoriThemePlugin
    git submodule init
    git submodule update

### With subversion

    svn propedit svn:externals plugins

In the editor that's displayed, add the following entry and then save

    sfHadoriThemePlugin https://svn.github.com/bshaffer/sfHadoriThemePlugin.git

Finally, update:

    svn update

### Setup

 1. Before doing anything, you must install the [sfThemeGeneratorPlugin](http://github.com/bshaffer/sfThemeGeneratorPlugin).  To do this, follow the Installation steps in the [README](https://github.com/bshaffer/sfThemeGeneratorPlugin/blob/master/README.md).

 1. In your `config/ProjectConfiguration.class.php` file, make sure you have the plugin enabled.

    $this->enablePlugins('sfHadoriThemePlugin');

 1. run the "publish assets" task to to symlink your web directory to the web assets of your plugins.

        $ php symfony plugin:publish-assets

### Generate the Theme

 1. Run the "theme generate" task for the hadori theme

        $ php symfony theme:generate hadori

 1. Complete the options asked by the generator
 
        Application to generate theme:
        $ frontend
        Model for this theme:
        $ sfGuardUser
        Module for this theme [sf\_guard\_user]: 
        $ (enter)

 1. (optional) Configure your assets.  jQuery is included in each module by default, but you may want to change this

        # /path/to/generated-module/config/view.yml
        all:
          stylesheets:     [/sfHadoriThemePlugin/css/theme.css, /sfHadoriThemePlugin/css/hadori.css]
          javascripts:     [/sfHadoriThemePlugin/js/jquery-1.4.2.min.js, /sfHadoriThemePlugin/js/hadori.js]

 1. (optional) Look at the layout.php.sample file for an example layout. It lives in `data/sample/layout.php.sample`.  Using this layout for your generated modules will apply the Hadori theme.  This will also apply to your login form.

Configuration
-------------

The generator.yml allows for several options you will be familiar with, with the addition of many new ones.  Configuration is as follows:


  * **General Options**

    These are options directly underneath `param` in the generator.yml configuration file

    * *i18n*

        Whether or not to wrap interface strings in the i18n `__()` function.  Defaults to `false`.

    * *sortable*

        If your model has the csDoctrineActAsSortable behavior, set this to true to enable "promote" and "demote" actions in the list view.  Defaults to `false`.

    * *use\_security\_yaml\_credentials*

        Any actions declared in `security.yml` will only be available to the logged-in user if they possess that credential

    * *class_label*

        The human-readable label for your class.  Defaults to the `model_class` parameter.

    * *route_prefix*

        This should be the same as specified in `routing.yml` for the module.  This will ensure everything is linked up correctly.

    * *actions\_base\_class*

        This class will be extended by the action class generated in cache.  Defaults to `sfActions`.

  * **Form Options**

    These are options underneath `form` in the generator.yml configuration file [param > config > form]

    * *class*

        The sfForm class to use when creating and editing your object.  Defaults to the generated doctrine form class (_MyModeForm_)

  * **Filter Options**

    These are options underneath `filter` in the generator.yml configuration file [param > config > filter]

    * *class*

        The sfFormFilter class to use when filtering the list view.  Defaults to the generated doctrine form filter class (_MyModeFormFilter_)

    * *default*

        An array of filter name-value pairs to filter your list view by default.  ex: `default: [is_active: true, type: 'client']`

  * **List Options**

    These are options underneath `list` in the generator.yml configuration file [param > config > list]

    * *title*

        Text in the list view's h2 tag

    * *display*

        Columns to show in the list view table.  Defaults to the first five columns in the object's table.  See _Fields Options_ below.

    * *actions*

        Actions to display at the bottom of the list view table.  Defaults to `[new, export]`.  See _Action Options_ below.  ex: `actions: [save, back]`

    * *batch_actions*

        Actions to display in the batch actions dropdown of the list view.  Defaults to `[delete]`.  These actions apply to all checked items in the list table.  ex: `batch_actions: [delete]`

    * *object_actions*

        Actions available to each row of the list view table.  Defaults to `[show, edit, delete]`.  See _Action Options_ below.  ex: `object_actions: [show, edit, delete]`

    * *pager\_max\_per\_page*

        Number of results to show in each list view table page.  Defaults to `10`.

    * *sort*

        A sort array to sort your list view by default.  ex: `sort: [last_name, asc]`

  * **New Options**

    These are options underneath `new` in the generator.yml configuration file [param > config > new]

    * *title*

        Text in the new action's h2 tag

    * *actions*

        Actions available to the new form.  Defaults to `[save, save_and_add, cancel]`.  See _Action Options_ below.  ex: `actions: [save, back]`

  * **Edit Options**

    These are options underneath `edit` in the generator.yml configuration file [param > config > edit]

    * *title*

        Text in the edit action's h2 tag

    * *actions*

        Actions available to the edit form.  Defaults to `[save, delete, cancel]`.  See _Action Options_ below.  ex: `actions: [save, back]`

  * **Show Options**

    These are options underneath `show` in the generator.yml configuration file [param > config > show]

    * *title*

        Text in the show action's h2 tag

    * *display*

        Object properties to show in the definition list.  Defaults to all columns.  See _Fields Options_ below.

    * *actions*

        Actions available to the show form.  Defaults to `[edit, cancel]`.  See _Action Options_ below.  ex: `actions: [edit, back]`

  * **Export Options**

    These are options underneath `export` in the generator.yml configuration file [param > config > export]

    * *title*

        Text to render in the h2 tag

    * *display*

        Fields available for export.  See _Fields Options_ below.

    * *help*

        Text to display above the export preview table.  ex: `help: The table below represents the data that will be exported`

    * *manager_class*

        The class to use when exporting your objects.  Defaults to the _sfExportManager_ class included in the admin generator.  Read more about this in the **Export** section below.

    * *filename*

        The name of the downloadable export file.  You do not need to include an extension.

  * **Action Options**

    It is possible to configure actions under `actions` in the generator.yml configuration file  [param > config > actions], which will be configured for all actions of that name.  These can also be configured under each context individually. The following options are available to each action.

    * *label*

        The text displayed for the link.  ex:  `edit: { label: 'Edit %%class_label%%' }`

    * *route*

        Route name for the action's URL.  It is recommended to use without the '@'.  ex:  `approve: { route: comment_approve }`

    * *object_link*

        Set to true if this action corresponds to an sfDoctrineRoute of type `object`.  This will use an object instance as the `sf_subject` when generating the route.  ex:  `approve: { route: comment_approve, object_link: true }`

    * *action*

        The `route` option is recommended, but this option can be used when a specific route does not exist, but the action does.  ex: `promote: { action: promote }`

    * *credentials*

        The credentials required to see this action.  This follows the same syntax as security.yml.  ex:  `delete: { credentials: [[Assistant, Administrator]] }`

    * *method*

        The HTTP method to use when this action is clicked.  Default is `get`.  ex:  `delete: { method: delete }`

    * *confirm*

        Prompts the user with a message before executing the action.  ex:  `delete: { confirm: Are you sure? }`

    * *HTML Attributes*

        All other options are passed through to the `link_to` function, allowing you to set HTML variables here.  ex:  `delete: { id: delete-action, class: delete }`

  * **Field Options**

    It is possible to configure actions under `fields` in the generator.yml configuration file  [param > config > fields], which will be configured for all fields of that name.  These can also be configured under the `list` and `show` context individually. The following options are available to each fields.

    * *label*

        This text will be displayed in the header for the list view table and in the definition term tag in the show table.

    * *credentials*

        The credentials required to view this field.  This follows the same syntax as security.yml.  ex:  `num_registrations: { credentials: [Planner, Administrator] }`

    * *date_format*

        If the field value is a valid date string, format it according to [PHP Date Format Characters](http://php.net/manual/en/function.date.php)

    * *type*

        Can be `Text`, `Date` or `Boolean`. Typically set by the database column type, but can be set manually to display fields in a custom way.  

          * Type `Boolean` displays the value as a green check or a red X.
          * Type `Date` formats the value based on the `date_format` parameter, **Y-m-d** by default
          * Type `Text` displays the string value with no modification
          * *to do* - Type `Object` links to the `show` action for the object if a theme exists for it

Customizing the List View
-------------------------

Where before, you may have specified the `getTableMethod` parameter, now you override the `getBaseQuery()` function in your actions.class.php.  By default,
this returns a `Doctrine_Query` object from your model's table by calling the `createQuery()` function.  Override this to add where clauses and other
customizations.  Just be sure that method returns a `Doctrine_Query` instance, and you'll be all set!

Forms and Filters
-----------------

If you are familiar with the built-in symfony admin generator, you may be asking yourself *"How do I configure my form and filter fields?"*.  The answer is simple:
Use the form framework provided with symfony.  There is no longer a disconnect between your form fields and the fields in your admin generator!  Rejoice!  Configuring
your forms like so:

    // lib/form/doctrine/MyModelForm.class.php
    class MyModelForm extends BaseFormDoctrine
    {
      public function configure()
      {
        $this->useFields(array('title', 'body', 'description'));
      }
    }

Hadori knows to only use these fields.  But don't forget to run `$ php symfony cache:clear`, otherwise you'll receive a 500 error when you view your
form.  This is the same for filters:

    // lib/form/doctrine/MyModelForm.class.php
    class MyModelFormFilter extends BaseFormFilterDoctrine
    {
      public function configure()
      {
        $this->useFields(array('title', 'body', 'description', 'created_at', 'updated_at'));
      }
    }

Only the fields used in your filter form will be available as filters.  Remarkable!

Tokens and Smart Linking
------------------------

**Tokens**: Hadori Tokens work much the same way as they do in the built in admin generator.  All tokens are wrapped in double-percents (_%%_).  Any
value that does not match a configuration parameter is thought to be a getter on an object.

  * Configuration Parameters:  Anything in generator.yml

      `edit: { title: Edit %%class_label%% }`

  * to_string: a special token for getting at the object's `__toString()` method

      `show: { title: Showing %%to_string%% }`

  * getters:  anything not matching the previous two tokens is assumed to be an object getter

      `delete: { confirm: Are you sure you want to delete '%%full_name%%'? }`  - will call $object->getFullName()

**Smart Linking**: Using relationship aliases in Hadori will automatically link to the `show` page for those objects as long as a route exists following the 
convention _table\_name\_show_.  This means any related objects with a Hadori module will be linked automatically.  Here is an example configuration:

    list:
      display:  [title, Author, created_at]

The above example will automatically the *Author* field to the `show` action for that object (`@author_show`).

Exporting
---------

Hadori gives you the ability to export subsets of your data to a csv
file. This feature is activated by default.  You can configure your fields
in the same way you configure fields in the list view: using the `display` configuration.

**Custom Exporting**:  Adding custom columns in the data export can be done two ways.  The first
is the simplest:  Add a getter for that column on your model, and include this in the `display` configuration.

      // MyModel.class.php
      class MyModel extends BaseMyModel
      {
        //...
        function getTagNames()
        {
          return implode(',', $this->getTags()->toKeyValueArray('id', 'name'));
        }
      }

      // generator.yml
      export:
        display: [title, created_at, tag_names]

The second method is to extend the `sfExportManager` class and add magic methods to it.  This is great if you have a lot of logic required for your
export and you'd like to keep that logic out of your model class.

      // MyModelExportManager.class.php
      class MyModelExportManager extends sfExportManager
      {
        public function exportField($object, $field)
        {
          if($field == 'tag_names')
          {
            return implode(',', $this->getTags()->toKeyValueArray('id', 'name'));
          }
          
          return parent::exportField($object, $field);
        }
      }
      
      // generator.yml
      export:
        display:        [title, created_at, tag_names]
        manager_class:  MyModelExportManager

To disable exporting, follow the steps below.

 1. Disable the `export` mode in the `generator.yml`. Do this by setting export to `false`

        edit:    ~
        new:     ~
        export:  false

 1. Turn off the export route by setting the `with_export` option in `routing.yml`
    to `false`:

        my_admin_route:
          class: sfHadoriRouteCollection
          options:
            # ...
            with_export:          false

Security
--------

Set the `use_security_yaml_credentials` to true (true by default) to
synchronize your module's security.yml file with the generator's credentials.
This will automatically hide actions to users without appropriate credentials.

Styling Login Form
------------------

This part will only work if you are using `sfDoctrineGuardPlugin`.  Follow these steps to style your login form with the Hadori theme:

Set your application to use the _sfDoctrineGuardPlugin_ login form in your settings.yml:

    # app/YOUR-APP/config/settings.yml
    all:
      .settings:
        login_module: sfGuardAuth
        login_action: login

Copy over the layout.php.sample file in _data/sample_ as your base application template.

    # cd /path/to/project
    cp plugins/sfHadoriThemePlugin/data/sample/layout.php.sample apps/YOUR-APP/templates/layout.php

Set your application's stylesheets to the plugin's stylesheets:

    # app/YOUR-APP/config/view.yml
    default:
      stylesheets:
        - /sfHadoriThemePlugin/css/theme.css
        - /sfHadoriThemePlugin/css/hadori.css

Set your application's stylesheets to the plugin's stylesheets:

    # app/YOUR-APP/config/view.yml
    default:
      stylesheets:
        - /sfHadoriThemePlugin/css/theme.css
        - /sfHadoriThemePlugin/css/hadori.css

This will already get you a decent looking login form, but if you want to go the extra mile, copy over the \_signin\_form.php.sample file into your application.

    # cd /path/to/project
    mkdir -p apps/YOUR-APP/modules/sfGuardAuth/templates
    cp plugins/sfHadoriThemePlugin/data/sample/_signin_form.php.sample apps/YOUR-APP/modules/sfGuardAuth/templates/_signin_form.php

Generated Code
--------------

View the [sfThemeGeneratorPlugin](http://github.com/bshaffer/sfHadoriThemePlugin) `README` for more options on how to customize this theme.

Upgrading from the original Symfony Admin Generator
---------------------------------------------------

Upgrading an existing module to Hadori is easy.  Follow this guide to transform your existing module into a Hadori theme module:

1. Change your route collection class in `routing.yml` from sfDoctrineRouteCollection to sfHadoriRouteCollection

2. Change the `theme` parameter in `generator.yml` from **admin** to **hadori**

1. Remove the `lib` directory in your admin module

    The my\_moduleGeneratorConfiguration and my\_modelGeneratorHelper classes are not needed in Hadori.  If you've customized these
    classes, this logic will need to be migrated to (most likely) the action, depending on the 
        
2. Add Hadori Assets
  
    1. If you don't have a `_flashes.php` partial in your global templates directory (`apps/myapp/templates/_flashes.php`), copy it over
        
        `cp plugins/sfHadoriThemePlugin/data/generator/sfDoctrineModule/hadori/templates/_flashes.php apps/myapp/modules/my_module/templates/`
        
    2. If you haven't added the stylesheets and javascripts to your global `view.yml`, create a new one or copy the existing one

        `cp plugins/sfHadoriThemePlugin/data/generator/sfDoctrineModule/hadori/skeleton/config/view.yml apps/myapp/modules/my_module/config/`

3. A whole slew of partials are deprecated in Hadori.  If you have custom logic in any of the partials below, it will need to be moved.

    - \_assets.php
    - \_filters\_field.php
    - \_flashes.php
    - \_form\_actions.php
    - \_form\_field.php
    - \_form\_fieldset.php
    - \_form\_footer.php
    - \_form\_header.php
    - \_list\_actions.php
    - \_list\_batch\_actions.php
    - \_list\_field\_boolean.php
    - \_list\_footer.php
    - \_list\_header.php -> this is not the same as the new \_list\_header.php
    - \_list\_td\_actions.php
    - \_list\_td\_batch\_actions.php
    - \_list\_td\_stacked.php
    - \_list\_td\_tabular.php
    - \_list\_th\_stacked.php
    - \_list\_th\_tabular.php -> this is now \_list\_header.php

4. Some configuration has been deprecated in Hadori.  If you have any of the configuration below, it will need to be moved:

    - form: display (*use the form class*)
    - filter: display (*use the filter form class*)
    - list: table\_method (*use getBaseQuery*), table\_count\_method (use *getBaseQuery*), params, layout, pager\_class

Credits
-------

Thanks to [Travis Roberts](http://github.com/travis) for the hadori stylesheets.