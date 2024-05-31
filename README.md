# Installation & Configuration

### Installation

```
composer create-project globalxtreme/laravel-backend-service project
```

### Configuration system in config/base.conf.php

```php
return [

    'prefix' => [

        // Base uri for website application
        'web' => env('BASE_WEB_PREFIX', 'web'),

        // Base uri for mobile application
        'mobile' => env('BASE_MOBILE_PREFIX', 'mobile'),

    ],

    'namespace' => [

        // Base namespace for website application
        'web' => env('BASE_WEB_NAMESPACE', 'Web'),

        // Base namespace for mobile application
        'mobile' => env('BASE_MOBILE_NAMESPACE', 'Mobile'),

    ]

];
  ```

### Authorization
Install [laravel-identifier](https://github.com/globalxtreme/laravel-identifier).
If you don't have access to this package, please contact your head for invite you to this package

### Generate trait for activity properties file in model

```
php artisan make:activity path\ModelClass
```

After success generate activity file, you can check in model directory. Ex: generate activity class for
**App\Models\Component\Example::class.** You can run command like this **"php artisan make:activity
Component\Component"**
will generate activity **trait** file **App\Models\Component\Traits\HasActivityComponentProperty::class.**

### Generate number generator class

```
php artisan make:number ClassName
```

After success generate number generator, you can check in **app\Services\Number\Generator** path.
You can modify the number generator if you have a custom format.
```php
class TestingNumber extends BaseNumber
{
    /**
     * @var string
     */
    protected static string $prefix = "TXT"; // Your prefix number (code)

    /**
     * @var Model|string|null
     */
    protected Model|string|null $model = null;
    
    // Add this function if you have a custom format.
    public static function generate(): string {
        // Your custom format
    }

}
```

### Generate form class (builder)

```
php artisan make:form path\FormClass
```

After generate form class, you can check file in **app\Services\Form** path. Ex: generate form for component, you can
run command like this **php artisan make:form Component\ComponentForm**. You can check file in **app\Services\Form\Component\ComponentForm.php.**

### Generate constant class (for component)

```
// Generate basic constant for ID(int) and Name(string)
php artisan make:constant path\ConstantClass

// Generate constant for Code(string) and Name(string)
php artisan make:constant path\ConstantClass -c
```

After generate constant class with command, you can check class in **app\Services\Constant\Path\ConstantClass.php.**

### Generate parser class

```
php artisan make:parser path\ParserClass
```

After generate parser class, you can check parser class in **app\Services\Parser\Path\ParserClass.php.**
