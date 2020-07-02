<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use Illuminate\Support\Carbon;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => [
                'daily-debug',
                'daily-info',
                'daily-notice',
                'daily-warning',
                'daily-error',
                'daily-alert',
                'daily-emergency',
            ],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/ewater.log'),
            'level' => 'debug',
        ],

        'daily-debug' => [
            'driver' => 'daily',
            'path' => storage_path('logs/ewater-dbg.log'),
            'level' => 'debug',
            'days' => 1,
        ],

        'daily-info' => [
            'driver' => 'daily',
            'path' => storage_path('logs/ewater-info.log'),
            'level' => 'info',
            'days' => 1,
        ],

        'daily-notice' => [
            'driver' => 'daily',
            'path' => storage_path('logs/ewater-ntc.log'),
            'level' => 'notice',
            'days' => 1,
        ],

        'daily-warning' => [
            'driver' => 'daily',
            'path' => storage_path('logs/ewater-warn.log'),
            'level' => 'warning',
            'days' => 1,
        ],

        'daily-error' => [
            'driver' => 'daily',
            'path' => storage_path('logs/ewater-err.log'),
            'level' => 'error',
            'days' => 1,
        ],

        'daily-critical' => [
            'driver' => 'daily',
            'path' => storage_path('logs/ewater-crit.log'),
            'level' => 'critical',
            'days' => 1,
        ],

        'daily-alert' => [
            'driver' => 'daily',
            'path' => storage_path('logs/ewater-alert.log'),
            'level' => 'alert',
            'days' => 1,
        ],

        'daily-emergency' => [
            'driver' => 'daily',
            'path' => storage_path('logs/ewater-emergency.log'),
            'level' => 'emergency',
            'days' => 1,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],
    ],

];
