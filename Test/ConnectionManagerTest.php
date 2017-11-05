<?php
namespace WebStream\Database\Test;

require_once dirname(__FILE__) . '/../Modules/Container/Container.php';
require_once dirname(__FILE__) . '/../ConnectionManager.php';

use WebStream\Container\Container;
use WebStream\Database\ConnectionManager;

/**
 * ConnectionManagerTest
 * @author Ryuichi TANAKA
 * @since 2017/11/05
 * @version 0.7
 */
class ConnectionManagerTest extends \PHPUnit\Framework\TestCase
{
    public function okDatabase()
    {
        $this->assertTrue(true);
    }
}
