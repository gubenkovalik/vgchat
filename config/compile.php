<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Additional Compiled Classes
    |--------------------------------------------------------------------------
    |
    | Here you may specify additional classes to include in the compiled file
    | generated by the `artisan optimize` command. These should be classes
    | that are included on basically every request into the application.
    |
    */

    'files' => [
        'app/Http/Controllers/SiteController.php',
        'app/Http/Controllers/AndroidController.php',
        'app/Http/Controllers/ChatController.php',
        'app/Http/Controllers/FilesController.php',
        'app/Http/Controllers/NodeController.php',
        'app/Http/Controllers/VG.php',
        'app/Http/Middleware/Locale.php',
        'app/Http/Middleware/Online.php',
        'app/Http/Middleware/RedirectIfAuthenticated.php',
        'app/Http/Files.php',
        'app/Http/FileShares.php',
        'app/Http/Helper.php',
        'app/Http/Kernel.php',
        'app/Http/LinkFinder.php',
        'app/Http/Messages.php',
        'app/Http/Resetting.php',
        'app/Http/SimpleImage.php',
        'app/Http/User.php',

    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled File Providers
    |--------------------------------------------------------------------------
    |
    | Here you may list service providers which define a "compiles" function
    | that returns additional files that should be compiled, providing an
    | easy way to get common files from any packages you are utilizing.
    |
    */

    'providers' => [
        //
    ],

];
