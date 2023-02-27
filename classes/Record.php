<?php

require_once 'RecordInterface.php';
require_once 'Database.php';

/** @property-read bool $isNewRecord */

abstract class Record implements RecordInterface
{
    public static function primaryKey(): string
    {
        return 'id';
    }
    public function __get($name) {
        $m = "get$name";
        if(method_exists($this, $m)) return $this->$m();
    }

    public static function findOne($conditions)
    {
        $tableName = static::tableName();

        if(!is_array($conditions)) {
            $sql = "SELECT * FROM $tableName WHERE id=:id";
            $stmt = Database::getInstance()->prepare($sql);
            $stmt->bindValue(':id', $conditions);
        } else {
            $keys = array_keys($conditions);
            $condition = implode(' AND ', array_map(fn($item) => "$item=:$item", $keys));
            $sql = "SELECT * FROM $tableName WHERE $condition";
            $stmt = Database::getInstance()->prepare($sql);

            foreach ($conditions as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

        }
        $stmt->execute();
        return $stmt->fetchObject(static::class);
    }

    public function save():bool
    {
        $this->beforeSave();
        if($this->isNewRecord) {
            return $this->insert();
        } else {
            return $this->update();
        }
    }

    public function load(array $config)
    {
        foreach ($config as $key => $value) {
            if(property_exists(static::class, $key)) {
                $this->$key = $value;
            }
        }
    }

    abstract public function beforeSave();

    protected function insert(): bool
    {
        $tableName = static::tableName();
        $attributes = $this->attributes();
        unset($attributes[0]);

        $fields = implode(',', $attributes);
        $values = implode(',', array_map(fn($item) => ":$item", $attributes));

        $sql = "INSERT INTO $tableName ($fields) VALUES ($values)";
        $stmt = Database::getInstance()->prepare($sql);

        foreach ($attributes as $attribute) {
            $stmt->bindValue(":$attribute", $this->$attribute);
        }

        return $stmt->execute();
    }

    protected function update(): bool
    {
        $tableName = static::tableName();
        $attributes = $this->attributes();
        $primaryKey = static::primaryKey();
        unset($attributes[0]);

        $values = implode(',', array_map(fn($item) => "$item=:$item", $attributes));

        $sql = "UPDATE $tableName SET $values WHERE $primaryKey=:$primaryKey";
        $stmt = Database::getInstance()->prepare($sql);

        foreach ($attributes as $attribute) {
            $stmt->bindValue(":$attribute", $this->$attribute);
        }

        $stmt->bindValue(":$primaryKey", $this->$primaryKey);

        return $stmt->execute();
    }

    protected function getIsNewRecord(): bool
    {
        return $this->id === 0;
    }
}