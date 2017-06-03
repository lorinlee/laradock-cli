<?php
/**
 * Created by PhpStorm.
 * User: lorin
 * Date: 17-2-16
 * Time: 上午10:46
 */

namespace LorinLee\LaradockCli\Console;

use Symfony\Component\Console\Exception\RuntimeException;

class RepoConfigManager
{

    const DEFAULT_NAME = 'origin';

    const DEFAULT_REPO = 'git@github.com:laradock/laradock.git';

    /**
     * @return mixed
     * @throws \Symfony\Component\Console\Exception\RuntimeException
     */
    public function getDefaultConfigName()
    {
        return $this->get('config_default');
    }

    /**
     * @return mixed
     * @throws \Symfony\Component\Console\Exception\RuntimeException
     */
    public function getDefaultConfigRepo()
    {
        return $this->get($this->get('config_default'));
    }


    /**
     * Set repo config
     *
     * @param $name     string name
     * @param $repoSrc  string repo
     *
     * @return boolean  result
     *
     * @throws \Symfony\Component\Console\Exception\RuntimeException
     */
    public function setConfig($name, $repoSrc)
    {
        if (empty($name) || empty($repoSrc)) {
            throw new RuntimeException('Tried to set empty configuration');
        }
        $config = $this->getConfigArray();
        $config[$name] = $repoSrc;

        $this->writeConfigToConfigFile($config);
    }

    /**
     * Get repo config
     *
     * @param string $name
     *
     * @return mixed
     * @throws \Symfony\Component\Console\Exception\RuntimeException
     */
    public function get($name)
    {
        $config = $this->getConfigArray();
        if (!isset($config[$name])) {
            throw new RuntimeException('Tried to access an undefined configuration');
        }
        return $config[$name];
    }

    /**
     * @return array
     */
    private function getConfigArray()
    {
        if (!self::configDirectoryExists() || !self::configFileExists()) {
            $this->generateDefaultConfig();
        }

        $configJson = file_get_contents(self::getConfigFilePath());
        return json_decode($configJson, true);
    }

    private function generateDefaultConfig()
    {
        if (!self::configDirectoryExists()) {
            mkdir(self::getConfigFileDirectory());
        }
        $config = [
            self::DEFAULT_NAME => self::DEFAULT_REPO,
            'config_default' => self::DEFAULT_NAME,
            'config_use_default_container' => 'yes',
            'config_submodule' => 'no',
        ];
        $this->writeConfigToConfigFile($config);
    }

    /**
     * @param array $config
     */
    private function writeConfigToConfigFile(array $config)
    {
        file_put_contents(self::getConfigFilePath(), json_encode($config));
    }

    /**
     * Get the repo config dir
     *
     * @return string config directory
     */
    public static function getConfigFileDirectory()
    {
        return dirname(__FILE__) . '/../config';
    }

    /**
     * Get the repo config file
     *
     * @return string config file
     */
    public static function getConfigFilePath()
    {
        return self::getConfigFileDirectory() . '/repo.json';
    }

    /**
     * Check if config dir exists
     *
     * @return boolean
     */
    public static function configDirectoryExists()
    {
        return file_exists(self::getConfigFileDirectory());
    }

    /**
     * Check if config file exists
     *
     * @return boolean
     */
    public static function configFileExists()
    {
        return file_exists(self::getConfigFilePath());
    }
}
