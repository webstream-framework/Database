<?php

namespace WebStream\Database;

use WebStream\Container\Container;
use WebStream\Exception\Extend\ClassNotFoundException;
use WebStream\Exception\Extend\DatabaseException;
use WebStream\IO\File;

/**
 * ConnectionManager
 * @author Ryuichi TANAKA.
 * @since 2014/06/13
 * @version 0.4
 */
class ConnectionManager
{
    /**
     * @var array<string> クラスパス-DSNハッシュマップ
     */
    private array $classpathMap;

    /**
     * @var Container データベース接続項目コンテナ
     */
    private Container $connectionContainer;

    /**
     * constructor
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->initialize($container);
    }

    /**
     * destructor
     */
    public function __destruct()
    {
        unset($this->connectionContainer);
    }

    /**
     * DBコネクションを返却する
     * @param string Modelクラスファイルパス
     * @return DatabaseDriver データベースドライバインスタンス
     */
    public function getConnection($filepath)
    {
        $dsnHash = $this->classpathMap[$filepath];

        return $dsnHash !== null ? $this->connectionContainer->{$dsnHash} : null;
    }

    /**
     * 初期処理
     * @param Container $container
     */
    private function initialize(Container $container)
    {
        $this->classpathMap = [];
        $this->connectionContainer = new Container(false);
        $logger = $container->logger;
        $innerException = null;

        foreach ($container->connectionContainerList as $connectionContainer) {
            $config = null;
            $configFile = new File($connectionContainer->configPath);

            if (!$configFile->exists()) {
                throw new DatabaseException("Database configuration file is not found: " . $configFile->getFilePath());
            }

            $ext = $configFile->getFileExtension();
            if ($ext === 'ini') {
                $config = parse_ini_file($configFile->getFilePath());
            } elseif ($ext === 'yml' || $ext === 'yaml') {
                $config = \Spyc::YAMLLoad($configFile->getFilePath());
            } else {
                throw new DatabaseException("Yaml or ini file only available database configuration file.");
            }

            $driverClassPath = $connectionContainer->driverClassPath;

            if (!class_exists($driverClassPath)) {
                throw new ClassNotFoundException("$driverClassPath is not defined.");
            }

            $dsnHash = "";
            $databaseConfigContainer = new Container(false);
            foreach ($config as $key => $value) {
                $dsnHash .= $key . $value;
                $databaseConfigContainer->set($key, $value);
            }
            $dsnHash = md5($dsnHash);

            $this->classpathMap[$connectionContainer->filepath] = $dsnHash;

            $this->connectionContainer->{$dsnHash} = function () use ($driverClassPath, $databaseConfigContainer, $logger) {
                $driver = new $driverClassPath($databaseConfigContainer);
                $driver->inject('logger', $logger);

                return $driver;
            };
        }
    }
}
