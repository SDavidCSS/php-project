<?php

interface RecordInterface
{
    public function save();
    public static function findOne($condition);
    public function load(array $config);
    public function attributes(): array;
    public static function tableName(): string;
    public static function primaryKey(): string;
}