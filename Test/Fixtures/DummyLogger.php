<?php
namespace WebStream\Database\Test\Fixtures;

class DummyLogger
{
    public function debug($message)
    {
        echo $message;
    }
}
