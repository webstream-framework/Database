<?php
namespace WebStream\Database\Test;

require_once dirname(__FILE__) . '/../Modules/Container/Container.php';
require_once dirname(__FILE__) . '/../Modules/Container/ValueProxy.php';
require_once dirname(__FILE__) . '/../Modules/DI/Injector.php';
require_once dirname(__FILE__) . '/../Modules/Exception/ApplicationException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Extend/ClassNotFoundException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Extend/DatabaseException.php';
require_once dirname(__FILE__) . '/../Driver/DatabaseDriver.php';
require_once dirname(__FILE__) . '/../ConnectionManager.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/DummyLogger.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/DummyDriver.php';
require_once dirname(__FILE__) . '/../Test/Providers/ConnectionManagerProvider.php';

use WebStream\Container\Container;
use WebStream\Database\ConnectionManager;
use WebStream\Database\Test\Fixtures\DummyLogger;
use WebStream\Database\Test\Fixtures\DummyDriver;
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
    public function okConnectionTest($configPath, $driverClassPath, $filepath)
    {
        $container = new Container();
        $container->logger = new DummyLogger();
        $connectionContainer = new Container();
        $connectionContainer->configPath = $configPath;
        $connectionContainer->driverClassPath = $driverClassPath;
        $connectionContainer->filepath = $filepath;
        $container->connectionContainerList = [$connectionContainer];
        $connectionManager = new ConnectionManager($container);

        $this->assertInstanceOf(DummyDriver::class, $connectionManager->getConnection($filepath));
    }

    /**
     * 異常系
     * 不正な設定ファイルが指定された場合、例外が発生すること
     * @test
     * @expectedException WebStream\Exception\Extend\DatabaseException
     */
    public function ngConfigPathTest()
    {
        $container = new Container();
        $container->logger = new DummyLogger();
        $connectionContainer = new Container();
        $connectionContainer->configPath = "dummy.txt";
        $container->connectionContainerList = [$connectionContainer];
        $connectionManager = new ConnectionManager($container);

        $this->assertTrue(false);
    }

    /**
     * 異常系
     * 不正なドライバクラスパスが指定された場合、例外が発生すること
     * @test
     * @expectedException WebStream\Exception\Extend\ClassNotFoundException
     */
    public function ngInvalidDriverClassPathTest()
    {
        $container = new Container();
        $container->logger = new DummyLogger();
        $connectionContainer = new Container();
        $connectionContainer->configPath = dirname(__FILE__) . '/../Fixtures/database.yml';
        $connectionContainer->driverClassPath = "DummyClass";
        $container->connectionContainerList = [$connectionContainer];
        $connectionManager = new ConnectionManager($container);

        $this->assertTrue(false);
    }
}
