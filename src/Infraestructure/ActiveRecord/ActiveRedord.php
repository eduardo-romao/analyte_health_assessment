<?php

namespace App\Infraestructure\ActiveRecord;

use App\Application\Container\Container;
use Exception;
use InvalidArgumentException;
use PDOStatement;
use ReflectionClass;

abstract class ActiveRedord {

    protected static array $fields = [];
    protected static array $fillable = [];

    public function __set(string $field, mixed $value): void
    {
        $setted = false;
        if (in_array($field, array_keys(static::$fields))) {
            $objFiled = static::$fields[$field];
            $this->$objFiled = $value;
        }

        throw new InvalidArgumentException(
            sprintf(
                'Property [%s] doesn\'t exist.',
                $field
            ),
            500
        );
    }

    abstract static public function getTableName(): string;

    abstract public function getId(): int;

    public static function findAll(): array
    {
        $pdo = Container::getInstance()->get('db');
        $stmt = $pdo->query(
            sprintf(
                "SELECT * FROM %s",
                static::getTableName()
            )
        );

        return array_map(
            fn ($data) => static::dataToModel($data), // Corrigido para usar 'static::'
            $stmt->fetchAll()
        );
    }

    protected static function processCriteria(string $mainQuery, array $criteria): PDOStatement
    {
        $pdo = Container::getInstance()->get('db');
        $model = new static();
        $reflectionClass = new ReflectionClass($model::class);
        $params = [];
        $where = [];
        $index = 0;
        foreach ($criteria as $field => $expr) {
            [$operator, $value] = explode(':', $expr, 2);
            $fieldIndex = ':'.$field.'_'.$index;
            $index++;
            $valueType = null;
            if (isset(static::$fields[$field]) && $reflectionClass->hasProperty(static::$fields[$field])) {
                $reflectionProperty = $reflectionClass->getProperty(static::$fields[$field]);
                $valueType = $reflectionProperty->getType()->getName();
            }
            $params[$fieldIndex] = self::castType($valueType, $value);
            switch ($operator) {
                case 'eq':
                    $where[] = sprintf('%s = %s', $field, $fieldIndex);
                    break;
                case 'neq':
                    $where[] = sprintf('%s <> %s', $field, $fieldIndex);
                    break;
                case 'gt':
                    $where[] = sprintf('%s > %s', $field, $fieldIndex);
                    break;
                case 'gte':
                    $where[] = sprintf('%s >= %s', $field, $fieldIndex);
                    break;
                case 'lt':
                    $where[] = sprintf('%s < %s', $field, $fieldIndex);
                    break;
                case 'lte':
                    $where[] = sprintf('%s <= %s', $field, $fieldIndex);
                    break;
                case 'like':
                    $where[] = sprintf('%s LIKE %s', $field, $fieldIndex);
                    break;
                case 'in':
                    $where[] = sprintf('%s IN (%s)', $field, $fieldIndex);
                    break;
                default:
                    throw new Exception('Unsuported criteria operator', 500);
            }
        }
        if (!empty($where)) {
            $mainQuery .= ' AND ' . implode(' AND ', $where);
        }

        $stmt = $pdo->prepare($mainQuery);

        foreach ($params as $key => &$value) { // Bind by reference
            $stmt->bindParam($key, $value);
        }

        return $stmt;
    }

    public static function search(array $criteria): array
    {
        $stmt = self::processCriteria(
            $query = sprintf(
                "SELECT * FROM %s WHERE 1=1 ",
                static::getTableName()
            ),
            $criteria
        );
        $stmt->execute();
        return array_map(
            fn ($data) => static::dataToModel($data),
            $stmt->fetchAll()
        );
    }

    public static function find(int $id): ?self
    {
        $pdo = Container::getInstance()->get('db');

        $stmt = $pdo->prepare(
            sprintf(
                "SELECT * FROM %s WHERE id = :id",
                static::getTableName()
            )
        );
        $stmt->bindParam('id', $id);
        $stmt->execute();

        $result = $stmt->fetch();

        return $result ? static::dataToModel($result) : null;
    }

    public static function findOneBy(array $criteria): ?self
    {
        $stmt = self::processCriteria(
            $query = sprintf(
                "SELECT * FROM %s WHERE 1=1 ",
                static::getTableName()
            ),
            $criteria
        );
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ? static::dataToModel($result) : null;
    }

    public static function delete(int $id): void
    {
        $pdo = Container::getInstance()->get('db');

        $stmt = $pdo->prepare(
            sprintf(
                "DELETE FROM %s WHERE id = :id",
                static::getTableName()
            )
        );
        $stmt->bindParam('id', $id);
        $stmt->execute();
    }

    public static function deleteBy(array $criteria): void
    {
        $pdo = Container::getInstance()->get('db');
        $query = sprintf(
            'DELETE FROM %s',
            static::getTableName()
        );
        $stmt = self::processCriteria($query, $criteria);
        $stmt->execute();
    }

    protected static function dataToModel(array $data): self
    {
        $model = new static();
        foreach (array_keys($data) as $field) {
            if (in_array($field, array_keys(static::$fields))) {
                $modelField = static::$fields[$field];
                $model->$modelField = $data[$field];
            }
        }

        return $model;
    }

    protected static function castType(?string $type, mixed $value): mixed
    {
        return match ($type) {
            'string' => (string) $value,
            'int' => (int) $value,
            'integer' => (int) $value,
            'float' => (float) $value,
            'double' => (float) $value,
            'bool' => (bool) $value,
            'boolean' => (bool) $value,
            'array' => (array) $value,
            'object' => (object) $value,
            default => $value,
        };
    }

    public function create(): self
    {
        $insertData = [];
        $pdo = Container::getInstance()->get('db');
        $reflectionClass = new ReflectionClass($this::class);
        foreach ($this::$fillable as $field) {
            $dbKey = array_search($field, static::$fields);
            $valueType = null;
            if (isset(static::$fields[$field]) && $reflectionClass->hasProperty(static::$fields[$field])) {
                $reflectionProperty = $reflectionClass->getProperty(static::$fields[$field]);
                $valueType = $reflectionProperty->getType()->getName();
            }
            $insertData[$dbKey] = self::castType($valueType, $this->$field);
        }
        $stmt = $pdo->prepare(
            sprintf(
                "INSERT INTO %s (%s) VALUES (%s)",
                static::getTableName(),
                implode(',', array_keys($insertData)),
                implode(',', array_map(
                    fn ($key) => ':' . $key,
                    array_keys($insertData)
                ))
            )
        );
        foreach ($insertData as $key => $value) {
            $stmt->bindParam(':'.$key, $insertData[$key]);
        }

        $stmt->execute();

        return self::find($pdo->lastInsertId());
    }

    public function update(): self
    {
        $updateData = [];
        $pdo = Container::getInstance()->get('db');
        $reflectionClass = new ReflectionClass($this::class);
        foreach ($this::$fillable as $field) {
            $dbKey = array_search($field, static::$fields);
            $valueType = null;
            if (isset(static::$fields[$field]) && $reflectionClass->hasProperty(static::$fields[$field])) {
                $reflectionProperty = $reflectionClass->getProperty(static::$fields[$field]);
                $valueType = $reflectionProperty->getType()->getName();
            }
            $updateData[$dbKey] = self::castType($valueType, $this->$field);
        }
        $query = sprintf(
            'UPDATE %s SET %s',
            static::getTableName(),
            implode(
                ', ', 
                array_map(
                    fn ($field) => sprintf(
                        '%s = :%s ',
                        $field,
                        $field
                    ),
                    array_keys($updateData)
                )
            )
        );

        $query .= sprintf(
            ' WHERE id = %s',
            $this->getId()
        );
        $stmt = $pdo->prepare($query);
        foreach (array_keys($updateData) as $field) {
            $stmt->bindParam(':'.$field, $updateData[$field]);
        }

        $stmt->execute();

        return $this;
    }

}