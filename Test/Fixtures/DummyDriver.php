<?php
namespace WebStream\Database\Test\Fixtures;

use WebStream\Database\Driver\DatabaseDriver;

class DummyDriver extends DatabaseDriver
{
    public function __construct($container)
    {
    }

    public function connect()
    {
    }
}
