<?php

if (!isset($_ENV['DISABLE_DEFAULT_SERVER']) && class_exists('\Swoole\Constant')) {
    $_ENV['APP_RUNTIME'] = Runtime\Swoole\Runtime::class;
    $_SERVER['APP_RUNTIME_OPTIONS'] = [
        'host'     => '0.0.0.0',
        'port'     => getenv('APP_PORT'),
        'mode'     => SWOOLE_BASE,
        'settings' => [
            \Swoole\Constant::OPTION_WORKER_NUM            => swoole_cpu_num() * 2,
            \Swoole\Constant::OPTION_ENABLE_STATIC_HANDLER => true,
            \Swoole\Constant::OPTION_DOCUMENT_ROOT         => dirname(__DIR__).'/../public',

            // Logging
            'log_level'                  => 1,
            'log_file'                   => dirname(__DIR__).'/../var/swoole.log',
            'log_rotation'               => SWOOLE_LOG_ROTATION_DAILY,
            'log_date_format'            => '%Y-%m-%d %H:%M:%S',
            'log_date_with_microseconds' => false,

            'open_cpu_affinity' => true,

            'input_buffer_size'  => 320 * 1024 * 1024,
            'buffer_output_size' => 320 * 1024 * 1024,

            // Enable trace logs
            'trace_flags' => SWOOLE_TRACE_ALL,
        ],
    ];
    function ddd(mixed ...$data): void
    {
        file_put_contents(__DIR__.'/../../var/log/swoole.log', var_export($data, true), FILE_APPEND);
    }

    function exeOnlyInSwoole(Closure $closure): void
    {
        $closure();
    }
} else {
    function exeOnlyInSwoole(Closure $closure): void
    {
    }
}
