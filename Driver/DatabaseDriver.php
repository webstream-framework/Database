<?php

namespace WebStream\Database\Driver;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\Driver\DriverStatement;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Statement;
use WebStream\Container\Container;
use WebStream\DI\Injector;

/**
 * DatabaseDriver
 * @author Ryuichi TANAKA.
 * @since 2013/12/07
 * @version 0.4
 */
abstract class DatabaseDriver
{
    use Injector;

    /**
     * @var Connection DBオブジェクト
     */
    protected Connection $connection;

    /**
     * @var Container DB接続設定
     */
    protected Container $config;

    /**
     * constructor
     * @param Container $config
     */
    public function __construct(Container $config)
    {
        $this->config = $config;
    }

    /**
     * destructor
     */
    public function __destruct()
    {
        // $this->logger->debug("Release driver: " . get_class($this));
    }

    /**
     * 接続する
     */
    abstract public function connect();

    /**
     * 切断する
     */
    public function disconnect()
    {
        if ($this->connection !== null) {
            $this->logger->debug("Database disconnect.");
            $this->connection->close();
        }
    }

    /**
     * トランザクションを開始する
     * @return boolean トランザクション開始結果
     */
    public function beginTransaction()
    {
        $inTransaction = false;
        if ($this->connection !== null) {
            $this->connection->beginTransaction();
            $inTransaction = $this->inTransaction();
        }

        return $inTransaction;
    }

    /**
     * コミットする
     * @throws ConnectionException
     */
    public function commit()
    {
        if ($this->connection !== null) {
            $this->connection->commit();
        }
    }

    /**
     * ロールバックする
     * @throws ConnectionException
     */
    public function rollback()
    {
        if ($this->inTransaction()) {
            $this->connection->rollBack();
        }
    }

    /**
     * 自動コミットフラグを設定する
     * @param bool $isAutoCommit 自動コミットフラグ
     */
    public function setAutoCommit(bool $isAutoCommit)
    {
        $this->connection->setAutoCommit($isAutoCommit);
    }

    /**
     * DB接続されているか
     * @return bool 接続有無
     */
    public function isConnected()
    {
        return $this->connection !== null;
    }

    /**
     * トランザクション内かどうか
     * @return bool トランザクション内かどうか
     */
    public function inTransaction()
    {
        return $this->isConnected() ? $this->connection->isTransactionActive() : false;
    }

    /**
     * SQLをセットしてステートメントを返却する
     * @param string SQL
     * @return Statement|null
     * @throws Exception
     */
    public function getStatement(string $sql)
    {
        return $this->isConnected() ? $this->connection->prepare($sql) : null;
    }

    /**
     * トランザクション分離レベルを返却する
     * @return int トランザクション分離レベル
     */
    public function getTransactionIsolation()
    {
        return $this->connection->getTransactionIsolation();
    }

    /**
     * トランザクション分離レベルを設定する
     * @param int $isolationLevel トランザクション分離レベル
     */
    public function setTransactionIsolation(int $isolationLevel)
    {
        $this->connection->setTransactionIsolation($isolationLevel);
    }
}
