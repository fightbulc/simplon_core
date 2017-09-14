<pre>
     _                 _                                  
 ___(_)_ __ ___  _ __ | | ___  _ __     ___ ___  _ __ ___ 
/ __| | '_ ` _ \| '_ \| |/ _ \| '_ \   / __/ _ \| '__/ _ \
\__ \ | | | | | | |_) | | (_) | | | | | (_| (_) | | |  __/
|___/_|_| |_| |_| .__/|_|\___/|_| |_|  \___\___/|_|  \___|
                |_|                                       
</pre>

# Simplon/Core

The simplon/core package is a strongly opinionated set of libraries which forms the core of a component based app. The package requires [PHP7.1+](https://github.com/tpunt/PHP7-Reference/blob/master/php71-reference.md) and is built against [PSR-7](http://www.php-fig.org/psr/psr-7/) in conjunction with middleware layers.

-------------------------------------------------

1. [__App structure__](#1-app-structure)  
1.1 [Registry](#11-registry)  
1.2 [Context](#12-context)  
1.3 [Routes](#13-routes)  
1.4 [Storage](#14-storage)  
1.5 [Outgoing requests](#15-outgoing-requests)  
2. [__Skeletons__](#2-skeletons)  
2.1 [Generate a default app](#21-generate-a-default-app)  
2.2 [Add a component](#22-add-a-component)  
2.3 [Add a view to a component](#23-add-a-view-to-a-component)  
2.4 [Add a store to a component](#24-add-a-store-to-a-component)  
3. [__Middleware__](#3-middleware)  
3.1 [Exception](#31-exception)  
3.2 [Locale](#32-locale)  
3.3 [Route](#33-route)  
3.4 [Auth](#34-auth)  
4. [__Controllers__](#4-controllers)  
4.1 [ViewController](#41-viewcontroller)  
4.2 [RestController](#42-restcontroller)  
5. [__Views__](#5-views)  
5.1 [Templates](#51-templates)  
5.2 [Building pages](#52-building-pages)  
6. [__Form helper__](#6-form-helper)  
6.1. [Define form fields](#61-define-form-fields)  
6.2. [Create form view](#62-create-form-view)  
6.3. [Implement in main view](#63-implement-in-main-view)  
6.4. [Controller implementation](#64-controller-implementation)  

-------------------------------------------------

# 1. App structure

An app is mainly made up by some app-wide classes but mainly by its components which are tiny apps in itself.
The general idea is that the app will be easier to communicate and to maintain if its broken down in smaller
pieces hence the focus on components. Further, the app works with inheritence when it comes to the modules of
`config` and `locale`. Inheritence goes from app-level to the component so that the component has access to
app-wide data.

The main concept is `MVC` which means that your `controller` will receive a request, process the data with the
help of whatever is needed and pass it
on to a view or redirect to another resource. Simplon\Core distinguishes between two types of controller:
`ViewController` is the first type which is ment to handle requests which should result in a website. The second
type is a `RestController` which is ment to handle requests for any api related data. Latter will respond with
`JSON structured` data.

Components can only communicate via `events`. They can either `pull` information by other component's `offers`
or `subscribe` to events. Components describe their events in their own events class. `Offers` or `Subscriptions`
are defined as class constants.

It's important to note that all requests will run through a number of pre-defined `Middleware` with the
`RouteMiddleware` as the only required one since it handles all incoming requests.

## 1.1. Registry

Each component needs to be registered via its own registry class. It requires a method for receiving
the `Context` class and can take optionally methods for referencing `Routes`, `Authentication rules` and
`Events` definition.

## 1.2. Context

App and components have `Context` classes which hold all essential instances. Essential means instances which
are shared among different classes within the app respectively the component. For example, if you have a storage
for a component you would put the instance creation of that storage in the components context class. Context
classes hold also references to your config- and locale-data.

## 1.3. Routes

All component related routes are defined in a component based `Route` class. This class holds all route
`patterns` and static methods which are used to build corresponding routes.

## 1.4. Storage

Storage is handled via `CRUD` classes. Only `MySQL` adapter is available at the moment. A storage is
described by its storage- and model class. If you  want to interact with your data you should go through the
storage class and avoid direct access.

## 1.5. Outgoing requests

Component related `outgoing requests` are all collected within its own `Requests` class, mainly to aid
transparency and structure. This class is obviously only needed if you have any type of these requests.

-------------------------------------------------

# 2. Skeletons

Core has a command line tool which lets your create code skeletons in order to help you setting up your app,
components or part of your component such as `CrudStore`/`CrudModel` classes.

You can find all possible commands by running the following command from your terminal after you installed
`simplon/core` with `composer install`:

```bash
vendor/bin/core -h
```
 
## 2.1. Generate a default app

Let's create a default app called `MyApp`. We want to use `Views` for our app so we will use the option `--with-view`.
This is not needed if you only want to use a `REST` interface. 

```bash
vendor/bin/core init MyApp --with-view 
```

## 2.2. Add a component

Since core is component based we need to have at least one component. Let's add one and name it `Cars`.
Again, we wanna use `Views` so we have to add that option but this time we have to add the name of our first `ViewController`.

```bash
vendor/bin/core component Cars --with-view=Car
```

There is also an option for a `REST` interface:

```bash
vendor/bin/core component Cars --with-rest
```

It's also possible to combine both options:

```bash
vendor/bin/core component Cars --with-view=Car --with-rest
```

## 2.3. Add a view to a component

If any of your components needs another `View` you can run the following command which will add a new `ViewController`
and a default set of a `View` and a `Template`.

For the following example let's assume that we have a component called `Team`. Now we want to add a skeleton view for resources:

```bash
vendor/bin/core view Team Resources 
```

This should create the following files:
- `App/Components/Team/Controllers/TeamViewController.php`
- `App/Components/Team/Views/Resources/ResourcesView.php`
- `App/Components/Team/Views/Resources/ResourcesTemplate.phtml`

You still need to add a `Route` and register this route within the `Registry`.

## 2.4. Add a store to a component

If any of your components needs a `CrudStore` you can run the following command which will build
a default set of a store/model class. You can add the options for setting the names of the `store`, `model`
and `database table`. By default it will derive it from the `component name`.

```bash
vendor/bin/core store Cars
```

-------------------------------------------------

# 3. Middleware

Middleware helps us to handle/structure our request/response processing. It is also some sort of simplification by
pre-processing the request e.g. for authentication reasons before it hits the actual controller. Lastly, it's a great
tool due to its scalability and flexiblity. So far there are four classes which come with the core:

## 3.1. Exception

Wraps all following processing and handles exceptions with [Whoops](https://github.com/filp/whoops).

## 3.2. Locale

If integrated it will detect a two-letter defined locale within the requesting route (e.g. /en/) or even a region specific
locale with additional three-letters (e.g. /en-us/). If latter is the case the region specific file (e.g. `en-us-locale.php`)
will inherit from the main locale (e.g. `en-locale.php`). This middleware expects an array of accepted locale codes
(e.g. `['en', 'de']`) but will fallback to `['en']` if non is given.

## 3.3. Route

The RouteMiddleware will check the requested route against all defined routes (only registered components).
It will also kick-off the `event handling` for these components.

## 3.4. Auth

AuthMiddleware aids authentication against certain `routes`, `user roles` and `temporary tokens`. To make this work
we need to create an `AuthContainer` which is required for the `AuthMiddleware` as is the `ComponentsCollection`.
The later is required since all authentication rules are defined by each component.

`AuthContainer` is a class you have to setup which should be extended from the core's abstact `AuthContainer` class.
The AuthMiddleware will use this container to authenticate the current request by calling `fetchUser` which should handle
the actual authentication. Hence, you are free to choose how you want to authenticate the request.

The AuthMiddlware will call `onSuccess` or `onError` callbacks if available. Both callbacks will receive a
`ResponseInterface` object while `onSuccess` will receive `AuthUserInterface` as second parameter. Make sure to return
the `ResponseInterface` object for both cases.

Eventually, you need to knit everything together. Following a rough example of your bootstrap:

```php
$appContext = new AppContext();

$components = new ComponentsCollection();
$components->add(new FooRegistry($appContext());

$authContainer = new AuthContainer();

$middleware = new MiddlewareCollection();
$middleware->add(new AuthMiddleware($authContainer, $components));

(new Core())->run($components, $middleware);
```

### 3.4.1. Example session based AuthContainer

This is just a rough example to clarify discussed content:

```php
namespace App\Components\Auth\Managers;

use App\Components\Auth\AuthRoutes;
use App\Components\Auth\Data\AuthSessionUser;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplon\Core\Interfaces\AuthUserInterface;
use Simplon\Core\Middleware\Auth\AuthContainer;

/**
 * @package App\Components\Auth\Managers
 */
class AuthViewContainer extends AuthContainer
{
    /**
     * @param ServerRequestInterface $request
     *
     * @return null|AuthUserInterface
     */
    public function fetchUser(ServerRequestInterface $request): ?AuthUserInterface
    {    
        if (!empty($_SESSION['session']))
        {                    
            return new AuthSessionUser($_SESSION['session']);
        }

        return null;
    }

    /**
     * @return callable|null
     */
    protected function getOnError(): ?callable
    {
        return function (ResponseInterface $response) {
            if (empty($response->getHeaderLine('Location')))
            {
                $response = $response->withAddedHeader('Location', AuthRoutes::toSignIn());
            }

            return $response;
        };
    }
}
```

### 3.4.2. Example REST based AuthContainer

Here is a rough example with a `bearer token` and a lookup in a user database:

```php
namespace App\Components\Auth\Managers;

use App\Components\Auth\AuthRoutes;
use App\Components\Auth\Data\AuthRestUser;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplon\Core\Interfaces\AuthUserInterface;
use Simplon\Core\Middleware\Auth\AuthContainer;
use Simplon\Mysql\Mysql;
use Simplon\Mysql\MysqlException;

/**
 * @package App\Components\Auth\Managers
 */
class AuthRestContainer extends AuthContainer
{
    /**
     * @var Mysql
     */
    private $mysql;

    /**
     * @param Mysql $mysql
     */
    public function __construct(Mysql $mysql)
    {
        $this->mysql = $mysql;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return null|AuthUserInterface
     * @throws MysqlException
     */
    public function fetchUser(ServerRequestInterface $request): ?AuthUserInterface
    {    
        if ($bearer = $this->fetchAuthBearer($request))
        {
            list($token, $secret) = explode(':', $bearer);

            $query = '
            -- noinspection SqlDialectInspection
            select * from ' . AuthStore::TABLE_NAME . ' where ' . AuthModel::COLUMN_TOKEN . ' = :token
            ';

            if ($row = $this->mysql->fetchRow($query, ['token' => $token]))
            {
                $authUser = new AuthRestUser($row);

                if ($secret)
                {
                    $authUser->validateSecret($secret);
                }

                return $authUser;
            }
        }

        return null;
    }

    /**
     * @return callable|null
     */
    protected function getOnError(): ?callable
    {
        return function (ResponseInterface $response) {
            if (empty($response->getHeaderLine('Location')))
            {
                $response = $response->withAddedHeader('Location', AuthRoutes::toSignIn());
            }

            return $response;
        };
    }
}
```

-------------------------------------------------

# 4. Controllers

Each identified route ends up in a controller. There are two types of controllers which only differ in a couple of `media related methods` and their `response content type`. Both types expect an `__invoke` method which receives either an empty array or a set of possible params. These params are partial structures of your defined route which leads to the connected controller. For instance, a route such as `/some/{foo}/stuff` would match a requested route of `/some/more/stuff`. For that example your controller params would hold `['foo' => 'more']`.

## 4.1. ViewController

This controller type is used for all requests which result in a rendered html page.

```php
class SomeViewController extends ViewController
{
    /**
    * @param array $params
    *
    * @return ResponseViewData
    */
    public function __invoke(array $params): ResponseViewData
    {
        // some code

        //
        // you can handle redirects
        //
        
        if($shouldRedirect)
        {
            $this->getFlashMessage()->setFlashSuccess('Some flash message');

            return $this->redirect('/some/route');
        }

        //
        // or respond with view data
        //
        
        return $this->respond(new SomeView());
    }

    /**
    * @return SomeRegistry
    */
    public function getRegistry(): SomeRegistry
    {
        return $this->registry;
    }
}
```

## 4.2. RestController

```php
class SomeRestController extends RestController
{
    /**
     * @param array $params
     *
     * @return ResponseViewData
     */
    public function __invoke(array $params): ResponseRestData
    {
        // some code
        
        //
        // respond with array data which will be
        // transformed into JSON
        //
        
        return $this->respond(['foo' => 'bar']);
    }

    /**
     * @return SomeRegistry
     */
    public function getRegistry(): SomeRegistry
    {
        return $this->registry;
    }
}
```

-------------------------------------------------

# 5. Views

Views only know about the stuff they receive as dependency. The first dependency, and only requirement,
is `CoreViewData` which holds the instances of `Locale`, `FlashMessages` and `Device`. The function of `Locale`
is clear. `FlashMessages` show messages such as warnings, errors or successes which have been defined in
the controller. `Device` is used to detect defined templates based on `mobile`, `tablet` or `anything else`.
A view expects a template and optional some data which will be injected into the template. The above mentioned
instances from our `CoreViewData` are automatically injected.

## 5.1. Templates

As already mentioned each template receives three variables by default: `$locale`, `$flash` and `$device`. The
view class offers also a couple of static helper methods such as `View::renderWidget` which aids the need of
rendering smaller template pieces or as we call it `widgets`. Here is a small example of a template:

```php
/**
 * @var FlashMessage $flash
 * @var Locale $locale
 * @var Device $device
 *
 * @var string $content
 */
use Simplon\Core\Views\FlashMessage;
use Simplon\Core\Views\View;
use Simplon\Device\Device;
use Simplon\Locale\Locale;

?>
<?php if ($flash->hasFlash()): ?>
    <?= $flash->getFlash('huge') ?>
<?php endif ?>

<div>
    Some content
</div>

<div>
    <?= View::renderWidget(__DIR__ . '/SomeWidget.phtml', ['foo' => 'bar']) ?>
</div>
```

### Using device templates

By default the view uses the defined template. However, it also tries to detect device-related templates by
looking for these specific templates.

For instance, let's assume that our default template is `DefaultTemplate.phtml`
and that we are using a `tablet device`. In that case our view would look for an existing `DefaultTemplateTablet.phtml`.
If such a template exists it would prefer it over the defined one. Same accounts for `mobile devices`. In that case our view would look for `DefaultTemplateMobile.phtml`.

__Side note:__ a tablet device would also prefer a `mobile template` in case that a `tablet template` is absent.

## 5.2. Building pages

Building pages is quite an important piece since we nest our views: a component has its own view but owns probably
also a couple of sub-views. These sub-views will be `implemented` within the `component views template` as injected variable.
The component view itself will be then implemented within an `app view` or maybe a `session wrapper view`. The principle
is always the same: all lower views wrap the upper views and its up to you how many levels you use.

This process will be handled in the controllers of our component:

```php
protected function buildPage(ViewInterface $view, ComponentViewData $componentViewData, GlobalViewData $globalViewData): ViewInterface
{
    $appContext = $this->getContext()->getAppContext();
    
    $componentView = new AccountsPageView($this->getCoreViewData(), $componentViewData);
    $componentView->implements($view, 'content');
    
    $sessionView = new SessionPageView($this->getCoreViewData(), $appContext->getUserSessionManager()->read());
    $sessionView->implements($componentView, 'content');
    
    $appView = $appContext->getAppPageView($this->getCoreViewData(), $globalViewData);
    $appView->implements($sessionView, 'content');
    
    return $appView;
}
```

You can also see two data classes: `ComponentViewData` and `GlobalViewData`. These are helpers to transpart data to our
component- and app-views since its possible that we have many sub-views within our components. It helps us to structure
and describe our data.

-------------------------------------------------

# 6. Form helper

The core offers a couple of form helper classes to ease and structure the use within an app.
The following paragraphs will show a fulll example of how to use these helpers.

## 6.1. Define form fields

```php
namespace App;

use Simplon\Core\Utils\Form\BaseForm;
use Simplon\Form\Data\FormField;
use Simplon\Form\Data\Rules\RequiredRule;
use Simplon\Form\Data\Rules\EmailRule;

class CreateForm extends BaseForm
{
    const NAME = 'name';
    const EMAIL = 'email';

    /**
     * @return FormField[]
     */
    protected function buildFields(): array
    {
        return [
            $this->getName(),
            $this->getEmail(),
        ];
    }

    /**
     * @return FormField
     */
    private function getName(): FormField
    {
        return (new FormField(self::NAME))->addRule(new RequiredRule());
    }

    /**
     * @return FormField
     */
    private function getEmail(): FormField
    {
        return (new FormField(self::EMAIL))->addRule(new EmailRule());
    }
}
```

## 6.2. Create form view

```php
namespace App;

use App\CreateFormFields;
use Simplon\Core\Utils\Form\BaseFormView;
use Simplon\Form\FormError;
use Simplon\Form\View\Elements\DropDownElement;
use Simplon\Form\View\Elements\InputTextElement;
use Simplon\Form\View\FormViewBlock;
use Simplon\Form\View\FormViewRow;

class CreateFormView extends BaseFormView
{
    /**
     * @return FormViewBlock[]
     * @throws FormError
     */
    protected function getBlocks(): array
    {
        return [
            $this->buildFormViewBlock(self::BLOCK_DEFAULT)
                ->addRow(
                    $this->>buildFormViewRow()
                        ->autoColumns($this->getNameElement())
                        ->autoColumns($this->getEmailElement())
                ),
        ];
    }

    /**
     * @return string
     */
    protected function getSubmitLabel(): string
    {
        return $this->getLocale()->get('form-create-submit-label');
    }

    /**
     * @return InputTextElement
     * @throws FormError
     */
    private function getNameElement(): InputTextElement
    {
        $element = new InputTextElement($this->getFields()->get(CreateFormFields::NAME));

        $element
            ->setLabel($this->getLocale()->get('form-create-name-label'))
            ->setPlaceholder($this->getLocale()->get('form-create-name-placeholder'))
        ;

        return $element;
    }

    /**
     * @return InputTextElement
     * @throws FormError
     */
    private function getEmailElement(): InputTextElement
    {
        $element = new InputTextElement($this->getFields()->get(CreateFormFields::EMAIL));

        $element
            ->setLabel($this->getLocale()->get('form-create-email-label'))
            ->setPlaceholder($this->getLocale()->get('form-create-email-placeholder'))
        ;

        return $element;
    }
}
```

## 6.3. Implement in main view

```php
namespace App;

use Simplon\Core\Utils\Form\ViewWithForm;

class CreateView extends ViewWithForm
{
    /**
     * @return string
     */
    protected function getTemplate(): string
    {
        return __DIR__ . '/CreateTemplate.phtml';
    }
}
```

### 6.3.1. Main template

```php
/**
 * @var Locale $locale
 * @var FlashMessage $flash
 * @var Device $device
 *
 * @var FormView $formView
 */

use App\AppContext;
use Simplon\Core\Views\FlashMessage;
use Simplon\Core\Views\View;
use Simplon\Device\Device;
use Simplon\Form\View\FormView;
use Simplon\Locale\Locale;

?>
<div class="ui grid">
    <div class="sixteen wide column">
        <div class="section-content">
            <?= $formView->render(__DIR__ . '/FormTemplate.phtml', ['locale' => $locale]) ?>
        </div>
    </div>
</div>
```

### 6.3.2. Form template

```php
/**
 * @var Locale $locale
 * @var FormView $formView
 */
use App\CreateFormView;
use Simplon\Form\View\FormView;
use Simplon\Locale\Locale;

?>

<div class="ui basic segment">
    <?= $formView->getBlock(CreateFormView::BLOCK_DEFAULT)->render() ?>
</div>

<?= $formView->getSubmitElement()->renderElement() ?>
```

## 6.4. Controller implementation

```php
namespace App;

use App\CreateForm;
use App\CreateFormView;
use App\CreateView;
use Simplon\Core\Controllers\ViewController;
use Simplon\Core\Utils\Form\FormWrapper;
use Simplon\Core\Data\ResponseViewData;
use Simplon\Form\FormFields;

class CreateViewController extends ViewController
{
    /**
     * @param array $params
     *
     * @return ResponseViewData
     * @throws \Simplon\Form\FormError
     */
    public function __invoke(array $params): ResponseViewData
    {
        $formWrapper = $this->buildFormWrapper(
            new CreateForm($this->getLocale())
        );

        if ($formWrapper->getValidator()->validate()->isValid())
        {
            // do something with the form data
            
            return $this->redirect('/some/other/url');
        }

        $formView = new CreateFormView($this->getLocale(), $formWrapper->getFields());

        return $this->respond(
            new CreateView($this->getCoreViewData(), $formView)
        );
    }
}
```
