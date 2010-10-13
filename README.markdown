sfSlimAdminGeneratorPlugin
============================

Before spinning up this theme, you must install the [sfThemeGeneratorPlugin](http://github.com/bshaffer/sfThemeGeneratorPlugin)

1) Generate Theme

    $ php symfony generate:theme slim
    
2) Complete the options asked by the generator

3) Publish your assets

    $ php symfony plugin:publish-assets
    
The generator.yml allows for several options you will be familiar with, with the addition of several more.

Exporting
---------

Turn on the "export" context and configure your fields in the same way you configure fields in the list view

Security
--------

Set the "use_security_yaml_credentials" to true (true by default) to synchronize your module's security.yml file
with the generator's credentials.  This will automatically hide actions to users without appropriate credentials.

Generated Code
--------------

View the sfThemeGeneratorPlugin README for more options