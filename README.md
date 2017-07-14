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
2. [__Bootstrap__](#2-bootstrap)  
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

# 2. Bootstrap

Our bootstrap holds all registered components, middlewares and kicks-off the core. The an example taken
from the [skeleton repo](https://github.com/fightbulc/simplon_core_skeleton):

```php
//
// enforce typed
//

declare(strict_types=1);

use App\AppContext;
use App\Components\Contents\ContentsRegistry;
use App\Components\Simple\SimpleRegistry;
use Simplon\Core\Core;
use Simplon\Core\Middleware\ExceptionMiddleware;
use Simplon\Core\Middleware\LocaleMiddleware;
use Simplon\Core\Middleware\RouteMiddleware;

//
// loads optimised composer when getenv('APP_ENV') !== 'dev'
//

require __DIR__ . '/../vendor/simplon/core/src/autoload.php';

//
// instantiate AppContext
//

$appContext = new AppContext();

//
// components queue
//

$components = [
    new ContentsRegistry($appContext),
    new SimpleRegistry($appContext),
];

//
// middleware queue
//

$middleware = [
    new ExceptionMiddleware(),
    new LocaleMiddleware(),
    new RouteMiddleware($components),
];

//
// run core with app data
//

(new Core())->withSession(60)->run($middleware);
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

AuthMiddleware aids authentication against certain `routes`, `user roles` and `temporary tokens`. It requires the
an `AuthContainer` object which holds all required data. Here is an example combined with the middleware queue:

```php
//
// Auth Config
//

$authConfig = new AuthContainer(
	$appContext->getSessionStorage(), // storage for our auth data
	new UserSessionData(),            // object for a users auth data
	AuthRoutes::toSignIn()            // where to send the user if auth failed
);

// handle a recognised temporary token in your request e.g. ?token=ABCD1234
// is used to give one-time access to the app for 3rd-party services such as facebook

$authConfig->setCallbackVerifyToken(
	function(string $token)
	{
		// check token if valid and
		// return bool to indicate result
	}
);

// load defined authenticated routes from registries

$authConfig->loadRoutesFromComponents($components);

//
// middleware queue
//

$middleware = [
    new ExceptionMiddleware(),
    new LocaleMiddleware(),
    new AuthMiddleware($authConfig),
    new RouteMiddleware($components),
];
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

class CreateFormFields extends BaseForm
{
    const NAME = 'name';
    const EMAIL = 'email';

    /**
     * @return FormField[]
     */
    protected function getFields(): array
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
    const BLOCK_DEFAULT = 'default';

    /**
     * @return string
     */
    protected function getUrl(): string
    {
        return '/your/url';
    }

    /**
     * @return FormViewBlock[]
     * @throws FormError
     */
    protected function getBlocks(): array
    {
        return [
            (new FormViewBlock(self::BLOCK_DEFAULT))
                ->addRow(
                    (new FormViewRow())
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

use Simplon\Core\Utils\Form\FormViewInterface;
use Simplon\Core\Data\CoreViewData;
use Simplon\Core\Views\View;

class CreateView extends View
{
    /**
     * @var FormViewInterface
     */
    private $formView;

    /**
     * @param CoreViewData $coreViewData
     * @param FormViewInterface $formView
     */
    public function __construct(CoreViewData $coreViewData, FormViewInterface $formView)
    {
        parent::__construct($coreViewData);
        $this->addFormAssets($formView->getView());
        $this->formView = $formView;
    }

    /**
     * @return string
     */
    protected function getTemplate(): string
    {
        return __DIR__ . '/CreateTemplate.phtml';
    }

    /**
     * @return array
     */
    protected function getData(): array
    {
        return [
            'formView' => $this->formView->getView(),
        ];
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

use App\CreateFormFields;
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
        $requestData = $this->getRequest()->getParsedBody();
        $formFields = (new CreateFormFields($this->getLocale()))->getFormFields();
        $formWrapper = new FormWrapper($formFields, $requestData);

        if ($formWrapper->getValidator()->validate()->isValid())
        {
            // do something with the form data
            
            return $this->redirect('/some/other/url');
        }

        $formView = new CreateFormView($this->getLocale(), $formFields);

        return $this->respond(
            new CreateView($this->getCoreViewData(), $formView)
        );
    }
}
```
