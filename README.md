This is my first Symfony project
=================================

novalidate="novalidate atrakni?!

Notes:
------
  * Show every route in the app: **php app/console router:debug**
  * Run the server: **php app/console server:run**
  * Show(access) every services in the app: **php app/console container:debug** [More about Container, the “doctrine” Service and the Entity Manager][27].
  * Using doctrine: **php app/console doctrine:generate:entity**
  * query from console: **php app/console doctrine:query:sql "SELECT * from yoda_event"**
  * when updating the entity the doctrine need to be updated: before updating we can see what would be done: **php app/console doctrine:schema:update --dump-sql** and if its everything is alright we can execute command: **php app/console doctrine:schema:update --force**
  * to  use annotations in [doctrine docs][15] prefix them with **@ORM** before putting them in the symfony example @Column becomes **@ORM\Column** in somfony. That is because of the use statement in the Entity/Event.php **use Doctrine\ORM\Mapping as ORM;**
  * to use app in prod option not in app.dev first need to clear the cashe for prod: **php app/console cache:clear --env=prod**
  * code generation(CRUD): **php app/console doctrine:generate:crud**
  * to run doctrine (adding dummy files): **php app/console doctrine:fixtures:load**
  * doctrine fixtures if working check: **php app/console doctrine:fixtures:load --help** if the code runs we are ready to import some dummy data. after that use the command again without the --help this command is first purge all the data in the database and put the "new" data what we created.
  * [for shortcuts][16]. This bundle provides a way to configure your controllers with annotations.
  * [Twig Mind Tricks][17]
  * generate setters and getters example: **php app/console doctrine:generate:entities UserBundle --no-backup**  the --no-backup prevents the command from creating little backup version of the file.
  * [Twig Template Form Function and Variable Reference][21]
  * [Form Types Reference][22]
  * To run phpunit testing in windows: **bin\phpunit -c app**
  * Use [Behat][24] instead of Symfony's built in functional testing tools
  * [Migrating to symfony 3.0][25] 1/68 ptt
  * [The DomCrawler Component][26] aka how to use **crawler** in testing
  * [Accessing the User][29]
  * [How to Embed a Collection of Forms][30]
  * [CollectionType Field][31]

Useful information:
-------------------

  * - The template name is always have 3 parts: **EventBundle:Default:index.html.twig**
  * 1) A Bundle name
  * 2) A subdirectory
  * 3) And a template file name

  * - annotations (read phrase by doctrine) is: **PHP comments that's read like configuration**

  * - by using the twig **parent()** function all the parent content is included first. in example if we are want to to use the login.css only in the login.html.twig:
```
{% block stylesheets %}
  {{ parent() }}

  <link rel="stylesheet" href="{{ asset('bundles/user/css/login.css') }}"/>
{% endblock %}
```  

  * Don’t Need isSubmitted
  ```
  if ($form->isSubmitted() && $form->isValid()) {

  ```
  ```
  if ($form->isValid()) {
  ```
  This actually doesn’t change anything because **isValid()** automatically returns false if the form wasn’t submitted - meaning, if the request isn’t a POST. So either just do this, or keep the **isSubmitted** part in there if you want.

  * Disabling HTML5 Validation:
    To disable it, just add a novalidate attribute to your form tag:
  ```
  {# src/Yoda/UserBundle/Resources/views/Register/register.html.twig #}

  {# ... #}
  <form action="{{ path('user_register') }}" method="POST" novalidate="novalidate">
  ```

  * To add symfony server side validation in the user class need to add:
  ```
  // src/Yoda/UserBundle/Entity/User.php

  use Symfony\Component\Validator\Constraints as Assert;
  ```
  Now the validation to add using the Assert to make the field not blank example:
  ```
  /**
 * @ORM\Column(name="username", type="string", length=255)
 * @Assert\NotBlank()
 */
private $username;
  ```
  If we wan to add unique constraint example if we wan to check if the username or the email is already in the database then we need to add the **UniqueEntity** Constraint.

  ```
  // src/Yoda/UserBundle/Entity/User.php

  use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
  ```
   After that to add **UniqueEntity** it need to be added above a class not the property. it takes two options the field that should be unique followed by a (error)message:
   ```
   // src/Yoda/UserBundle/Entity/User.php
// ...

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="yoda_user")
 * @ORM\Entity(repositoryClass="Yoda\UserBundle\Entity\UserRepository")
 * @UniqueEntity(fields="username", message="That username is taken!")
 * @UniqueEntity(fields="email", message="That email is taken!")
 */
class User implements AdvancedUserInterface, Serializable

   ```
 [Strategies for Controller Access][28]
 ---------------------------------
 Keep these two tips in mind when using roles:
 - 1. Protect the actual parts of your application using feature-specific roles, not user-specific roles. This means your roles should describe the features they give you access to, like **ROLE_EVENT_CREATE** and not the type of user that should have access, like **ROLE_ADMIN**.

 - 2. Use the role hierarchy section to manage which types of users have which roles. For example, you might decide that **ROLE_USER** should have **ROLE_BLOG_CREATE** and **ROLE_EVENT_CREATE**, which you setup here. Assign your actual users these user-specific roles, like **ROLE_USER** or **ROLE_MARKETING**.

 Switching Users / Impersonation
 -------------------------------
 To to activate **switch_user*** feature:
 ```
 # app/config/security.yml
 security:
     # ...
     firewalls:
         secured_area:
             # ...
             switch_user: ~
 ```

 To use it, just add a **?_switch_user=** query parameter to any page with the username you want to change to. Make sure that the admin has the **ROLE_ALLOWED_TO_SWITCH**:

 ```
 # app/config/security.yml
security:
    # ...
    role_hierarchy:
        ROLE_ADMIN:       [ROLE_USER, ROLE_EVENT_CREATE, ROLE_ALLOWED_TO_SWITCH]
        # ...

 ```

 to exit(logout) the user: **localhost/app_dev.php/new?_switch_user=_exit**

  If we have problem with example existing events that's isn't unique, so when we run **php app/console doctrine:schema:update --force** and have bunch of errors try first drop the schema and rebuild from scratch to get around it:
  ```
  php app/console doctrine:schema:drop --force
  php app/console doctrine:schema:create  
  php app/console doctrine:fixtures:load

  ```
  and reload the fixtures ofc.

  If we set for example **slug** wildcard istead of **id** in **event_show** route, we need to change the wildcard we're passing to it. To make sure **event_show** we're using this route:

  ```
   git grep event_show

  ```

  **Whenever you need a custom query: create a new method in the right repository class and build it there. Don’t create queries in your controller**

  Useful links:
  -------------
  Serializing:

  * [The Basics of Serializing Objects in PHP][18]
  * [serialize][19]
  * [The Serializer Component][20]

  Validation:

  * [Validation Constraints Reference][23]

  DoctrineExtensions:

  * For slug [StofDoctrineExtensionsBundle][32]
  * Also using by slug to (sluggable.md, timestampable.md)[DoctrineExtensions/doc/][33]

  FOSJsRoutingBundle: [it's easy to use to generate symfony routes right in a javascript][34]

  Using the bcrypt password encoder:
  ---------------------------------

  fore bcrypt password encoder there is no need to make the getsalt() in **Yoda\UserBundle\Entity\User.php** because the salt is making automatically by bcrypt the getsalt() method is just for the UserInterfaces because its requires 5 methods to work.

  Trust Levels: IS_AUTHENTICATED_ANONYMOUSLY, IS_AUTHENTICATED_REMEMBERED, IS_AUTHENTICATED_FULLY:
  ================================================================================================

  **is_granted** is how you check security in Twig, and we also could have passed normal roles like **ROLE_USER** and **ROLE_ADMIN**, instead of this **IS_AUTHENTICATED_REMEMBERED** thingy. So in addition to checking to see if the user has a given role, Symfony has 3 other special security checks you can use.

  * First, **IS_AUTHENTICATED_REMEMBERED** is given to all users who are logged in. They may have actually logged in during the session or may be logged in because they have a “remember me” cookie.
  * Second, **IS_AUTHENTICATED_FULLY** is actually stronger. You only have this if you’ve actually logged in during this session. If you’re logged in because of a remember me cookie, you won’t have this;
  * Finally, **IS_AUTHENTICATED_ANONYMOUSLY** is given to all users, even if you’re not logged in. And since literally everyone has this, it seems worthless But it actually does have a use if you need to white-list URLs that should be public.

  Since we’re checking for **IS_AUTHENTICATED_REMEMBERED**, we’re showing the logout link to anyone who is logged in, via a remember me cookie or because they recently entered their password. We want to let both types of users logout.


PROBLEMS WITH ENTITY AND DOCTRINE:
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
[17]: https://knpuniversity.com/screencast/symfony2-ep1/mind-tricks#play
[18]: http://www.devshed.com/c/a/PHP/The-Basics-of-Serializing-Objects-in-PHP/
[19]: http://php.net/manual/en/function.serialize.php
[20]: http://symfony.com/doc/2.8/components/serializer.html
[21]: http://symfony.com/doc/2.8/reference/forms/twig_reference.html
[22]: http://symfony.com/doc/2.8/reference/forms/types.html
[23]: http://symfony.com/doc/current/reference/constraints.html
[24]: http://knpuniversity.com/screencast/behat
[25]: http://www.slideshare.net/nicolas.grekas/migrating-to-symfony-30
[26]: http://symfony.com/doc/current/components/dom_crawler.html
[27]: https://knpuniversity.com/screencast/symfony2-ep2/container-doctrine#play
[28]: https://knpuniversity.com/screencast/symfony2-ep2/role-hierarchies#play
[29]: https://knpuniversity.com/screencast/symfony2-ep2/accessing-security-user#play
[30]: http://symfony.com/doc/2.8/cookbook/form/form_collections.html
[31]: http://symfony.com/doc/2.8/reference/forms/types/collection.html
[32]: https://github.com/stof/StofDoctrineExtensionsBundle/blob/master/Resources/doc/index.rst
[33]: https://github.com/Atlantic18/DoctrineExtensions/tree/master/doc
[34]: https://github.com/FriendsOfSymfony/FOSJsRoutingBundle/blob/master/Resources/doc/index.md#generating-uris
