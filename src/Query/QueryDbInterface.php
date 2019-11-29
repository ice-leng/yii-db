<?php

namespace Lengbin\YiiDb\Query;

interface QueryDbInterface
{
    public function query(string $sql, array $params = []);

    public function queryOne(string $sql, array $params = []);

    public function execute(string $sql, array $params = []);

    public function beginTransaction();

    public function commit();

    public function rollBack();

    public function lastInsertId($sequenceName = '');

}