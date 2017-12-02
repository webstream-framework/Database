<?php
namespace WebStream\Database\Test;

require_once dirname(__FILE__) . '/../Modules/Container/Container.php';
require_once dirname(__FILE__) . '/../Modules/Container/ValueProxy.php';
require_once dirname(__FILE__) . '/../Modules/DI/Injector.php';
require_once dirname(__FILE__) . '/../Driver/DatabaseDriver.php';
require_once dirname(__FILE__) . '/../Driver/Mysql.php';
require_once dirname(__FILE__) . '/../ConnectionManager.php';
require_once dirname(__FILE__) . '/../DatabaseManager.php';
require_once dirname(__FILE__) . '/../Query.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/DummyLogger.php';

use WebStream\Container\Container;
use WebStream\Database\DatabaseManager;
use WebStream\Database\Test\Fixtures\DummyLogger;

/**
 * MysqlTest
 */
class MysqlTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function test()
    {
        $container = new Container();
        $container->logger = new DummyLogger();
        $config = new Container();
        $config->configPath = dirname(__FILE__) . '/Fixtures/database2.mysql.yml';
        $config->driverClassPath = 'WebStream\Database\Driver\Mysql';
        $config->filepath = "test";

        $container->connectionContainerList = [$config];

        $manager = new DatabaseManager($container);
        $manager->loadConnection($config->filepath);
        $manager->connect();
        $result = $manager->query("select * from T_WebStream")->select();
        var_dump($result);

        $this->assertTrue(true);
    }
}
