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
        $stmt = static::find($conditions);
        return $stmt->fetchObject(static::class);
    }

    public static function findAll($conditions = null)
    {
        $results = static::find($conditions);
        $results = $results->fetchAll();

        $data = [];
        foreach ($results as $result) {
            $record = new static;
            $record->load($result);
            $data[] = $record;
        }

        return $data;
    }

    private static function find($conditions)
    {
        $tableName = static::tableName();
        if(!is_array($conditions) && $conditions !== null) {
            $sql = "SELECT * FROM $tableName WHERE id=:id";
            $stmt = Database::getInstance()->prepare($sql);
            $stmt->bindValue(':id', $conditions);
        } elseif ($conditions !== null) {
            $keys = array_keys($conditions);
            $condition = implode(' AND ', array_map(fn($item) => "$item=:$item", $keys));
            $sql = "SELECT * FROM $tableName WHERE $condition";
            $stmt = Database::getInstance()->prepare($sql);

            foreach ($conditions as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
        } else {
            $sql = "SELECT * FROM $tableName";
            $stmt = Database::getInstance()->query($sql);
        }

        $stmt->execute();

        return $stmt;
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

    public function delete()
    {
        $tableName = static::tableName();
        $primaryKey = static::primaryKey();

        $sql = "DELETE FROM $tableName WHERE $primaryKey=:$primaryKey";
        $stmt = Database::getInstance()->prepare($sql);
        $stmt->execute([":$primaryKey" => $this->$primaryKey]);
    }

    protected function getIsNewRecord(): bool
    {
        return $this->id === 0;
    }
}