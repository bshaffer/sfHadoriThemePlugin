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

The generator.yml allows for several options you will be familiar with, with the addition of many new ones.  Configuration is as follows:


  * **General Options**

    These are options directly underneath `param` in the generator.yml configuration file

    * sortable [false]

        If your model has the csDoctrineActAsSortable behavior, set this to true to enable "promote" and "demote" actions in the list view

    * use\_security\_yaml\_credentials [true]

        Any actions declared in `security.yml` will only be available to the logged-in user if they possess that credential

    * route_prefix

        This should be the same as specified in `routing.yml` for the module.  This will ensure everything is linked up correctly.

    * actions\_base\_class [sfActions]

        This class will be extended by the action class generated in cache

  * Form Options

    These are options underneath `form` in the generator.yml configuration file [param > config > form]

    * class

        The sfForm class to use when creating and editing your object.  Defaults to the generated doctrine form class (_MyModeForm_)

  * **Filter Options**

    These are options underneath `filter` in the generator.yml configuration file [param > config > filter]

    * class

        The sfFormFilter class to use when filtering the list view.  Defaults to the generated doctrine form filter class (_MyModeFormFilter_)

    * default

        An array of filter name-value pairs to filter your list view by default.  ex: `default: [is_active: true, type: 'client']`

  * **List Options**

    These are options underneath `list` in the generator.yml configuration file [param > config > list]

    * title

        Text in the list view's h2 tag

    * display [true]

        Columns to show in the list view table.  Defaults to the first five columns in the object's table.  See _Fields Options_ below.

    * actions

        Actions to display at the bottom of the list view table.  See _Action Options_ below.  ex: `actions: [save, back]`

    * batch_actions

        Actions to display in the batch actions dropdown of the list view.  These actions apply to all checked items in the list table.  ex: `batch_actions: [delete]`

    * object_actions

        Actions available to each row of the list view table.  See _Action Options_ below.  ex: `object_actions: [show, edit, delete]`

    * pager\_max\_per\_page

        Number of results to show in each list view table page

    * sort

        A sort array to sort your list view by default.  ex: `sort: [last_name, asc]`


  * **New Options**

    These are options underneath `new` in the generator.yml configuration file [param > config > new]

    * title

        Text in the new action's h2 tag

    * actions

        Actions available to the new form.  See _Action Options_ below.  ex: `actions: [save, back]`

  * **Edit Options**

    These are options underneath `edit` in the generator.yml configuration file [param > config > edit]

    * title

        Text in the edit action's h2 tag

    * actions

        Actions available to the edit form.  See _Action Options_ below.  ex: `actions: [save, back]`

  * **Show Options**

    These are options underneath `show` in the generator.yml configuration file [param > config > show]

    * title

        Text in the show action's h2 tag

    * display [true]

        Object properties to show in the definition list.  Defaults to all columns.  See _Fields Options_ below.

    * actions [edit, cancel]

        Actions available to the show form.  See _Action Options_ below.  ex: `actions: [edit, back]`

  * **Export Options**

    These are options underneath `export` in the generator.yml configuration file [param > config > export]

    * title

        Text to render in the h2 tag

    * display:

        Fields available for export.  See _Fields Options_ below.

    * help:

        Text to display above the export preview table.  ex: `help: The table below represents the data that will be exported`

    * manager_class

        The class to use when exporting your objects.  Defaults to the _sfExportManager_ class included in the admin generator.  Read more about this in the **Export** section below.

    * filename

        The name of the downloadable export file.  You do not need to include an extension.

  * **Action Options**

    It is possible to configure actions under `actions` in the generator.yml configuration file  [param > config > actions], which will be configured for all actions of that name.  These can also be configured under each context individually. The following options are available to each action.

    * label

        The text displayed for the link.  ex:  `edit: { label: 'Edit %%class_label%%' }`

    * route

        Route name for the action's URL.  It is recommended to use without the '@'.  ex:  `approve: { route: comment_approve }`

    * object_link

        Set to true if this action corresponds to an sfDoctrineRoute of type `object`.  This will use an object instance as the `sf_subject` when generating the route.  ex:  `approve: { route: comment_approve, object_link: true }`

    * action

        The `route` option is recommended, but this option can be used when a specific route does not exist, but the action does.  ex: `promote: { action: promote }`

    * credentials

        The credentials required to see this action.  This follows the same syntax as security.yml.  ex:  `delete: { credentials: [[Assistant, Administrator]] }`

    * method

        The HTTP method to use when this action is clicked.  Default is `get`.  ex:  `delete: { method: delete }`

    * confirm

        Prompts the user with a message before executing the action.  ex:  `delete: { confirm: Are you sure? }`

    * HTML Attributes

        All other options are passed through to the `link_to` function, allowing you to set HTML variables here.  ex:  `delete: { id: delete-action, class: delete }`

  * **Field Options**

    It is possible to configure actions under `fields` in the generator.yml configuration file  [param > config > fields], which will be configured for all fields of that name.  These can also be configured under the `list` and `show` context individually. The following options are available to each fields.

    * label

        This text will be displayed in the header for the list view table and in the definition term tag in the show table.

    * credentials

        The credentials required to view this field.  This follows the same syntax as security.yml.  ex:  `num_registrations: { credentials: [Planner, Administrator] }`

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

        my\_admin\_route:
          class: sfHadoriAdminRouteCollection
          options:
            # ...
            with_export:          true

Security
--------

Set the `use_security_yaml_credentials` to true (true by default) to
synchronize your module's security.yml file with the generator's credentials.
This will automatically hide actions to users without appropriate credentials.

Styling Login Form
------------------

Follow these steps to style your login form with the Hadori theme:

Set your application to use the _sfDoctrineGuardPlugin_ login form in your settings.yml:

    # app/MYAPP/config/settings.yml
    all:
      .settings:
        login_module: sfGuardAuth
        login_action:   login

Copy over the layout.php.sample file in _data/sample_ as your base application template.

    # cd /path/to/project
    cp plugins/sfHadoriThemePlugin/data/sample/layout.php.sample apps/MYAPP/templates/

Set your stylesheets to the plugin's stylesheets:

    # app/MYAPP/config/view.yml
    default:
      stylesheets:
        - /sfHadoriThemePlugin/css/theme.css
        - /sfHadoriThemePlugin/css/hadori.css

Generated Code
--------------

View the sfThemeGeneratorPlugin `README` for more options on how to customize this theme.
