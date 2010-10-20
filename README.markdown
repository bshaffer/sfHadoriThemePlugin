sfHadoriThemePlugin
===================

Before spinning up this theme, you must install the
[sfThemeGeneratorPlugin](http://github.com/bshaffer/sfThemeGeneratorPlugin).

 1. Generate Theme

    $ php symfony generate:theme hadori

 1. Complete the options asked by the generator

 1. Publish your assets

    $ php symfony plugin:publish-assets

The generator.yml allows for several options you will be familiar with,
with the addition of several more.

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

Set the "use_security_yaml_credentials" to true (true by default) to
synchronize your module's security.yml file with the generator's credentials.
This will automatically hide actions to users without appropriate credentials.

Generated Code
--------------

View the sfThemeGeneratorPlugin README for more options.