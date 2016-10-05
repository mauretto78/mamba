<?php

/*
 * This file is part of the Mamba microframework.
 *
 * (c) Mauro Cassani <assistenza@easy-grafica.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mamba\Providers;

use App\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ConfigServiceProvider.
 */
class ConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * The config directory.
     *
     * @var string
     */
    private $baseDir;

    /**
     * The yml filename to parse.
     *
     * @var array
     */
    private $configFiles;

    /**
     * @var bool
     */
    private $debug;

    /**
     * ConfigProvider constructor.
     *
     * @param string $baseDir
     * @param array  $configFiles
     * @param bool   $debug
     */
    public function __construct($baseDir, array $configFiles, $debug = true)
    {
        $this->baseDir = $baseDir;
        $this->configFiles = $configFiles;
        $this->debug = $debug;
    }

    /**
     * Register this provider.
     *
     * @param Container $app
     *
     * @throws \Exception if config cache file is generated but is malformed or not readable
     */
    public function register(Container $app)
    {
        $cacheFile = $this->getCacheFilePath($app);

        if ($this->debug || !file_exists($cacheFile)) {
            $configArray = [];
            foreach ($this->configFiles as $filename) {
                $pathFile = $this->baseDir.'/'.$filename;
                if (!file_exists($pathFile)) {
                    throw new \RuntimeException(sprintf('The file "%s" is not found', $pathFile));
                } elseif (!is_readable($pathFile)) {
                    throw new \RuntimeException(sprintf('The file "%s" is not readable', $pathFile));
                }

                $configArray[] = Yaml::parse(file_get_contents($pathFile));
            }

            $config = call_user_func_array('array_replace_recursive', $configArray);

            file_put_contents($cacheFile, serialize($config));
        } else {
            $config = unserialize(file_get_contents($cacheFile));
            if ($config === false) {
                throw new \Exception(sprintf('The config cache file "%s" is malformed or not readable', $cacheFile));
            }
        }

        $app['config'] = $config;
    }

    /**
     * @param Application $app
     *
     * @return string
     */
    private function getCacheFilePath(Application $app)
    {
        return $app->getCacheDir().'/config.cache';
    }
}