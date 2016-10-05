<?php

/*
 * This file is part of the Mamba microframework.
 *
 * (c) Mauro Cassani <assistenza@easy-grafica.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

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
            \Silex\Provider\AssetServiceProvider::class => [
                'assets.version' => @$this['config']['site']['version'],
                'assets.version_format' => '%2$s/%1$s',
                'assets.base_urls' => @$this['config']['assets']['cdn'],
            ],
            \Silex\Provider\SessionServiceProvider::class => [],
            \Silex\Provider\CsrfServiceProvider::class => [],
            \Silex\Provider\FormServiceProvider::class => [],
            \Silex\Provider\LocaleServiceProvider::class => [],
            \Silex\Provider\ValidatorServiceProvider::class => [],
            \Silex\Provider\TranslationServiceProvider::class => [],
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
            \Silex\Provider\TwigServiceProvider::class => [
                'twig.path' => $this->getViewDir(),
                'twig.options' => [
                    'auto_reload' => $this['debug'],
                    'cache' => $this['debug'] ? false : $this->getCacheDir().'/twig',
                ],
            ],
            \Knp\Provider\ConsoleServiceProvider::class => [
                'console.name'              => $this['config']['console']['name'],
                'console.version'           => $this['config']['console']['version'],
                'console.project_directory' => __DIR__.'/..'
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
            \Mamba\Command\RouterCommand::class => [$this],
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
