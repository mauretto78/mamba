<?php

namespace App;

use Mamba\Base\BaseApplication;
use Mamba\Command\DebugDumpCommand;
use Mamba\Providers\BaseControllerServiceProvider;

class Application extends BaseApplication
{
    public function __construct($env)
    {
        parent::__construct($env);

        $this->setRootDir(__DIR__.'/..');
        $this->setConfigDir($this->getRootDir().'/config');
        $this->setCacheDir($this->getRootDir().'/var/cache');
        $this->setLogsDir($this->getRootDir().'/var/logs');
        $this->setViewDir($this->getRootDir().'/src/Resources/views');
        $this->setServerName(@$_SERVER['HTTP_HOST'] ?: 'localhost');
    }

    /**
     * Load config files.
     *
     * @return array
     */
    public function loadConfigFiles()
    {
        return [
            'app.yml',
            'config.yml',
            'parameters.yml',
            'routing.yml',
        ];
    }

    /**
     * Load Providers.
     *
     * @return array
     */
    public function loadProviders()
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
                \Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider::class => [
                    'orm.proxies_dir' => $this->getCacheDir().'/doctrine/proxies',
                    'orm.em.options' => [
                        'mappings' => [
                            [
                                'use_simple_annotation_reader' => false,
                                'type' => 'annotation',
                                'namespace' => 'Mamba\Entity',
                                'path' => __DIR__.'/../src/Entity',
                            ],
                        ],
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
                \Mamba\Providers\ClientServiceProvider::class => [
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
     * Load Extensions.
     *
     * @return array
     */
    public function loadExtensions()
    {
        return [];
    }

    /**
     * Load Commands.
     *
     * @return array
     */
    public function loadCommands()
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Register your Commands here.
            |--------------------------------------------------------------------------
            */
            \Mamba\Command\DebugDumpCommand::class,
            \Mamba\Command\EntityCreateCommand::class,
            \Mamba\Command\EntityDeleteCommand::class,
            \Mamba\Command\FormCreateCommand::class,
            \Mamba\Command\FormDeleteCommand::class,
            \Mamba\Command\RouterDebugCommand::class,
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
        $this->initConfig($this->loadConfigFiles());
        $this->initProviders($this->loadProviders());
        $this->initCommands($this->loadCommands());
        $this->initLocale();
        $this->initRouting();
        $this->initErrorHandler();

        return $this;
    }
}
