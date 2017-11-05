<?php
namespace WebStream\Database\Test\Providers;

/**
 * ConnectionManagerProvider
 * @author Ryuichi TANAKA.
 * @since 2017/11/05
 * @version 0.7
 */
trait ConnectionManagerProvider
{
    public function connectionProvider()
    {
        return [
            [dirname(__FILE__) . '/../Fixtures/database.ini', 'WebStream\Database\Test\Fixtures\DummyDriver', "key"]
        ];
    }
}
