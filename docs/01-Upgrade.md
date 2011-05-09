Upgrading from the Original Symfony Admin Generator
---------------------------------------------------

Upgrading an existing module to Hadori is easy.  Follow this guide to transform your existing module into a Hadori theme module:

1. Change your route collection class in `routing.yml` from **sfDoctrineRouteCollection** to **sfHadoriRouteCollection**

2. Change the `theme` parameter in `generator.yml` from **admin** to **hadori**

3. Remove the `lib` directory in your admin module

    The `my\_moduleGeneratorConfiguration` and `my\_modelGeneratorHelper` classes are not needed in Hadori.  If you've customized these
    classes, this logic will need to be migrated to (most likely) the action.
        
4. Add Hadori Assets
  
    If you don't have a `_flashes.php` partial in your global templates directory (`apps/myapp/templates/_flashes.php`), copy it over

        cp plugins/sfHadoriThemePlugin/data/generator/sfDoctrineModule/hadori/templates/_flashes.php apps/myapp/modules/my_module/templates/

    If you haven't added the stylesheets and javascripts to your global `view.yml`, create a new one or copy the existing one

        cp plugins/sfHadoriThemePlugin/data/generator/sfDoctrineModule/hadori/skeleton/config/view.yml apps/myapp/modules/my_module/config/

5. A whole slew of partials are deprecated in Hadori.  If you have custom logic in any of the partials below, it will need to be moved.

    - *\_assets.php*
    - *\_filters\_field.php*
    - *\_flashes.php*
    - *\_form\_actions.php*
    - *\_form\_field.php*
    - *\_form\_fieldset.php*
    - *\_form\_footer.php*
    - *\_form\_header.php*
    - *\_list\_actions.php*
    - *\_list\_batch\_actions.php*
    - *\_list\_field\_boolean.php*
    - *\_list\_footer.php*
    - *\_list\_header.php* -> this is not the same as the new \_list\_header.php
    - *\_list\_td\_actions.php*
    - *\_list\_td\_batch\_actions.php*
    - *\_list\_td\_stacked.php*
    - *\_list\_td\_tabular.php*
    - *\_list\_th\_stacked.php*
    - *\_list\_th\_tabular.php* -> this is now \_list\_header.php

6. Some configuration has been deprecated in Hadori.  If you have any of the configuration below, it will need to be moved:

    - *form*: display (*use the form class*)
    - *filter*: display (*use the filter form class*)
    - *list*: table\_method (*use `getBaseQuery`*), table\_count\_method (use *`getBaseQuery`*), params, layout, pager\_class
