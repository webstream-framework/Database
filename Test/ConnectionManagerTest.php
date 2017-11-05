<?php
namespace WebStream\Database\Test;

require_once dirname(__FILE__) . '/../Modules/Container/Container.php';
require_once dirname(__FILE__) . '/../Modules/Container/ValueProxy.php';
require_once dirname(__FILE__) . '/../Driver/DatabaseDriver.php';
require_once dirname(__FILE__) . '/../ConnectionManager.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/DummyLogger.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/DummyDriver.php';
require_once dirname(__FILE__) . '/../Test/Providers/ConnectionManagerProvider.php';

use WebStream\Container\Container;
use WebStream\Database\Test\Fixtures\DummyLogger;
use WebStream\Database\ConnectionManager;
use WebStream\Database\Test\Providers\ConnectionManagerProvider;

/**
 * ConnectionManagerTest
 * @author Ryuichi TANAKA
 * @since 2017/11/05
 * @version 0.7
 */
class ConnectionManagerTest extends \PHPUnit\Framework\TestCase
{
    use ConnectionManagerProvider;

    /**
     * 正常系
     * データベース接続情報が取得できること
     * @test
     * @dataProvider connectionProvider
     */
    public function okConnectionTest($configPath, $driverClassPath, $filepath, $connectionKey)
    {
        $container = new Container();
        $container->logger = new DummyLogger();
        $connectionContainer = new Container();
        $connectionContainer->configPath = $configPath;
        $connectionContainer->driverClassPath = $driverClassPath;
        $connectionContainer->filepath = $filepath;
        $container->connectionContainerList = [$connectionContainer];
        $connectionManager = new ConnectionManager($container);

        var_dump($connectionManager->getConnection("key"));


        $this->assertTrue(true);
    }
}
