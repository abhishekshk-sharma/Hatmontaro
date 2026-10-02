<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hash Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default hash driver that will be used to hash
    | passwords for your application. By default, the bcrypt algorithm is
    | used; however, you are free to modify this option if you wish.
    |
    | Supported: "bcrypt", "argon", "argon2id"
    |
    */

    'default' => env('HASH_DRIVER', 'bcrypt'),

    /*
    |--------------------------------------------------------------------------
    | Bcrypt Options
    |--------------------------------------------------------------------------
    |
    | Here you may specify the configuration options for the bcrypt algorithm.
    | Setting verify to false allows PHP's native password_verify to check
    | compatible bcrypt hashes (including $2a$ and $2b$) without throwing
    | RuntimeException on non-$2y$ formats.
    |
    */

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 12),
        'verify' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Argon Options
    |--------------------------------------------------------------------------
    |
    | Here you may specify the configuration options for the Argon algorithm.
    |
    */

    'argon' => [
        'memory' => 65536,
        'threads' => 1,
        'time' => 4,
        'verify' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Automatically Rehash Passwords
    |--------------------------------------------------------------------------
    |
    | Here you may specify whether Laravel should automatically rehash
    | passwords when using password-based authentication.
    |
    */

    'rehash_on_login' => true,

];
