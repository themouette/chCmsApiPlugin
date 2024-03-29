# chCmsApiPlugin

This plugin allows *quick* and *easy API development*.

It provides an action class with built-in methods for API needs, response
and data formatters to ease objects export, parameter parsers to ease data
fetching, validation and mapping.

Generators are provided too, and each of them generates it's own unit test file
so you can organize your code and focus on what matters.

Oh, and yes, your API will *automatically publish it's own documentation* by
introspecting your routes, validators and comments!


# Installation

## Retrieving the code

Clone the plugin from Github:

    git clone git://github.com/Carpe-Hora/chCmsApiPlugin.git plugins/chCmsApiPlugin

Or even better, add it to your submodules

    git submodule add git://github.com/Carpe-Hora/chCmsApiPlugin.git plugins/chCmsApiPlugin

## Enabling the plugin

to install this plugin, just enable it in your project :

```php
<?php
// config/ProjectConfiguration.class.php
//...
public function initialize()
{
  $this->eneablePlugins(
    // ...
    'chCmsApiPlugin',
    // ...
  );
}
//...
```

And add the filters to your application:

```yml
# app/api/config/filters.yml

all:
  # ...
  Api:
    class: chCmsApiFilter

  # ...

  security:  ~

  ApiParams:
    class: chCmsApiValidateParamFilter

  # ...
```

You're done.


# Use

## Create an API function

### register routing

Create a new route as usual (use improved [TaskExtraPlugin](https://github.com/Carpe-Hora/sfTaskExtraPlugin) to generate plugin)

<<<<<<< HEAD
```php
<?php
// plugins/myApiPlugin/lib/routing/myApiPluginRouting.class.php
public static function registerMyApiRoutes($routing)
{
  $routing->prependRoute('my_first_api_route',
    new sfRequestRoute(
      '/api/my/function/:required.sf_format',
      // default values
      array(
        'module'    => 'myApiModule',
        'action'    => 'myApiAction',
        'sf_method' => chCmsApiTools::getDefaultFormat()
      ),
      // requirements
      array(
        'required' => "\w+",
        'sf_method' => chCmsApiTools::getFormatRequirementForRoute(array(
            'my_extra_format'
          )),
        'sf_method' => array('POST', 'PUT')
      ),
      // options
      array(
        'comment'         => 'A really cool API method.',
        //'public_api'    => false, // uncomment id to hide if from the methods list
        'param_validator' => 'myApiParamValidator',
        'param_validator_args' => array(
        'option_name' => 'option_value'
      ))
    )
  );
}
```

Noticed the weird param_validator option ?
It means that request parameters will go through this param validator
cleaning process.

Param validators is a simple way to translate, preprocess and validate
parameters. Let's go further.


### Param Validators

Every API request is about recieving data and return result depending on those
paramters.
To ensure parameter consistancy, chCmsApiPlugin provide ParamValidators.

Note:   forms can be used either. let's say that ParamValidators are just as
        forms but with extra functionalities.

first of all, let's generate the request param validator :

> ./symfony chCms:generate-param-validator --plugin=myApiPlugin myApi

Will generate *myApiParamValidator* class in the lib/param folder and a unit
test file for this param validator.

Note:   in plugin context a Plugin class is generated too.

You configure it in the setup method, just as forms.

```php
<?php
// plugins/myApiPlugin/lib/param/plugin/PluginMyApiParamValidator.class.php
public function setup()
{
  // to validate a page number
  $this->setValidator('page',
    new chCmsPageParamValidator(array(
      'max' => $this->getOption('max_page', null)
    ))
  );

  // you can access user directly
  if (!$this->getUser()->isAuthenticated())
  {
    $this->setValidator('email', new sfValidatorEmail());
  }
  else
  {
    $this->setDefault('email', $this->getUser()->getEmail());
  }

  // and even post validate
  $this->mergePostValidator(new sfValidatorCallback(array(
    'callback' => array($this, 'myRequestParameterFilterMethod')
  )));
  // this required you to implement myRequestParameterFilterMethod
  // with prototype :
  // public function myRequestParameterFilterMethod($validator, $value, $args)
  }
```

Now, when @my_first_api_route will be requested, thanks to the *chCmsApiFilter*,
parameters will go through *myApiParamValidator* and directly set into the
request object.

Note :  to access original parameters, you can use *getOriginalApiParameters*
        $sf_request method.

### Formatting output

The real pain when creating an API is the response formatting. Most of the time
You don't care about the result format, you only deal with collections, active
record objects, arrays and scalar values.

That's what you do, dealing with your business logic, no matter the output
format, formatters are there for you. It is achieved in 2 steps :

* format data with *Data Formatters*
* format response with *Response Formatter*

To use *Response Formatter*, nothing to do except declare *sf_format* parameter
in, your route.
To declare a new output format, extend the sfResponse class with a
*formatApiResultXxx($result, $request)* where Xxx is your format.

Note:   to extend a sfResponse use the "response.method_not_found" event.

A *Data Formatter* translates anything into a format that can be used by the
*Response Formatter*.

Eventhough you can define formatters on the fly, it is a good practice to
generate a formatter to use it.

> ./symfony chCms:generate-formatter --plugin=myApiPlugin myApi

Will generate *myApiFormatter* extending *chCmsApiObjectFormatter* and a test
file.

To specialize the formatter, specialize the initialize method:

```php
<?php
// plugins/myApiPlugin/lib/formatter/plugin/PluginMyApiFormatter.class.php
public function initialize()
{
  $this->setDefaultFormatFields(array(
    // put the slug (using getSlug) property into api_id
    'api_id'      => new chCmsApiPropertyFormatter('slug'),

    // set collection property with getSubobj collection formatted
    // with myCustomFormatter
    'collection'  => new chCmsApiCollectionPropertyFormatter('subobj',
      new myCustomFormatter()
    ),

    // call *getBar* to initialize foo property
    'foo'         => 'bar',

    // simply translate baz to the result object
    'baz'
  ));
}
```

A bunch of formatters is bundeled with the plugin, such as:

* *chCmsApiObjectFormatter* : to format an object
* *chCmsApiPagerFormatter* : to format a PropelPager
* *chCmsApiPassFormatter* : to return raw result
* *chCmsApiCollectionFormatter* : to format a collection
* *chCmsApiArrayFormatter* : to format an array

To use a formatter, just call format method.
for objects, you can also call

* *formatObject* (alias to format)
* *formatCollection* to format array or PropelCollection
* *formatPager* to format a PropelPager

Here is some sample code

```php
<?php
$myApiObject = myApiOjectQuery::create()->findOneById(10);
$f = new myApiFormatter();
$f->format($myApiObject);

// add extra fields
$f = new myApiFormatter();
$f->format($myApiObject, array(
  'my_extra_field' // include my_extra_field property
));
$f->format($myApiObject);     // no more include my_extra_field property
$f->mergeFormatFields(array('my_extra_field'));
$f->format($myApiObject);     // include my_extra_field property

// force properties
$f = new myApiFormatter(array('my_extra_field'));
$f->format($myApiObject);   // only include my_extra_field property
```

let's see how to put it all together.

### Controller

extend from chCmsApiActions

use the $this->renderApi($formatted_result);

## Generators

there is a validator class generator.
