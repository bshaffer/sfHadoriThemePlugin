sfHadoriThemePlugin
===================

sfHadoriThemePlugin is an admin generator with a beautiful built in theme.  The idea is to provide basic functionality desired in every administrative interface, while keeping the generated code readable and beautiful.  For that reason, Hadori does a lot, but he doesn't try to do *everything*.  When you need to break from the norm, Hadori provides you with spectacular generated code to let you hit the ground running.

Installation
------------

Before spinning up this theme, you must install the [sfThemeGeneratorPlugin](http://github.com/bshaffer/sfThemeGeneratorPlugin).

 1. Generate Theme

        $ php symfony generate:theme hadori

 1. Complete the options asked by the generator

 1. Publish your assets

        $ php symfony plugin:publish-assets

 1. Configure your assets.  jQuery is included in each module by default, but you may want to change this

        # /path/to/generated-module/config/view.yml
        all:
          stylesheets:     [/sfHadoriThemePlugin/css/theme.css, /sfHadoriThemePlugin/css/hadori.css]
          javascripts:     [/sfHadoriThemePlugin/js/jquery-1.4.2.min.js, /sfHadoriThemePlugin/js/hadori.js]        

 1. Look at the layout.php.sample file for an example layout. It lives in `data/sample/layout.php.sample`.  Using this layout for your generated modules will apply the Hadori theme.  This will also apply to your login form.
Configuration
-------------

The generator.yml allows for several options you will be familiar with, with the addition of several more.  Configuration is as follows:

  * route_prefix

      This should be the same as specified in `routing.yml` for the module.  This will ensure everything is linked up correctly.

  * display

      The `list`, `show`, `export`, `filters` actions each allow for the `display` options, an array of fields on your model to be understood in a variety of ways:
      
      * `list`: Columns to show in list table
      * `show`: Object properties to show in the definition list
      * `export`: Fields available to export
      * `filters`: Fields available to filter

  * form_class
  
      The sfForm class to use when creating and editing your object.  Defaults to the generated doctrine form class (_MyModeForm_)

  * filter_class

      The sfFormFilter class to use when filtering the list view.  Defaults to the generated doctrine form filter class (_MyModeFormFilter_)

  * filter_default

      An array of filter name-value pairs to filter your list view by default.  ex: `default: [is_active: true, type: 'client']`
      
  * export_manager_class

      The class to use when exporting your objects.  Defaults to the _sfExportManager_ class included in the admin generator.  Read more about this in the **Export** section below.

  * export_filename

      The name of the downloadable export file.  You do not need to include an extension.
  
  * list_title
  
      Text in the list view's h2 tag

  * list_pager_max_per_page
  
      Number of results to show on the list page

  * list_sort

      A sort array to sort your list view by default.  ex: `sort: [last_name, asc]`

  * list_batch_actions

      Actions available to the batch actions dropdown of the list view.  ex: `batch_actions: [delete]`

  * list_object_actions

      Actions available to each row of the list view.  ex: `object_actions: [show, edit, delete]`

  * new_title

      Text in the new action's h2 tag

  * new_actions

      Actions available to the new form.  ex: `actions: [save, back]`

  * edit_title

      Text in the edit action's h2 tag

  * edit_actions

      Actions available to the edit form.  ex: `actions: [save, back]`

  * show_title

      Text in the show action's h2 tag

  * show_actions

      Actions available to the show form.  ex: `actions: [edit, back]`

Exporting
---------

This plugins gives you the ability to export subsets of your data to a csv
file. To use this feature, simply activate is (as described below) and
then configure your fields in the same way you configure fields in the
list view.

 1. Activate the `export` mode in the `generator.yml`. Do this by removing
    the `~` after the export option:

        edit:    ~
        new:     ~
        export:

 1. Turn the export route on by setting the `with_export` option in `routing.yml`
    to true:

        my_admin_route:
          class: sfHadoriAdminRouteCollection
          options:
            # ...
            with_export:          true

Security
--------

Set the `use_security_yaml_credentials` to true (true by default) to
synchronize your module's security.yml file with the generator's credentials.
This will automatically hide actions to users without appropriate credentials.

Generated Code
--------------

View the sfThemeGeneratorPlugin `README` for more options on how to customize this theme.

Styling Login Form
------------------

Follow these steps to style your login form with the Hadori theme:

Set your application to use the _sfDoctrineGuardPlugin_ login form in your settings.yml:

    # app/MYAPP/config/settings.yml
    all:
      .settings:
        login_module: sfGuardAuth
        login_action:   login

Copy over the layout.php.sample file in _data/generator/sfDoctrineModule/hadori/template_ as your base application template.

    # cd /path/to/project
    cp plugins/sfHadoriThemePlugin/data/generator/sfDoctrineModule/hadori/template/layout.php.sample apps/MYAPP/templates/

Set your stylesheets to the plugin's stylesheets:

    # app/MYAPP/config/view.yml
    default:
      stylesheets:
        - /sfHadoriThemePlugin/css/theme.css
        - /sfHadoriThemePlugin/css/hadori.css
