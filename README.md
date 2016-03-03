This is my first Symfony project
=================================

novalidate="novalidate atrakni?!

Importent after the curse:
-------------------------

  * **Dependacy Injection and the art of services and containers** [knpuniversity][37]
  * [More about Twig Extensions][40]
  * More on Tags, [The Dependacy Injection Tags][41], very important tag is [kernel.event_listener][42], which allows you to register "hooks" inside Symfony at various stages of the request lifecycle.
  * [Changhing and Using Form Variables][51] (Inventing a New Form Variable and using it in the form_teheme.html.twig then we can use this in our register.html.twig but we can do the same in the RegisterFormType.php using the FormView Customizing Form Variables from Form Type by adding 2 use statements FormInterface and FormView then assign some data or w/e). [Most of the core built-in form view variables come from a FormType::buildView method][53]

  Bootsrap Bundle from knp:

  * [MopaBootstrapBundle][47]
  * [BraincraftedBootsrapBundle][48]

Deployment
==========

  First time steps:

 * Step 1. **git clone git@github.com:djanov/starwarsevents.git knpevents.com**
 * Step 1.1 **MOVE** => **cd knpevents.com** into the directory if your code lives anywhere other the master barnch, you'll need to switch to that branch: **git checkout -b  episode4-finish origin/episode4-finish**
 * Step 1.2 Github might ask to authenticate or give some public key error if that happens you'll need to register the public key of your Server as a [deploy key][54] for your repository. This is what gives your server permission access to the code.

 * Step 2. Configuring the Web Server (I'm using the XAMPP) open Apache config file (httpd.conf) the in the end (example we have the project in the **c:\xampp\htdocs\knpevents.com**) add:
 ```
 <VirtualHost *:80>
     ServerName knpevents.com
     DocumentRoot "/xampp/htdocs/live/knpevents.com/web"

     <Directory "/xampp/htdocs/live/knpevents.com/web">
         AllowOverride All
     </Directory>
ErrorLog /var/log/apache2/events_error.log
CustomLog /var/log/apache2/events_access.log combined
 </VirtualHost>

 ```
 then restart the apache2 server

 * Step 3. Install [composer][55]: **curl -sS https://getcomposer.org/installer | php** then install the coposer.phar. linux: **php composer.phar install**, windows: **composer install**

 * Step 4. Then create the database insert the schema and add some dummy data:
 ```
php app/console doctrine:database:create
php app/console doctrine:schema:create
php app/console doctrine:fixtures:load

 ```
 * Step 5. only in linux, we have an error when we go to **knpevents.com** we can't write to the cache directory so give privileges to cache and logs : **sudo chmod -R 777 app/cache/ app/logs**

 * Step 6. The site will now run but the css will not work and so need to dump the assetic assets and to install the uglifycss for the minifying so first install that
 ```
 npm install

 php app/console assetic:dump --env=prod

 ```

 If the uglifycss doesn't work. After npm install go to and change in **config.yml** under filters(tested in windows 10):
 ```
 uglifycss:
   node:  null
   bin: "C:/Users/WHATEVER_IS_YOUR_USERNAME/AppData/Roaming/npm/uglifycss"

  ```
  After that run first: **php app/console cache:clear --env=prod** The --env=prod is important! and then run the command:
  ```
  php app/console assetic:dump --env=prod

  ```

 * Step 7. Done. I just add step 7 because its my favorite number lol.

 Thing to do on each Deploy  
 ==========================

 * Step 1. Update your Code, as simple as running a git pull:
 ```
 git pull origin

 ```

* Step 2. If there are new libraries in Composer, run the install command (windows):
```
composer install
```
Linux:
```
php composer.phar install

```

* Step 3. Update the database schema, the easy but maybe dangerous way is with the schema update console command:

```
php app/console doctrine:schema:update --force
```
This is dangerous because let's say you rename a property from **name** to **firstName**. Instead of renaming the column, this task may just **drop** name and add **firstName**. That would mean that you'd lose all that data! But there's a library called [Doctrine Migrations][56] that helps do this safely.

* Step 4. Clear the production cace:

```
php app/console cache:clear --env=prod
```

* Step 5. Dump Assetic assets:

```
php app/console assetic:dump --env=pord
```

* Step 6. **DONE**

* Bonus Step 7. Performeance Setup you Need!!

To maximize Symfony's performance:
First when deploy, dump Composer's optimized autoloader:
```
php composer.phar dump-autoload --optimize // linux
composer dump-autoload --optimize // windows (not tested)
```
if you add the **–optimize-autoloader** flag, Composer will generate a class map, which will give the whole application a performance boost. Using the [APC ClassLoader][57] may give an even bigger boost.
Next make sure that byte code cache is installed on the server. for php 5.5 it's OPcache. In background, these cache the compiled PHP files, making the site much faster. Make sure you have one of these on the server there's no downside.

Notes:
------
  * Show every route in the app: **php app/console router:debug**
  * Run the server: **php app/console server:run**
  * Show(access) every services in the app: **php app/console container:debug** [More about Container, the “doctrine” Service and the Entity Manager][27].
  * Using doctrine: **php app/console doctrine:generate:entity**
  * query from console: **php app/console doctrine:query:sql "SELECT * from yoda_event"**
  * when updating the entity the doctrine need to be updated: before updating we can see what would be done: **php app/console doctrine:schema:update --dump-sql** and if its everything is alright we can execute command: **php app/console doctrine:schema:update --force**
  * to  use annotations in [doctrine docs][15] prefix them with **@ORM** before putting them in the symfony example @Column becomes **@ORM\Column** in somfony. That is because of the use statement in the Entity/Event.php **use Doctrine\ORM\Mapping as ORM;**
  * to use app in prod option not in app.dev first need to clear the cache  for prod: **php app/console cache:clear --env=prod**
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
  * [So What's a Service][36]
  * [Configuration Loading and Type-Hinting][38]
  * [FrameworkBundle Configuration "framework"][44]
  * In the **dev** environment, Symfony keeps our 3 files so we can debug more easily. In **prod**, it puts them all together, but when your browser request this one CSS file, it's still being executed through dynamic Symfony route. For production, that's way too slow. And depending on your steup, it may not even be working in the **prod** environment. So to gain more speed
  ```
   php app/console assetic:dump --env=prod

  ```
  This wrote a physical file to the **web/css** directory. And when we refresh, the web server loads this file instead of going through Symfony. When we deploy our application, this command will be part of our deploy process.

  The **cssrewrite** filter dynamically changes the url so that things still work.

  ```
  {% stylesheets
    'bundles/event/css/event.css'
    'bundles/event/css/events.css'
    'bundles/event/css/main.css'
    filter='cssrewrite'
%}
    <link rel="stylesheet" href="{{ asset_url }}" />
{% endstylesheets %}  

  ```
  More about [Assetic filters][45] but lot of them aren't documented.

  * Applying a Filter only in the prod Environment.
  - If we want the **uglifycss** filter to only run in the prod environment. We can do just by adding a **?** before the filter name:

  ```
  {# app/Resources/views/base.html.twig #}
    {# ... #}

    {% stylesheets
      'bundles/event/css/event.css'
      'bundles/event/css/events.css'
      'bundles/event/css/main.css'
      filter='cssrewrite'
      filter='?uglifycss'
      output='css/built/layout.css'
    %}
    <link rel="stylesheet" href="{{ asset_url }}" />
 {% endstylesheets %}
 ```

 * [If dev environment start running slower][46]
 * [Twig Template Form Function and variable Reference][52]

Useful information:
-------------------
  * - **When you’re in a service and you need to do some work, just find out which service does that work, inject it through the constructor, then use it. **

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

  pro tip if we want to render partial pages use underscore in front of the name.

  ```
    public function _upcomingEventsAction()

  ```

  **Tags**: Telling Symfony about your Twig extension

  If page says that filter doesn't exist. We have to create a valid Twig extension with the filter. Services to the rescue!
  First, need to create a new service for the Twig extension:

  ```
  # src/Yoda/EventBundle/Resources/config/services.yml
  services:
      # ...

      twig.event_extension:
          class: Yoda\EventBundle\Twig\EventExtension
          arguments: []

  ```

  The arguments are empty because we don't have a constructor in this case. At this point Twig extension is a service, but Twig doesn't know about it. We need to tell Symfony this isn't a normal service, it's a Twig Extension!
  Add a **tags** key with **twig.extension**:

  ```
  # src/Yoda/EventBundle/Resources/config/services.yml
services:
    # ...

    yoda_event.twig.event_extension:
        class: Yoda\EventBundle\Twig\EventExtension
        arguments: []
        tags:
            - { name: twig.extension }
  ```
  Twig looks for all services with the **twig.extension** tag and includes those as extensions. For more real life check out the [KnpTimeBundle][39], which is even more powerful.

  Working with assets when using the config.yml file to add CDN host:
  ```
  # app/config/config.yml
framework:
    # ...
    templating:
        engines: ['twig']
        assets_version: 5-return-of-the-jedi
        assets_version_format: "%%s?v=%%s"
        assets_base_url: http://evilempireassets.com

  ```
  in the **assets_base_url** take the **http** part of the host name
  ```
  # app/config/config.yml
# ...

framework:
    # ...
    templating:
        engines: ['twig']
        assets_version: 5-return-of-the-jedi
        assets_version_format: "%%s?v=%%s"
        assets_base_url: //myfancycdn.com
  ```
  This is a valid URL and make sure if the user is on an https page on your site that the css file is also downloaded via **https**. This avoids the annoying warnings about "non-secure" assets.

  WHAT IS A CACHE BUSTER?
  =======================

  A cache-buster is a unique piece of code that prevents a browser from reusing an ad it has already seen and cached, or saved, to a temporary memory file.

  **What Does a Cache-Buster Do?**

  The cache-buster doesn’t stop a browser from caching the file, it just prevents it from reusing it. In most cases, this is accomplished with nothing more than a random number inserted into the ad tag on each page load. The random number makes every ad call look unique to the browser and therefore prevents it from associating the tag with a cached file, forcing a new call to the ad server.

  Cache-busting maximizes publisher inventory, keeps the value and meaning of an impression constant, and helps minimize discrepancies between Publisher and Marketer delivery reports.

  Form Variables: The Holy Grail of Form Rendering Control
  ========================================================

  Inside the **form_errors** block, we have access to some **errors** variable. In fact, in each block we have access to a bunch of variables, like **label**, **value**, **name**, **full_name** and **required.**

  Let's use a trick to see all of the variables we have acces to in **form_errors**:

  ```
  {# app/Resources/views/form_theme.html.twig #}
    {# ... #}

    {% block form_errors %}
      {{ dump(_context|keys) }}

      {% if errors|length > 0 %}
        <ul class="help-block">
          {% for error in errors %}
              <li>{{ error.message }}</li>
          {% endfor %}
        </ul>
     {% endif %}
 {% endblock form_errors %}

 ```

  So the important part is the **{{ dump(_context|keys ) }}** when the magic happens. The **dump** is a Twig debugging function, like **var_dump**. You can pass it any variable to print it out.

 if the css doesn't won't work or something like that if clearing the cache or dump the assetic then try **php app/console assets:install --symlink**

 [The Block Names (e.g. form_row versus textarea_widget)][49] aka Tweak all the things! Just find the right block, copy it into your template, use the variables and customize it. [the cookbook article][50].



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

  [Going Deeper with Exception Handling][35]

  [Lifecycle Callbacks][43]

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
[35]: https://knpuniversity.com/screencast/symfony2-ep3/error-pages
[36]: https://knpuniversity.com/screencast/symfony2-ep3/services#play
[37]: http://knpuniversity.com/screencast/dependency-injection
[38]: https://knpuniversity.com/screencast/symfony2-ep3/config-imports-type-hinting#play
[39]: https://github.com/KnpLabs/KnpTimeBundle
[40]: http://twig.sensiolabs.org/doc/advanced.html
[41]: http://symfony.com/doc/current/reference/dic_tags.html
[42]: http://symfony.com/doc/current/reference/dic_tags.html#kernel-event-listener
[43]: https://symfony.com/doc/current/book/doctrine.html#lifecycle-callbacks
[44]: https://symfony.com/doc/2.8/reference/configuration/framework.html#assets-base-urls
[45]: https://github.com/kriswallsmith/assetic#filters
[46]: https://symfony.com/doc/current/cookbook/assetic/asset_management.html#dumping-asset-files-in-the-dev-environment
[47]: http://knpbundles.com/phiamo/MopaBootstrapBundle
[48]: http://knpbundles.com/braincrafted/bootstrap-bundle
[49]: https://knpuniversity.com/screencast/symfony2-ep4/form-input-label-customizations#play
[50]: https://symfony.com/doc/current/cookbook/form/form_customization.html
[51]: https://knpuniversity.com/screencast/symfony2-ep4/more-form-customizations#play
[52]: https://symfony.com/doc/2.8/reference/forms/twig_reference.html#twig-reference-form-variables
[53]: https://github.com/symfony/symfony/blob/2.1/src/Symfony/Component/Form/Extension/Core/Type/FormType.php#L106
[54]: https://developer.github.com/guides/managing-deploy-keys/
[55]: https://getcomposer.org/download/
[56]: https://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html
[57]: https://symfony.com/doc/current/book/performance.html#caching-the-autoloader-with-apc
