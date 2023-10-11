<?php
namespace MythicalSystems;
use Symfony\Component\Yaml\Yaml;

class AppConfig
{
    private $config;

    public function __construct()
    {
        $configFilePath = '../config.yml';
        $this->loadConfig($configFilePath);
    }

    public function loadConfig($configFilePath)
    {
        $this->config = Yaml::parseFile($configFilePath);
    }

    public function get($key)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }
        return null; // Return null or throw an exception as appropriate for your use case.
    }

    public function set($key, $value)
    {
        $this->config[$key] = $value;
    }

    public function save($configFilePath)
    {
        file_put_contents($configFilePath, Yaml::dump($this->config));
    }
}