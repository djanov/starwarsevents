This is my first Symfony project
=================================

Notes:
------
  * Show every route in the app: **php app/console router:debug**
  * Run the server: **php app/console server:run**
  * Show(access) every services in the app: **php app/console container:debug**
  * Using doctrine: **php app/console doctrine:generate:entity**
  * query from console: **php app/console doctrine:query:sql "SELECT * from yoda_event"**
  * when updating the entity the doctrine need to be updated: **php app/console doctrine:schema:update --force**
  * to  use annotations in [doctrine docs][15] prefix them with **@ORM** before putting them in the symfony example @Column becomes **@ORM\Column** in somfony. That is because of the use statement in the Entity/Event.php **use Doctrine\ORM\Mapping as ORM;**
  * to use app in prod option not in app.dev first need to clear the cashe for prod: **php app/console cache:clear --env=prod**
  * code generation(CRUD): **php app/console doctrine:generate:crud**
  * doctrine fixtures if working check: **php app/console doctrine:fixtures:load --help** if the code runs we are ready to import some dummy data. after that use the command again without the --help this command is first purge all the data in the database and put the "new" data what we created.
  * [for shortcuts][16]. This bundle provides a way to configure your controllers with annotations.

Useful information:
-------------------

  * - The template name is always have 3 parts: **EventBundle:Default:index.html.twig**
  * 1) A Bundle name
  * 2) A subdirectory
  * 3) And a template file name

  * - annotations (read phrase by doctrine) is: **PHP comments that's read like configuration**


PROBLEMS WITH ENTITY AND DOCTRINE AND ALL:
==========================================
 * clear cashe!!!!!! **php app/console cache:clear**
 * genrate entetites(tables in database, make sure database is created and configurated in the app/config parameters.yml): **php app/console doctrine:generate:entities**
 * if there is an error or need to update some field then: **php app/console doctrine:schema:update --force**
 * DONE

 Understanding composer update and composer install:
 ==================================================

 composer update, install and composer.lock:
 ------------------------------------------
 While we wait, let’s look at a small mystery. We know that Composer reads information from composer.json. So what’s the purpose of the composer.lock file that’s at the root of our project and how did it get there?

Composer actually has 2 different commands for downloading vendor stuff.

composer update
---------------
The first is update. It says “read the composer.json file and update everything to the latest versions specified in there”. So if today we have Symfony 2.4.1 but 2.5.0 gets released, a Composer update would upgrade us to the new version. That’s because our Symfony version constraint of ~2.4 allows for anything greater than 2.4, but less than 3.0.

Hold up. That could be a big issue. What happens if you deploy right as Symfony 2.5.0 comes out? Will your production server get that version, even though you were testing on 2.4.1? That would be lame.

Because Composer is not lame, each time the composer.phar update command is run, it writes a composer.lock file. This records the exact versions of all of your vendors at that moment.

composer install
----------------
And that’s where the second command - install - comes in. It ignores the composer.json file and reads entirely from the composer.lock file, assuming one exists. So as long as you run install on your deploy, you’ll get the exact versions you expected.

So unless you’re adding a new library or intentionally upgrading something, always use composer.phar install.

And when you do need to add or update something, you can be more precise by calling composer.phar update and passing it the name of the library you’re updating like we did. With this, Composer will only update that library, instead of everything.

app/resources/base.html.twig
Add later:
<!-- {% block stylesheets %} // NEED TO INSTALL ASSETIC BUNDLE, to work with less and sass but it was not working so maybe try later,
    {% stylesheets          // helpfull link: http://stackoverflow.com/questions/34039842/strange-unexpected-stylesheets-tag-error
        'bundles/event/css/event.css'
        'bundles/event/css/events.css'
        'bundles/event/css/main.css'
        filter='cssrewrite'
    %}
        <link rel="stylesheet" href="{{ asset_url }}" />
    {% endstylesheets %}
{% endblock %} -->
Symfony Standard Edition
========================

Welcome to the Symfony Standard Edition - a fully-functional Symfony2
application that you can use as the skeleton for your new applications.

For details on how to download and get started with Symfony, see the
[Installation][1] chapter of the Symfony Documentation.

What's inside?
--------------

The Symfony Standard Edition is configured with the following defaults:

  * An AppBundle you can use to start coding;

  * Twig as the only configured template engine;

  * Doctrine ORM/DBAL;

  * Swiftmailer;

  * Annotations enabled for everything.

It comes pre-configured with the following bundles:

  * **FrameworkBundle** - The core Symfony framework bundle

  * [**SensioFrameworkExtraBundle**][6] - Adds several enhancements, including
    template and routing annotation capability

  * [**DoctrineBundle**][7] - Adds support for the Doctrine ORM

  * [**TwigBundle**][8] - Adds support for the Twig templating engine

  * [**SecurityBundle**][9] - Adds security by integrating Symfony's security
    component

  * [**SwiftmailerBundle**][10] - Adds support for Swiftmailer, a library for
    sending emails

  * [**MonologBundle**][11] - Adds support for Monolog, a logging library

  * **WebProfilerBundle** (in dev/test env) - Adds profiling functionality and
    the web debug toolbar

  * **SensioDistributionBundle** (in dev/test env) - Adds functionality for
    configuring and working with Symfony distributions

  * [**SensioGeneratorBundle**][13] (in dev/test env) - Adds code generation
    capabilities

  * **DebugBundle** (in dev/test env) - Adds Debug and VarDumper component
    integration

All libraries and bundles included in the Symfony Standard Edition are
released under the MIT or BSD license.

Enjoy!

[1]:  https://symfony.com/doc/2.8/book/installation.html
[6]:  https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/index.html
[7]:  https://symfony.com/doc/2.8/book/doctrine.html
[8]:  https://symfony.com/doc/2.8/book/templating.html
[9]:  https://symfony.com/doc/2.8/book/security.html
[10]: https://symfony.com/doc/2.8/cookbook/email.html
[11]: https://symfony.com/doc/2.8/cookbook/logging/monolog.html
[13]: https://symfony.com/doc/2.8/bundles/SensioGeneratorBundle/index.html
[14]: http://www.freecodecamp.com/djanov
[15]: http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/annotations-reference.html
[16]: http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/index.html
