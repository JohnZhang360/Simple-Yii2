<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace zbsoft\db;

use Zb;
use zbsoft\exception\InvalidCallException;
use zbsoft\exception\InvalidConfigException;
use zbsoft\exception\InvalidParamException;
use zbsoft\helpers\ArrayHelper;
use zbsoft\helpers\Inflector;
use zbsoft\helpers\StringHelper;
use zbsoft\UnknownMethodException\UnknownMethodException;

/**
 * ActiveRecord is the base class for classes representing relational data in terms of objects.
 *
 * Active Record implements the [Active Record design pattern](http://en.wikipedia.org/wiki/Active_record).
 * The premise behind Active Record is that an individual [[ActiveRecord]] object is associated with a specific
 * row in a database table. The object's attributes are mapped to the columns of the corresponding table.
 * Referencing an Active Record attribute is equivalent to accessing the corresponding table column for that record.
 *
 * As an example, say that the `Customer` ActiveRecord class is associated with the `customer` table.
 * This would mean that the class's `name` attribute is automatically mapped to the `name` column in `customer` table.
 * Thanks to Active Record, assuming the variable `$customer` is an object of type `Customer`, to get the value of
 * the `name` column for the table row, you can use the expression `$customer->name`.
 * In this example, Active Record is providing an object-oriented interface for accessing data stored in the database.
 * But Active Record provides much more functionality than this.
 *
 * To declare an ActiveRecord class you need to extend [[\zbsoft\db\ActiveRecord]] and
 * implement the `tableName` method:
 *
 * ```php
 * <?php
 *
 * class Customer extends \zbsoft\db\ActiveRecord
 * {
 *     public static function tableName()
 *     {
 *         return 'customer';
 *     }
 * }
 * ```
 *
 * The `tableName` method only has to return the name of the database table associated with the class.
 *
 * > Tip: You may also use the [Gii code generator](guide:start-gii) to generate ActiveRecord classes from your
 * > database tables.
 *
 * Class instances are obtained in one of two ways:
 *
 * * Using the `new` operator to create a new, empty object
 * * Using a method to fetch an existing record (or records) from the database
 *
 * Below is an example showing some typical usage of ActiveRecord:
 *
 * ```php
 * $user = new User();
 * $user->name = 'Qiang';
 * $user->save();  // a new row is inserted into user table
 *
 * // the following will retrieve the user 'CeBe' from the database
 * $user = User::find()->where(['name' => 'CeBe'])->one();
 *
 * // this will get related records from orders table when relation is defined
 * $orders = $user->orders;
 * ```
 *
 * For more details and usage information on ActiveRecord, see the [guide article on ActiveRecord](guide:db-active-record).
 *
 * @property boolean $isNewRecord Whether the record is new and should be inserted when calling [[save()]].
 *
 * @method ActiveQuery hasMany($class, array $link) see [[BaseActiveRecord::hasMany()]] for more info
 * @method ActiveQuery hasOne($class, array $link) see [[BaseActiveRecord::hasOne()]] for more info
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Carsten Brandt <mail@cebe.cc>
 * @since 2.0
 */
class ActiveRecord extends Model
{
    /**
     * The insert operation. This is mainly used when overriding [[transactions()]] to specify which operations are transactional.
     */
    const OP_INSERT = 0x01;
    /**
     * The update operation. This is mainly used when overriding [[transactions()]] to specify which operations are transactional.
     */
    const OP_UPDATE = 0x02;
    /**
     * The delete operation. This is mainly used when overriding [[transactions()]] to specify which operations are transactional.
     */
    const OP_DELETE = 0x04;
    /**
     * All three operations: insert, update, delete.
     * This is a shortcut of the expression: OP_INSERT | OP_UPDATE | OP_DELETE.
     */
    const OP_ALL = 0x07;

    /**
     * @var array attribute values indexed by attribute names
     */
    private $_attributes = [];
    /**
     * @var array|null old attribute values indexed by attribute names.
     * This is `null` if the record [[isNewRecord|is new]].
     */
    private $_oldAttributes;
    /**
     * @var array related models indexed by the relation names
     */
    private $_related = [];

    /**
     * Loads default values from database table schema
     *
     * You may call this method to load default values after creating a new instance:
     *
     * ```php
     * // class Customer extends \zbsoft\db\ActiveRecord
     * $customer = new Customer();
     * $customer->loadDefaultValues();
     * ```
     *
     * @param boolean $skipIfSet whether existing value should be preserved.
     * This will only set defaults for attributes that are `null`.
     * @return $this the model instance itself.
     */
    public function loadDefaultValues($skipIfSet = true)
    {
        foreach (static::getTableSchema()->columns as $column) {
            if ($column->defaultValue !== null && (!$skipIfSet || $this->{$column->name} === null)) {
                $this->{$column->name} = $column->defaultValue;
            }
        }
        return $this;
    }

    /**
     * Returns the database connection used by this AR class.
     * By default, the "db" application component is used as the database connection.
     * You may override this method if you want to use a different database connection.
     * @return Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Zb::$app->getDb();
    }

    /**
     * Creates an [[ActiveQuery]] instance with a given SQL statement.
     *
     * Note that because the SQL statement is already specified, calling additional
     * query modification methods (such as `where()`, `order()`) on the created [[ActiveQuery]]
     * instance will have no effect. However, calling `with()`, `asArray()` or `indexBy()` is
     * still fine.
     *
     * Below is an example:
     *
     * ```php
     * $customers = Customer::findBySql('SELECT * FROM customer')->all();
     * ```
     *
     * @param string $sql the SQL statement to be executed
     * @param array $params parameters to be bound to the SQL statement during execution.
     * @return ActiveQuery the newly created [[ActiveQuery]] instance
     */
    public static function findBySql($sql, $params = [])
    {
        $query = static::find();
        $query->sql = $sql;

        return $query->params($params);
    }

    /**
     * Finds ActiveRecord instance(s) by the given condition.
     * This method is internally called by [[findOne()]] and [[findAll()]].
     * @param mixed $condition please refer to [[findOne()]] for the explanation of this parameter
     * @return ActiveQuery the newly created [[ActiveQuery]] instance.
     * @throws InvalidConfigException if there is no primary key defined
     * @internal
     */
    protected static function findByCondition($condition)
    {
        $query = static::find();

        if (!ArrayHelper::isAssociative($condition)) {
            // query by primary key
            $primaryKey = static::primaryKey();
            if (isset($primaryKey[0])) {
                $pk = $primaryKey[0];
                if (!empty($query->join) || !empty($query->joinWith)) {
                    $pk = static::tableName() . '.' . $pk;
                }
                $condition = [$pk => $condition];
            } else {
                throw new InvalidConfigException('"' . get_called_class() . '" must have a primary key.');
            }
        }

        return $query->andWhere($condition);
    }

    /**
     * Updates the whole table using the provided attribute values and conditions.
     * For example, to change the status to be 1 for all customers whose status is 2:
     *
     * ```php
     * Customer::updateAll(['status' => 1], 'status = 2');
     * ```
     *
     * @param array $attributes attribute values (name-value pairs) to be saved into the table
     * @param string|array $condition the conditions that will be put in the WHERE part of the UPDATE SQL.
     * Please refer to [[Query::where()]] on how to specify this parameter.
     * @param array $params the parameters (name => value) to be bound to the query.
     * @return integer the number of rows updated
     */
    public static function updateAll($attributes, $condition = '', $params = [])
    {
        $command = static::getDb()->createCommand();
        $command->update(static::tableName(), $attributes, $condition, $params);

        return $command->execute();
    }

    /**
     * Updates the whole table using the provided counter changes and conditions.
     * For example, to increment all customers' age by 1,
     *
     * ```php
     * Customer::updateAllCounters(['age' => 1]);
     * ```
     *
     * @param array $counters the counters to be updated (attribute name => increment value).
     * Use negative values if you want to decrement the counters.
     * @param string|array $condition the conditions that will be put in the WHERE part of the UPDATE SQL.
     * Please refer to [[Query::where()]] on how to specify this parameter.
     * @param array $params the parameters (name => value) to be bound to the query.
     * Do not name the parameters as `:bp0`, `:bp1`, etc., because they are used internally by this method.
     * @return integer the number of rows updated
     */
    public static function updateAllCounters($counters, $condition = '', $params = [])
    {
        $n = 0;
        foreach ($counters as $name => $value) {
            $counters[$name] = new Expression("[[$name]]+:bp{$n}", [":bp{$n}" => $value]);
            $n++;
        }
        $command = static::getDb()->createCommand();
        $command->update(static::tableName(), $counters, $condition, $params);

        return $command->execute();
    }

    /**
     * Deletes rows in the table using the provided conditions.
     * WARNING: If you do not specify any condition, this method will delete ALL rows in the table.
     *
     * For example, to delete all customers whose status is 3:
     *
     * ```php
     * Customer::deleteAll('status = 3');
     * ```
     *
     * @param string|array $condition the conditions that will be put in the WHERE part of the DELETE SQL.
     * Please refer to [[Query::where()]] on how to specify this parameter.
     * @param array $params the parameters (name => value) to be bound to the query.
     * @return integer the number of rows deleted
     */
    public static function deleteAll($condition = '', $params = [])
    {
        $command = static::getDb()->createCommand();
        $command->delete(static::tableName(), $condition, $params);

        return $command->execute();
    }

    /**
     * @inheritdoc
     * @return ActiveQuery the newly created [[ActiveQuery]] instance.
     */
    public static function find()
    {
        return Zb::createObject(ActiveQuery::className(), [get_called_class()]);
    }

    /**
     * Declares the name of the database table associated with this AR class.
     * By default this method returns the class name as the table name by calling [[Inflector::camel2id()]]
     * with prefix [[Connection::tablePrefix]]. For example if [[Connection::tablePrefix]] is 'tbl_',
     * 'Customer' becomes 'tbl_customer', and 'OrderItem' becomes 'tbl_order_item'. You may override this method
     * if the table is not named after this convention.
     * @return string the table name
     */
    public static function tableName()
    {
        return '{{%' . Inflector::camel2id(StringHelper::basename(get_called_class()), '_') . '}}';
    }

    /**
     * Returns the schema information of the DB table associated with this AR class.
     * @return TableSchema the schema information of the DB table associated with this AR class.
     * @throws InvalidConfigException if the table for the AR class does not exist.
     */
    public static function getTableSchema()
    {
        $tableSchema = static::getDb()
            ->getSchema()
            ->getTableSchema(static::tableName());

        if ($tableSchema === null) {
            throw new InvalidConfigException('The table does not exist: ' . static::tableName());
        }

        return $tableSchema;
    }

    /**
     * Returns the primary key name(s) for this AR class.
     * The default implementation will return the primary key(s) as declared
     * in the DB table that is associated with this AR class.
     *
     * If the DB table does not declare any primary key, you should override
     * this method to return the attributes that you want to use as primary keys
     * for this AR class.
     *
     * Note that an array should be returned even for a table with single primary key.
     *
     * @return string[] the primary keys of the associated database table.
     */
    public static function primaryKey()
    {
        return static::getTableSchema()->primaryKey;
    }

    /**
     * Returns the list of all attribute names of the model.
     * The default implementation will return all column names of the table associated with this AR class.
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return array_keys(static::getTableSchema()->columns);
    }

    /**
     * Populates an active record object using a row of data from the database/storage.
     *
     * This is an internal method meant to be called to create active record objects after
     * fetching data from the database. It is mainly used by [[ActiveQuery]] to populate
     * the query results into active records.
     *
     * When calling this method manually you should call [[afterFind()]] on the created
     * record to trigger the [[EVENT_AFTER_FIND|afterFind Event]].
     *
     * @param ActiveRecord $record the record to be populated. In most cases this will be an instance
     * created by [[instantiate()]] beforehand.
     * @param array $row attribute values (name => value)
     */
    public static function populateRecord($record, $row)
    {
        $columns = static::getTableSchema()->columns;
        foreach ($row as $name => $value) {
            if (isset($columns[$name])) {
                $row[$name] = $columns[$name]->phpTypecast($value);
            }
        }
        $columns = array_flip($record->attributes());
        foreach ($row as $name => $value) {
            if (isset($columns[$name])) {
                $record->_attributes[$name] = $value;
            } elseif ($record->canSetProperty($name)) {
                $record->$name = $value;
            }
        }
        $record->_oldAttributes = $record->_attributes;
    }

    /**
     * Inserts a row into the associated database table using the attribute values of this record.
     *
     * This method performs the following steps in order:
     *
     * 3. call [[beforeSave()]]. If [[beforeSave()]] returns `false`,
     *    the rest of the steps will be skipped;
     * 4. insert the record into database. If this fails, it will skip the rest of the steps;
     * 5. call [[afterSave()]];
     *
     * Only the [[dirtyAttributes|changed attribute values]] will be inserted into database.
     *
     * If the table's primary key is auto-incremental and is null during insertion,
     * it will be populated with the actual value after insertion.
     *
     * For example, to insert a customer record:
     *
     * ```php
     * $customer = new Customer;
     * $customer->name = $name;
     * $customer->email = $email;
     * $customer->insert();
     * ```
     *
     * @param array $attributes list of attributes that need to be saved. Defaults to null,
     * meaning all attributes that are loaded from DB will be saved.
     * @return boolean whether the attributes are valid and the record is inserted successfully.
     * @throws \Exception in case insert failed.
     */
    public function insert($attributes = null)
    {
        return $this->insertInternal($attributes);
    }

    /**
     * Inserts an ActiveRecord into DB without considering transaction.
     * @param array $attributes list of attributes that need to be saved. Defaults to null,
     * meaning all attributes that are loaded from DB will be saved.
     * @return boolean whether the record is inserted successfully.
     */
    protected function insertInternal($attributes = null)
    {
        $values = $this->getDirtyAttributes($attributes);
        if (($primaryKeys = static::getDb()->schema->insert(static::tableName(), $values)) === false) {
            return false;
        }
        foreach ($primaryKeys as $name => $value) {
            $id = static::getTableSchema()->columns[$name]->phpTypecast($value);
            $this->setAttribute($name, $id);
            $values[$name] = $id;
        }

        $this->setOldAttributes($values);

        return true;
    }


    /**
     * Returns the old attribute values.
     * @return array the old attribute values (name-value pairs)
     */
    public function getOldAttributes()
    {
        return $this->_oldAttributes === null ? [] : $this->_oldAttributes;
    }

    /**
     * Sets the old attribute values.
     * All existing old attribute values will be discarded.
     * @param array|null $values old attribute values to be set.
     * If set to `null` this record is considered to be [[isNewRecord|new]].
     */
    public function setOldAttributes($values)
    {
        $this->_oldAttributes = $values;
    }

    /**
     * Returns the attribute values that have been modified since they are loaded or saved most recently.
     *
     * The comparison of new and old values is made for identical values using `===`.
     *
     * @param string[]|null $names the names of the attributes whose values may be returned if they are
     * changed recently. If null, [[attributes()]] will be used.
     * @return array the changed attribute values (name-value pairs)
     */
    public function getDirtyAttributes($names = null)
    {
        if ($names === null) {
            $names = $this->attributes();
        }
        $names = array_flip($names);
        $attributes = [];
        if ($this->_oldAttributes === null) {
            foreach ($this->_attributes as $name => $value) {
                if (isset($names[$name])) {
                    $attributes[$name] = $value;
                }
            }
        } else {
            foreach ($this->_attributes as $name => $value) {
                if (isset($names[$name]) && (!array_key_exists($name, $this->_oldAttributes) || $value !== $this->_oldAttributes[$name])) {
                    $attributes[$name] = $value;
                }
            }
        }
        return $attributes;
    }

    /**
     * Saves the changes to this active record into the associated database table.
     *
     * This method performs the following steps in order:
     *
     * 3. call [[beforeSave()]]. If [[beforeSave()]] returns `false`,
     *    the rest of the steps will be skipped;
     * 4. save the record into database. If this fails, it will skip the rest of the steps;
     * 5. call [[afterSave()]];
     *
     * Only the [[dirtyAttributes|changed attribute values]] will be saved into database.
     *
     * For example, to update a customer record:
     *
     * ```php
     * $customer = Customer::findOne($id);
     * $customer->name = $name;
     * $customer->email = $email;
     * $customer->update();
     * ```
     *
     * Note that it is possible the update does not affect any row in the table.
     * In this case, this method will return 0. For this reason, you should use the following
     * code to check if update() is successful or not:
     *
     * ```php
     * if ($customer->update() !== false) {
     *     // update successful
     * } else {
     *     // update failed
     * }
     * ```
     *
     * @param array $attributeNames list of attributes that need to be saved. Defaults to null,
     * meaning all attributes that are loaded from DB will be saved.
     * @return integer|boolean the number of rows affected, or false if validation fails
     * or [[beforeSave()]] stops the updating process.
     * @throws StaleObjectException if [[optimisticLock|optimistic locking]] is enabled and the data
     * being updated is outdated.
     * @throws \Exception in case update failed.
     */
    public function update($attributeNames = null)
    {
        return $this->updateInternal($attributeNames);
    }

    /**
     * @see update()
     * @param array $attributes attributes to update
     * @return integer number of rows updated
     * @throws StaleObjectException
     */
    protected function updateInternal($attributes = null)
    {
        $values = $this->getDirtyAttributes($attributes);
        $condition = $this->getOldPrimaryKey(true);
        $lock = $this->optimisticLock();
        if ($lock !== null) {
            $values[$lock] = $this->$lock + 1;
            $condition[$lock] = $this->$lock;
        }
        // We do not check the return value of updateAll() because it's possible
        // that the UPDATE statement doesn't change anything and thus returns 0.
        $rows = static::updateAll($values, $condition);

        if ($lock !== null && !$rows) {
            throw new StaleObjectException('The object being updated is outdated.');
        }

        if (isset($values[$lock])) {
            $this->$lock = $values[$lock];
        }

        $changedAttributes = [];
        foreach ($values as $name => $value) {
            $changedAttributes[$name] = isset($this->_oldAttributes[$name]) ? $this->_oldAttributes[$name] : null;
            $this->_oldAttributes[$name] = $value;
        }

        return $rows;
    }

    /**
     * Deletes the table row corresponding to this active record.
     *
     * This method performs the following steps in order:
     *
     * 1. call [[beforeDelete()]]. If the method returns false, it will skip the
     *    rest of the steps;
     * 2. delete the record from the database;
     * 3. call [[afterDelete()]].
     *
     * In the above step 1 and 3, events named [[EVENT_BEFORE_DELETE]] and [[EVENT_AFTER_DELETE]]
     * will be raised by the corresponding methods.
     *
     * @return integer|false the number of rows deleted, or false if the deletion is unsuccessful for some reason.
     * Note that it is possible the number of rows deleted is 0, even though the deletion execution is successful.
     * @throws StaleObjectException if [[optimisticLock|optimistic locking]] is enabled and the data
     * being deleted is outdated.
     * @throws \Exception in case delete failed.
     */
    public function delete()
    {
        return $this->deleteInternal();
    }

    /**
     * Deletes an ActiveRecord without considering transaction.
     * @return integer|false the number of rows deleted, or false if the deletion is unsuccessful for some reason.
     * Note that it is possible the number of rows deleted is 0, even though the deletion execution is successful.
     * @throws StaleObjectException
     */
    protected function deleteInternal()
    {
        // we do not check the return value of deleteAll() because it's possible
        // the record is already deleted in the database and thus the method will return 0
        $condition = $this->getOldPrimaryKey(true);
        $lock = $this->optimisticLock();
        if ($lock !== null) {
            $condition[$lock] = $this->$lock;
        }
        $result = static::deleteAll($condition);
        if ($lock !== null && !$result) {
            throw new StaleObjectException('The object being deleted is outdated.');
        }
        $this->setOldAttributes(null);

        return $result;
    }

    /**
     * Returns a value indicating whether the given active record is the same as the current one.
     * The comparison is made by comparing the table names and the primary key values of the two active records.
     * If one of the records [[isNewRecord|is new]] they are also considered not equal.
     * @param ActiveRecord $record record to compare to
     * @return boolean whether the two active records refer to the same row in the same database table.
     */
    public function equals($record)
    {
        if ($this->isNewRecord || $record->isNewRecord) {
            return false;
        }

        return static::tableName() === $record->tableName() && $this->getPrimaryKey() === $record->getPrimaryKey();
    }


    /**
     * Returns a value indicating whether the current record is new.
     * @return boolean whether the record is new and should be inserted when calling [[save()]].
     */
    public function getIsNewRecord()
    {
        return $this->_oldAttributes === null;
    }

    /**
     * Sets the value indicating whether the record is new.
     * @param boolean $value whether the record is new and should be inserted when calling [[save()]].
     * @see getIsNewRecord()
     */
    public function setIsNewRecord($value)
    {
        $this->_oldAttributes = $value ? null : $this->_attributes;
    }

    /**
     * Returns the name of the column that stores the lock version for implementing optimistic locking.
     *
     * Optimistic locking allows multiple users to access the same record for edits and avoids
     * potential conflicts. In case when a user attempts to save the record upon some staled data
     * (because another user has modified the data), a [[StaleObjectException]] exception will be thrown,
     * and the update or deletion is skipped.
     *
     * Optimistic locking is only supported by [[update()]] and [[delete()]].
     *
     * To use Optimistic locking:
     *
     * 1. Create a column to store the version number of each row. The column type should be `BIGINT DEFAULT 0`.
     *    Override this method to return the name of this column.
     * 2. Add a `required` validation rule for the version column to ensure the version value is submitted.
     * 3. In the Web form that collects the user input, add a hidden field that stores
     *    the lock version of the recording being updated.
     * 4. In the controller action that does the data updating, try to catch the [[StaleObjectException]]
     *    and implement necessary business logic (e.g. merging the changes, prompting stated data)
     *    to resolve the conflict.
     *
     * @return string the column name that stores the lock version of a table row.
     * If null is returned (default implemented), optimistic locking will not be supported.
     */
    public function optimisticLock()
    {
        return null;
    }

    /**
     * 两个model建立联系
     *
     * 如果通过外键关联,则会给外键赋值,并且会自动保存(不会验证数据),
     * 如果通过中间表关联,则会在中间表中新增一条关联记录
     *
     * Note that this method requires that the primary key value is not null.
     *
     * @param string $name the case sensitive name of the relationship.
     * @param ActiveRecord $model the model to be linked with the current one.
     * @param array $extraColumns additional column values to be saved into the junction table.
     * This parameter is only meaningful for a relationship involving a junction table
     * (i.e., a relation set with [[ActiveRelationTrait::via()]] or [[ActiveQuery::viaTable()]].)
     * @throws InvalidCallException if the method is unable to link two models.
     */
    public function link($name, $model, $extraColumns = [])
    {
        $relation = $this->getRelation($name);

        if ($relation->via !== null) {
            if ($this->getIsNewRecord() || $model->getIsNewRecord()) {
                throw new InvalidCallException('Unable to link models: the models being linked cannot be newly created.');
            }
            if (is_array($relation->via)) {
                /* @var $viaRelation ActiveQuery */
                list($viaName, $viaRelation) = $relation->via;
                $viaClass = $viaRelation->modelClass;
                // unset $viaName so that it can be reloaded to reflect the change
                unset($this->_related[$viaName]);
            } else {
                $viaRelation = $relation->via;
                $viaTable = reset($relation->via->from);
            }
            $columns = [];
            foreach ($viaRelation->link as $a => $b) {
                $columns[$a] = $this->$b;
            }
            foreach ($relation->link as $a => $b) {
                $columns[$b] = $model->$a;
            }
            foreach ($extraColumns as $k => $v) {
                $columns[$k] = $v;
            }
            if (is_array($relation->via)) {
                /* @var $viaClass ActiveRecord */
                /* @var $record ActiveRecord */
                $record = new $viaClass();
                foreach ($columns as $column => $value) {
                    $record->$column = $value;
                }
                $record->insert(false);
            } else {
                /* @var $viaTable string */
                static::getDb()->createCommand()
                    ->insert($viaTable, $columns)->execute();
            }
        } else {
            $p1 = $model->isPrimaryKey(array_keys($relation->link));
            $p2 = static::isPrimaryKey(array_values($relation->link));
            if ($p1 && $p2) {
                if ($this->getIsNewRecord() && $model->getIsNewRecord()) {
                    throw new InvalidCallException('Unable to link models: at most one model can be newly created.');
                } elseif ($this->getIsNewRecord()) {
                    $this->bindModels(array_flip($relation->link), $this, $model);
                } else {
                    $this->bindModels($relation->link, $model, $this);
                }
            } elseif ($p1) {
                $this->bindModels(array_flip($relation->link), $this, $model);
            } elseif ($p2) {
                $this->bindModels($relation->link, $model, $this);
            } else {
                throw new InvalidCallException('Unable to link models: the link defining the relation does not involve any primary key.');
            }
        }

        // update lazily loaded related objects
        if (!$relation->multiple) {
            $this->_related[$name] = $model;
        } elseif (isset($this->_related[$name])) {
            if ($relation->indexBy !== null) {
                if ($relation->indexBy instanceof \Closure) {
                    $index = call_user_func($relation->indexBy, $model);
                } else {
                    $index = $model->{$relation->indexBy};
                }
                $this->_related[$name][$index] = $model;
            } else {
                $this->_related[$name][] = $model;
            }
        }
    }

    /**
     * Destroys the relationship between two models.
     *
     * The model with the foreign key of the relationship will be deleted if `$delete` is true.
     * Otherwise, the foreign key will be set null and the model will be saved without validation.
     *
     * @param string $name the case sensitive name of the relationship.
     * @param ActiveRecord $model the model to be unlinked from the current one.
     * You have to make sure that the model is really related with the current model as this method
     * does not check this.
     * @param boolean $delete whether to delete the model that contains the foreign key.
     * If false, the model's foreign key will be set null and saved.
     * If true, the model containing the foreign key will be deleted.
     * @throws InvalidCallException if the models cannot be unlinked
     */
    public function unlink($name, $model, $delete = false)
    {
        $relation = $this->getRelation($name);

        if ($relation->via !== null) {
            if (is_array($relation->via)) {
                /* @var $viaRelation ActiveQuery */
                list($viaName, $viaRelation) = $relation->via;
                $viaClass = $viaRelation->modelClass;
                unset($this->_related[$viaName]);
            } else {
                $viaRelation = $relation->via;
                $viaTable = reset($relation->via->from);
            }
            $columns = [];
            foreach ($viaRelation->link as $a => $b) {
                $columns[$a] = $this->$b;
            }
            foreach ($relation->link as $a => $b) {
                $columns[$b] = $model->$a;
            }
            $nulls = [];
            foreach (array_keys($columns) as $a) {
                $nulls[$a] = null;
            }
            if (is_array($relation->via)) {
                /* @var $viaClass ActiveRecord */
                if ($delete) {
                    $viaClass::deleteAll($columns);
                } else {
                    $viaClass::updateAll($nulls, $columns);
                }
            } else {
                /* @var $viaTable string */
                /* @var $command Command */
                $command = static::getDb()->createCommand();
                if ($delete) {
                    $command->delete($viaTable, $columns)->execute();
                } else {
                    $command->update($viaTable, $nulls, $columns)->execute();
                }
            }
        } else {
            $p1 = $model->isPrimaryKey(array_keys($relation->link));
            $p2 = static::isPrimaryKey(array_values($relation->link));
            if ($p2) {
                if ($delete) {
                    $model->delete();
                } else {
                    foreach ($relation->link as $a => $b) {
                        $model->$a = null;
                    }
                    $model->save(false);
                }
            } elseif ($p1) {
                foreach ($relation->link as $a => $b) {
                    if (is_array($this->$b)) { // relation via array valued attribute
                        if (($key = array_search($model->$a, $this->$b, false)) !== false) {
                            $values = $this->$b;
                            unset($values[$key]);
                            $this->$b = array_values($values);
                        }
                    } else {
                        $this->$b = null;
                    }
                }
                $delete ? $this->delete() : $this->save(false);
            } else {
                throw new InvalidCallException('Unable to unlink models: the link does not involve any primary key.');
            }
        }

        if (!$relation->multiple) {
            unset($this->_related[$name]);
        } elseif (isset($this->_related[$name])) {
            /* @var $b ActiveRecord */
            foreach ($this->_related[$name] as $a => $b) {
                if ($model->getPrimaryKey() === $b->getPrimaryKey()) {
                    unset($this->_related[$name][$a]);
                }
            }
        }
    }

    /**
     * @param array $link
     * @param ActiveRecord $foreignModel
     * @param ActiveRecord $primaryModel
     * @throws InvalidCallException
     */
    private function bindModels($link, $foreignModel, $primaryModel)
    {
        foreach ($link as $fk => $pk) {
            $value = $primaryModel->$pk;
            if ($value === null) {
                throw new InvalidCallException('Unable to link models: the primary key of ' . get_class($primaryModel) . ' is null.');
            }
            if (is_array($foreignModel->$fk)) { // relation via array valued attribute
                $foreignModel->$fk = array_merge($foreignModel->$fk, [$value]);
            } else {
                $foreignModel->$fk = $value;
            }
        }
        $foreignModel->save(false);
    }

    /**
     * Destroys the relationship in current model.
     *
     * The model with the foreign key of the relationship will be deleted if `$delete` is true.
     * Otherwise, the foreign key will be set null and the model will be saved without validation.
     *
     * Note that to destroy the relationship without removing records make sure your keys can be set to null
     *
     * @param string $name the case sensitive name of the relationship.
     * @param boolean $delete whether to delete the model that contains the foreign key.
     */
    public function unlinkAll($name, $delete = false)
    {
        $relation = $this->getRelation($name);

        if ($relation->via !== null) {
            if (is_array($relation->via)) {
                /* @var $viaRelation ActiveQuery */
                list($viaName, $viaRelation) = $relation->via;
                $viaClass = $viaRelation->modelClass;
                unset($this->_related[$viaName]);
            } else {
                $viaRelation = $relation->via;
                $viaTable = reset($relation->via->from);
            }
            $condition = [];
            $nulls = [];
            foreach ($viaRelation->link as $a => $b) {
                $nulls[$a] = null;
                $condition[$a] = $this->$b;
            }
            if (!empty($viaRelation->where)) {
                $condition = ['and', $condition, $viaRelation->where];
            }
            if (is_array($relation->via)) {
                /* @var $viaClass ActiveRecord */
                if ($delete) {
                    $viaClass::deleteAll($condition);
                } else {
                    $viaClass::updateAll($nulls, $condition);
                }
            } else {
                /* @var $viaTable string */
                /* @var $command Command */
                $command = static::getDb()->createCommand();
                if ($delete) {
                    $command->delete($viaTable, $condition)->execute();
                } else {
                    $command->update($viaTable, $nulls, $condition)->execute();
                }
            }
        } else {
            /* @var $relatedModel ActiveRecord */
            $relatedModel = $relation->modelClass;
            if (!$delete && count($relation->link) === 1 && is_array($this->{$b = reset($relation->link)})) {
                // relation via array valued attribute
                $this->$b = [];
                $this->save(false);
            } else {
                $nulls = [];
                $condition = [];
                foreach ($relation->link as $a => $b) {
                    $nulls[$a] = null;
                    $condition[$a] = $this->$b;
                }
                if (!empty($relation->where)) {
                    $condition = ['and', $condition, $relation->where];
                }
                if ($delete) {
                    $relatedModel::deleteAll($condition);
                } else {
                    $relatedModel::updateAll($nulls, $condition);
                }
            }
        }

        unset($this->_related[$name]);
    }

    /**
     * Saves the current record.
     *
     * This method will call [[insert()]] when [[isNewRecord]] is true, or [[update()]]
     * when [[isNewRecord]] is false.
     *
     * For example, to save a customer record:
     *
     * ```php
     * $customer = new Customer; // or $customer = Customer::findOne($id);
     * $customer->name = $name;
     * $customer->email = $email;
     * $customer->save();
     * ```
     *
     * @param array $attributeNames list of attribute names that need to be saved. Defaults to null,
     * meaning all attributes that are loaded from DB will be saved.
     * @return boolean whether the saving succeeded (i.e. no validation errors occurred).
     */
    public function save($attributeNames = null)
    {
        if ($this->getIsNewRecord()) {
            return $this->insert($attributeNames);
        } else {
            return $this->update($attributeNames) !== false;
        }
    }

    /**
     * Returns the relation object with the specified name.
     * A relation is defined by a getter method which returns an [[ActiveQuery]] object.
     * It can be declared in either the Active Record class itself or one of its behaviors.
     * @param string $name the relation name
     * @param boolean $throwException whether to throw exception if the relation does not exist.
     * @return ActiveQuery the relational query object. If the relation does not exist
     * and `$throwException` is false, null will be returned.
     * @throws InvalidParamException if the named relation does not exist.
     */
    public function getRelation($name, $throwException = true)
    {
        $getter = 'get' . $name;
        try {
            // the relation could be defined in a behavior
            $relation = $this->$getter();
        } catch (UnknownMethodException $e) {
            if ($throwException) {
                throw new InvalidParamException(get_class($this) . ' has no relation named "' . $name . '".', 0, $e);
            } else {
                return null;
            }
        }
        if (!$relation instanceof ActiveQuery) {
            if ($throwException) {
                throw new InvalidParamException(get_class($this) . ' has no relation named "' . $name . '".');
            } else {
                return null;
            }
        }

        if (method_exists($this, $getter)) {
            // relation name is case sensitive, trying to validate it when the relation is defined within this class
            $method = new \ReflectionMethod($this, $getter);
            $realName = lcfirst(substr($method->getName(), 3));
            if ($realName !== $name) {
                if ($throwException) {
                    throw new InvalidParamException('Relation names are case sensitive. ' . get_class($this) . " has a relation named \"$realName\" instead of \"$name\".");
                } else {
                    return null;
                }
            }
        }

        return $relation;
    }

    /**
     * Populates the named relation with the related records.
     * Note that this method does not check if the relation exists or not.
     * @param string $name the relation name (case-sensitive)
     * @param ActiveRecord|array|null $records the related records to be populated into the relation.
     */
    public function populateRelation($name, $records)
    {
        $this->_related[$name] = $records;
    }

    /**
     * Check whether the named relation has been populated with records.
     * @param string $name the relation name (case-sensitive)
     * @return boolean whether relation has been populated with records.
     */
    public function isRelationPopulated($name)
    {
        return array_key_exists($name, $this->_related);
    }

    /**
     * Returns all populated related records.
     * @return array an array of related records indexed by relation names.
     */
    public function getRelatedRecords()
    {
        return $this->_related;
    }

    /**
     * Returns the primary key value(s).
     * @param boolean $asArray whether to return the primary key value as an array. If true,
     * the return value will be an array with column names as keys and column values as values.
     * Note that for composite primary keys, an array will always be returned regardless of this parameter value.
     * @property mixed The primary key value. An array (column name => column value) is returned if
     * the primary key is composite. A string is returned otherwise (null will be returned if
     * the key value is null).
     * @return mixed the primary key value. An array (column name => column value) is returned if the primary key
     * is composite or `$asArray` is true. A string is returned otherwise (null will be returned if
     * the key value is null).
     */
    public function getPrimaryKey($asArray = false)
    {
        $keys = $this->primaryKey();
        if (!$asArray && count($keys) === 1) {
            return isset($this->_attributes[$keys[0]]) ? $this->_attributes[$keys[0]] : null;
        } else {
            $values = [];
            foreach ($keys as $name) {
                $values[$name] = isset($this->_attributes[$name]) ? $this->_attributes[$name] : null;
            }

            return $values;
        }
    }

    /**
     * Returns the old primary key value(s).
     * This refers to the primary key value that is populated into the record
     * after executing a find method (e.g. find(), findOne()).
     * The value remains unchanged even if the primary key attribute is manually assigned with a different value.
     * @param boolean $asArray whether to return the primary key value as an array. If true,
     * the return value will be an array with column name as key and column value as value.
     * If this is false (default), a scalar value will be returned for non-composite primary key.
     * @property mixed The old primary key value. An array (column name => column value) is
     * returned if the primary key is composite. A string is returned otherwise (null will be
     * returned if the key value is null).
     * @return mixed the old primary key value. An array (column name => column value) is returned if the primary key
     * is composite or `$asArray` is true. A string is returned otherwise (null will be returned if
     * the key value is null).
     * @throws Exception if the AR model does not have a primary key
     */
    public function getOldPrimaryKey($asArray = false)
    {
        $keys = $this->primaryKey();
        if (empty($keys)) {
            throw new Exception(get_class($this) . ' does not have a primary key. You should either define a primary key for the corresponding table or override the primaryKey() method.');
        }
        if (!$asArray && count($keys) === 1) {
            return isset($this->_oldAttributes[$keys[0]]) ? $this->_oldAttributes[$keys[0]] : null;
        } else {
            $values = [];
            foreach ($keys as $name) {
                $values[$name] = isset($this->_oldAttributes[$name]) ? $this->_oldAttributes[$name] : null;
            }

            return $values;
        }
    }

    /**
     * Returns a value indicating whether the given set of attributes represents the primary key for this model
     * @param array $keys the set of attributes to check
     * @return boolean whether the given set of attributes represents the primary key for this model
     */
    public static function isPrimaryKey($keys)
    {
        $pks = static::primaryKey();
        if (count($keys) === count($pks)) {
            return count(array_intersect($keys, $pks)) === count($pks);
        } else {
            return false;
        }
    }

    /**
     * Sets the named attribute value.
     * @param string $name the attribute name
     * @param mixed $value the attribute value.
     * @throws InvalidParamException if the named attribute does not exist.
     * @see hasAttribute()
     */
    public function setAttribute($name, $value)
    {
        if ($this->hasAttribute($name)) {
            $this->_attributes[$name] = $value;
        } else {
            throw new InvalidParamException(get_class($this) . ' has no attribute named "' . $name . '".');
        }
    }


    /**
     * Returns a value indicating whether the model has an attribute with the specified name.
     * @param string $name the name of the attribute
     * @return boolean whether the model has an attribute with the specified name.
     */
    public function hasAttribute($name)
    {
        return isset($this->_attributes[$name]) || in_array($name, $this->attributes(), true);
    }

    /**
     * Returns the named attribute value.
     * If this record is the result of a query and the attribute is not loaded,
     * null will be returned.
     * @param string $name the attribute name
     * @return mixed the attribute value. Null if the attribute is not set or does not exist.
     * @see hasAttribute()
     */
    public function getAttribute($name)
    {
        return isset($this->_attributes[$name]) ? $this->_attributes[$name] : null;
    }


    /**
     * PHP getter magic method.
     * This method is overridden so that attributes and related objects can be accessed like properties.
     *
     * @param string $name property name
     * @throws \zbsoft\exception\InvalidParamException if relation name is wrong
     * @return mixed property value
     * @see getAttribute()
     */
    public function __get($name)
    {
        if (isset($this->_attributes[$name]) || array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        } elseif ($this->hasAttribute($name)) {
            return null;
        } else {
            if (isset($this->_related[$name]) || array_key_exists($name, $this->_related)) {
                return $this->_related[$name];
            }
            $value = parent::__get($name);
            if ($value instanceof ActiveQuery) {
                return $this->_related[$name] = $value->findFor($name, $this);
            } else {
                return $value;
            }
        }
    }

    /**
     * PHP setter magic method.
     * This method is overridden so that AR attributes can be accessed like properties.
     * @param string $name property name
     * @param mixed $value property value
     */
    public function __set($name, $value)
    {
        if ($this->hasAttribute($name)) {
            $this->_attributes[$name] = $value;
        } else {
            parent::__set($name, $value);
        }
    }
}