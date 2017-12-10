<?php
namespace WebStream\Database\Test\Providers;

/**
 * DatabaseProvider
 * @author Ryuichi TANAKA.
 * @since 2017/11/12
 * @version 0.7
 */
trait DatabaseProvider
{
    public function selectProvider()
    {
        return [
            ['SELECT * FROM T_WebStream', [], [['id' => 1, 'name' => 'test1'], ['id' => 2, 'name' => 'test2']]],
            ['SELECT * FROM T_WebStream WHERE id = :id', ['id' => 1], [['id' => 1, 'name' => 'test1']]],
        ];
    }

    public function insertProvider()
    {
        return [
            ['INSERT INTO T_WebStream (name) VALUES (:name)', ['name' => 'test3'], 1]
        ];
    }

    public function updateProvider()
    {
        return [
            ['UPDATE T_WebStream SET name = :name WHERE id = :id', ['id' => 1, 'name' => 'test4'], 1]
        ];
    }

    public function deleteProvider()
    {
        return [
            ['DELETE FROM T_WebStream', 1]
        ];
    }
}
