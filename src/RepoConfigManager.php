<?php
/**
 * Created by PhpStorm.
 * User: lorin
 * Date: 17-2-16
 * Time: 上午10:46
 */

namespace LorinLee\LaradockCli\Console;

class RepoConfigManager
{
    const DEFAULT_NAME = 'origin';
    const DEFAULT_REPO = 'git@github.com:laradock/laradock.git';

    /**
     * Get the repo config dir
     *
     * @return string config directory
     */
    public static function getConfigDir()
    {
        return dirname(__FILE__). '/../config';
    }

    /**
     * Get the repo config file
     *
     * @return string config file
     */
    public static function getConfigFile()
    {
        return self::getConfigDir(). '/repo.json';
    }

    /**
     * Check if config dir exists
     *
     * @return boolean
     */
    public static function isConfigDirExists()
    {
        return file_exists(self::getConfigDir());
    }

    /**
     * Check if config file exists
     *
     * @return boolean
     */
    public static function isConfigFileExists()
    {
        return file_exists(self::getConfigFile());
    }

    /**
     * Set repo config
     *
     * @param $name     string name
     * @param $repoSrc  string repo
     * @return boolean  result
     */
    public static function set($name = self::DEFAULT_NAME, $repoSrc = self::DEFAULT_REPO)
    {
        if (! $name) return false;

        if (! self::isConfigDirExists()) {
            mkdir(self::getConfigDir());
        }

        $config = self::getAll();

        if (is_null($config)) $config = [];

        $config[$name] = $repoSrc;

        $configJson = json_encode($config);

        file_put_contents(self::getConfigFile(), $configJson);

        return true;

    }

    /**
     * Get repo config
     *
     * @param string $name
     * @return null
     */
    public static function get($name = self::DEFAULT_NAME)
    {
        if (! $name) return self::getDefaultConfigRepo();

        if (! self::isConfigDirExists() || ! self::isConfigFileExists()) return self::getDefaultConfigRepo();

        $configJson = file_get_contents(self::getConfigFile());

        $config = json_decode($configJson, true);

        if (! $config || ! isset($config[$name])) return self::getDefaultConfigRepo();

        return $config[$name];
    }

    /**
     * Get all config
     *
     * @return array|null
     */
    public static function getAll()
    {
        if (! self::isConfigDirExists() || ! self::isConfigFileExists()) return null;

        $configFile = file_exists(self::getConfigFile());

        $config = json_decode($configFile, true);

        if (! $config) return null;

        return $config;
    }

    public static function getDefaultConfigName()
    {
        return self::DEFAULT_NAME;
    }

    public static function getDefaultConfigRepo()
    {
        return self::DEFAULT_REPO;
    }
}