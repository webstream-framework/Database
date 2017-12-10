<?php
namespace WebStream\Database\Test;

require_once dirname(__FILE__) . '/../Modules/IO/File.php';
require_once dirname(__FILE__) . '/../Modules/Container/Container.php';
require_once dirname(__FILE__) . '/../Modules/Container/ValueProxy.php';
require_once dirname(__FILE__) . '/../Modules/DI/Injector.php';
require_once dirname(__FILE__) . '/../Modules/Exception/ApplicationException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Extend/DatabaseException.php';
require_once dirname(__FILE__) . '/../Driver/DatabaseDriver.php';
require_once dirname(__FILE__) . '/../Driver/Mysql.php';
require_once dirname(__FILE__) . '/../ConnectionManager.php';
require_once dirname(__FILE__) . '/../DatabaseManager.php';
require_once dirname(__FILE__) . '/../Query.php';
require_once dirname(__FILE__) . '/../Result.php';
require_once dirname(__FILE__) . '/../ResultEntity.php';
require_once dirname(__FILE__) . '/../EntityManager.php';
require_once dirname(__FILE__) . '/../EntityProperty.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/DummyLogger.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/ResultEntityFixture.php';
require_once dirname(__FILE__) . '/../Test/Providers/DatabaseProvider.php';

use WebStream\Container\Container;
use WebStream\Database\DatabaseManager;
use WebStream\Database\Test\Fixtures\DummyLogger;
use WebStream\Database\Test\Providers\DatabaseProvider;

/**
 * MysqlTest
 */
class DatabaseIntegrationTest extends \PHPUnit\Framework\TestCase
{
    use DatabaseProvider;

    /**
     * 正常系
     * selectが実行できること
     * @test
     * @dataProvider selectProvider
     */
    public function okSelect($sql, $bind, $expect)
    {
        $container = new Container();
        $container->logger = new DummyLogger();
        $config = new Container();
        $config->configPath = dirname(__FILE__) . '/Fixtures/database.mysql.yml';
        $config->driverClassPath = 'WebStream\Database\Driver\Mysql';
        $config->filepath = "test";
        $container->connectionContainerList = [$config];

        $manager = new DatabaseManager($container);
        $manager->loadConnection($config->filepath);
        $manager->connect();
        $actual = $manager->query($sql, $bind)->select()->toArray();
        $manager->disconnect();

        $this->assertArraySubset($expect, $actual);
    }

    /**
     * 正常系
     * selectが実行できること、エンティティにマッピングできること
     * @test
     * @dataProvider selectProvider
     */
    public function okSelectEntity($sql, $bind, $expect)
    {
        $container = new Container();
        $container->logger = new DummyLogger();
        $config = new Container();
        $config->configPath = dirname(__FILE__) . '/Fixtures/database.mysql.yml';
        $config->driverClassPath = 'WebStream\Database\Driver\Mysql';
        $config->filepath = "test";
        $container->connectionContainerList = [$config];

        $manager = new DatabaseManager($container);
        $manager->loadConnection($config->filepath);
        $manager->connect();
        $result = $manager->query($sql, $bind)->select();
        $manager->disconnect();

        $entityList = $result->toEntity('WebStream\Database\Test\Fixtures\ResultEntityFixture');
        foreach ($entityList as $index => $entity) {
            $this->assertEquals($entity->getId(), $expect[$index]['id']);
            $this->assertEquals($entity->getName(), $expect[$index]['name']);
        }
    }

    /**
     * 正常系
     * insertが実行できること
     * @test
     * @dataProvider insertProvider
     */
    public function okInsert($sql, $bind, $expect)
    {
        $container = new Container();
        $container->logger = new DummyLogger();
        $config = new Container();
        $config->configPath = dirname(__FILE__) . '/Fixtures/database.mysql.yml';
        $config->driverClassPath = 'WebStream\Database\Driver\Mysql';
        $config->filepath = "test";
        $container->connectionContainerList = [$config];

        $manager = new DatabaseManager($container);
        $manager->loadConnection($config->filepath);
        $manager->connect();
        $actual = $manager->query($sql, $bind)->insert();
        $manager->disconnect();

        $this->assertEquals($expect, $actual);
    }

    /**
     * 正常系
     * updateが実行できること
     * @test
     * @dataProvider updateProvider
     */
    public function okUpdate($sql, $bind, $expect)
    {
        $container = new Container();
        $container->logger = new DummyLogger();
        $config = new Container();
        $config->configPath = dirname(__FILE__) . '/Fixtures/database.mysql.yml';
        $config->driverClassPath = 'WebStream\Database\Driver\Mysql';
        $config->filepath = "test";
        $container->connectionContainerList = [$config];

        $manager = new DatabaseManager($container);
        $manager->loadConnection($config->filepath);
        $manager->connect();
        $actual = $manager->query($sql, $bind)->update();
        $manager->disconnect();

        $this->assertEquals($expect, $actual);
    }

    /**
     * 正常系
     * deleteが実行できること
     * @test
     * @dataProvider deleteProvider
     */
    public function okDelete($sql)
    {
        $container = new Container();
        $container->logger = new DummyLogger();
        $config = new Container();
        $config->configPath = dirname(__FILE__) . '/Fixtures/database.mysql.yml';
        $config->driverClassPath = 'WebStream\Database\Driver\Mysql';
        $config->filepath = "test";
        $container->connectionContainerList = [$config];

        $manager = new DatabaseManager($container);
        $manager->loadConnection($config->filepath);
        $manager->connect();
        $manager->query($sql)->delete();
        $actual = $manager->query("SELECT * FROM T_WebStream")->select()->toArray();
        $manager->disconnect();

        $this->assertCount(0, $actual);
    }
}
