<?php

/*
 * This file is part of the Mamba microframework.
 *
 * (c) Mauro Cassani <assistenza@easy-grafica.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app;

use Mamba\Kernel\Kernel;

class Application extends Kernel
{
    /**
     * Load Providers.
     *
     * @return array
     */
    public function getProviders()
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Required Providers
            |--------------------------------------------------------------------------
            */
            'require' => [
                \Silex\Provider\SessionServiceProvider::class => [],
                \Silex\Provider\CsrfServiceProvider::class => [],
                \Silex\Provider\FormServiceProvider::class => [],
                \Silex\Provider\LocaleServiceProvider::class => [],
                \Silex\Provider\ValidatorServiceProvider::class => [],
                \Silex\Provider\TranslationServiceProvider::class => [],
                \Silex\Provider\ServiceControllerServiceProvider::class => [],
                \Silex\Provider\HttpFragmentServiceProvider::class => [],
                \Silex\Provider\HttpCacheServiceProvider::class => [
                    'http_cache.cache_dir' => $this->getCacheDir().'/http',
                ],
                \Silex\Provider\AssetServiceProvider::class => [
                    'assets.version' => @$this['config']['site']['version'],
                    'assets.version_format' => '%2$s/%1$s',
                    'assets.base_urls' => @$this['config']['assets']['base'],
                ],
                \Silex\Provider\DoctrineServiceProvider::class => [
                    'db.options' => [
                        'driver' => $this['config']['database']['driver'],
                        'host' => $this['config']['database']['host'],
                        'dbname' => $this['config']['database']['dbname'],
                        'user' => $this['config']['database']['user'],
                        'password' => $this['config']['database']['password'],
                        'charset' => $this['config']['database']['charset'],
                    ],
                ],
                \Silex\Provider\MonologServiceProvider::class => [
                    'monolog.logfile' => $this->getLogsDir().'/'.$this->env.'.log',
                    'monolog.level' => constant('\Monolog\Logger::'.$this['config']['monolog']['level']),
                    'monolog.name' => $this['config']['monolog']['name'],
                    'monolog.permission' => null,
                    'monolog.bubble' => true,
                ],
                \Silex\Provider\SwiftmailerServiceProvider::class => [
                    'swiftmailer.options' => [
                        'host' => $this['config']['swiftmailer']['host'],
                        'port' => $this['config']['swiftmailer']['port'],
                        'username' => $this['config']['swiftmailer']['username'],
                        'password' => $this['config']['swiftmailer']['password'],
                        'encryption' => $this['config']['swiftmailer']['encryption'],
                        'auth_mode' => $this['config']['swiftmailer']['auth_mode'],
                    ],
                ],
                \Silex\Provider\TwigServiceProvider::class => [
                    'twig.path' => $this->getViewDir(),
                    'twig.options' => [
                        'auto_reload' => $this['debug'],
                        'cache' => $this['debug'] ? false : $this->getCacheDir().'/twig',
                    ],
                ],
                \Mamba\Providers\GuzzleServiceProvider::class => [
                    'guzzle.base_uri' => $this['config']['guzzle']['base_uri'],
                    'guzzle.timeout' => $this['config']['guzzle']['timeout'],
                    'guzzle.debug' => false,
                    'guzzle.request_options' => [
                        'headers' => [
                            'User-Agent' => $this['config']['guzzle']['user-agent'],
                        ],
                    ],
                ],
            ],
            /*
            |--------------------------------------------------------------------------
            | Required Providers (only DEV environment)
            |--------------------------------------------------------------------------
            */
            'require-dev' => [
                \Knp\Provider\ConsoleServiceProvider::class => [
                    'console.name' => $this['config']['console']['name'],
                    'console.version' => $this['config']['console']['version'],
                    'console.project_directory' => __DIR__.'/..',
                ],
                \Silex\Provider\WebProfilerServiceProvider::class => [
                    'profiler.cache_dir' => $this->getCacheDir().'/profiler',
                    'profiler.mount_prefix' => '/_profiler', // this is the default
                ],
            ],
        ];
    }

    /**
     * Load Commands.
     *
     * @return array
     */
    public function getCommands()
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Register your Commands here.
            |--------------------------------------------------------------------------
            */
            \Mamba\Command\RouterCommand::class => [
                $this,
            ],
        ];
    }

    /**
     * Init Application.
     *
     * @return $this
     */
    public function init()
    {
        $this->_setEnv();
        $this->_setDebug();
        $this->_initConfig();
        $this->_initProviders($this->getProviders());
        $this->_initCommands($this->getCommands());
        $this->_initLocale();
        $this->_initRouting();
        $this->_initErrorHandler();

        return $this;
    }
}
