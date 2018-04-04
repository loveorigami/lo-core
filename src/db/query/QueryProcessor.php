<?php

namespace lo\core\db\query;

use yii\base\Component;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;

/**
 * Class ArrayQuery
 * @package lo\core\db\query
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class QueryProcessor extends Component
{
    /**
     * @var array
     */
    protected $conditionFilters = [
        'NOT' => 'filterNotCondition',
        'AND' => 'filterAndCondition',
        'OR' => 'filterOrCondition',
        'BETWEEN' => 'filterBetweenCondition',
        'NOT BETWEEN' => 'filterBetweenCondition',
        'IN' => 'filterInCondition',
        'NOT IN' => 'filterInCondition',
        'LIKE' => 'filterLikeCondition',
        'NOT LIKE' => 'filterLikeCondition',
        'OR LIKE' => 'filterLikeCondition',
        'OR NOT LIKE' => 'filterLikeCondition',
        'CALLBACK' => 'filterCallbackCondition',
    ];
    /**
     * @var ArrayQuery
     */
    private $_query;
    /**
     * @param ArrayQuery $query
     *
     * @return array[]
     */
    public function process($query)
    {
        $this->_query = $query;
        $data = $this->_query->from;
        $data = $this->applyWhere($data, $this->_query->where);
        $data = $this->applyOrderBy($data, $this->_query->orderBy);
        $data = $this->applyLimit($data, $this->_query->limit, $this->_query->offset);
        return $data;
    }
    /**
     * Applies sort for given data.
     *
     * @param array $data raw data
     * @param array|null $orderBy order by
     *
     * @return array sorted data
     */
    protected function applyOrderBy(array $data, $orderBy)
    {
        if (!empty($orderBy)) {
            ArrayHelper::multisort($data, array_keys($orderBy), array_values($orderBy));
        }
        return $data;
    }
    /**
     * Applies limit and offset for given data.
     *
     * @param array $data raw data
     * @param int|null $limit limit value
     * @param int|null $offset offset value
     *
     * @return array data
     */
    protected function applyLimit(array $data, $limit, $offset)
    {
        if (empty($limit) && empty($offset)) {
            return $data;
        }
        if (!ctype_digit((string) $limit)) {
            $limit = null;
        }
        if (!ctype_digit((string) $offset)) {
            $offset = 0;
        }
        return array_slice($data, $offset, $limit);
    }
    /**
     * Applies where conditions.
     *
     * @param array $data raw data
     * @param array|null $where where conditions
     *
     * @return array data
     */
    protected function applyWhere(array $data, $where)
    {
        return $this->filterCondition($data, $where);
    }
    /**
     * Applies filter conditions.
     *
     * @param array $data data to be filtered
     * @param array $condition filter condition
     *
     * @return array filtered data
     *
     * @throws InvalidArgumentException
     */
    public function filterCondition(array $data, $condition)
    {
        if (empty($condition)) {
            return $data;
        }
        if (!is_array($condition)) {
            throw new InvalidArgumentException('Condition must be an array');
        }
        if (isset($condition[0])) { // operator format: operator, operand 1, operand 2, ...
            $operator = strtoupper($condition[0]);
            if (isset($this->conditionFilters[$operator])) {
                $method = $this->conditionFilters[$operator];
            } else {
                $method = 'filterSimpleCondition';
            }
            array_shift($condition);
            return $this->$method($data, $operator, $condition);
        } else { // hash format: 'column1' => 'value1', 'column2' => 'value2', ...
            return $this->filterHashCondition($data, $condition);
        }
    }
    /**
     * Applies a condition based on column-value pairs.
     *
     * @param array $data data to be filtered
     * @param array $condition the condition specification
     *
     * @return array filtered data
     */
    public function filterHashCondition(array $data, $condition)
    {
        foreach ($condition as $column => $value) {
            if (is_array($value)) {
                // IN condition
                $data = $this->filterInCondition($data, 'IN', [$column, $value]);
            } else {
                $data = array_filter($data, function ($row) use ($column, $value) {
                    if ($value instanceof \Closure) {
                        return call_user_func($value, $row[$column]);
                    }
                    return $row[$column] == $value;
                });
            }
        }
        return $data;
    }
    /**
     * Applies 2 or more conditions using 'AND' logic.
     *
     * @param array $data data to be filtered
     * @param string $operator operator
     * @param array $operands conditions to be united
     *
     * @return array filtered data
     */
    protected function filterAndCondition(array $data, $operator, $operands)
    {
        foreach ($operands as $operand) {
            if (is_array($operand)) {
                $data = $this->filterCondition($data, $operand);
            }
        }
        return $data;
    }
    /**
     * Applies 2 or more conditions using 'OR' logic.
     *
     * @param array $data data to be filtered
     * @param string $operator operator
     * @param array $operands conditions to be united
     *
     * @return array filtered data
     */
    protected function filterOrCondition(array $data, $operator, $operands)
    {
        $parts = [];
        foreach ($operands as $operand) {
            if (is_array($operand)) {
                $parts[] = $this->filterCondition($data, $operand);
            }
        }
        if (empty($parts)) {
            return $data;
        }
        $data = [];
        foreach ($parts as $part) {
            foreach ($part as $row) {
                $pk = $row[$this->_query->primaryKeyName];
                $data[$pk] = $row;
            }
        }
        return $data;
    }

    /**
     * Inverts a filter condition.
     *
     * @param array $data data to be filtered
     * @param string $operator operator
     * @param array $operands operands to be inverted
     *
     * @return array filtered data
     *
     * @throws InvalidArgumentException if wrong number of operands have been given
     */
    protected function filterNotCondition(array $data, $operator, $operands)
    {
        if (count($operands) != 1) {
            throw new InvalidArgumentException("Operator '$operator' requires exactly one operand.");
        }
        $operand = reset($operands);
        $filteredData = $this->filterCondition($data, $operand);
        if (empty($filteredData)) {
            return $data;
        }
        $pkName = $this->_query->primaryKeyName;
        foreach ($data as $key => $row) {
            foreach ($filteredData as $filteredRowKey => $filteredRow) {
                if ($row[$pkName] === $filteredRow[$pkName]) {
                    unset($data[$key]);
                    unset($filteredData[$filteredRowKey]);
                    break;
                }
            }
        }
        return $data;
    }

    /**
     * Applies `BETWEEN` condition.
     *
     * @param array $data data to be filtered
     * @param string $operator operator
     * @param array $operands the first operand is the column name. The second and third operands
     * describe the interval that column value should be in
     *
     * @return array filtered data
     *
     * @throws InvalidArgumentException if wrong number of operands have been given
     */
    protected function filterBetweenCondition(array $data, $operator, $operands)
    {
        if (!isset($operands[0], $operands[1], $operands[2])) {
            throw new InvalidArgumentException("Operator '$operator' requires three operands.");
        }
        list($column, $value1, $value2) = $operands;
        if (strncmp('NOT', $operator, 3) === 0) {
            return array_filter($data, function ($row) use ($column, $value1, $value2) {
                return $row[$column] < $value1 || $row[$column] > $value2;
            });
        }
        return array_filter($data, function ($row) use ($column, $value1, $value2) {
            return $row[$column] >= $value1 && $row[$column] <= $value2;
        });
    }

    /**
     * Applies 'IN' condition.
     *
     * @param array $data data to be filtered
     * @param string $operator operator
     * @param array $operands the first operand is the column name.
     * The second operand is an array of values that column value should be among
     *
     * @return array filtered data
     *
     * @throws InvalidArgumentException if wrong number of operands have been given
     */
    protected function filterInCondition(array $data, $operator, $operands)
    {
        if (!isset($operands[0], $operands[1])) {
            throw new InvalidArgumentException("Operator '$operator' requires two operands.");
        }
        list($column, $values) = $operands;
        if ($values === [] || $column === []) {
            return $operator === 'IN' ? [] : $data;
        }
        $values = (array) $values;
        if (count($column) > 1) {
            throw new InvalidArgumentException("Operator '$operator' allows only a single column.");
        }
        if (is_array($column)) {
            $column = reset($column);
        }
        foreach ($values as $i => $value) {
            if (is_array($value)) {
                $values[$i] = isset($value[$column]) ? $value[$column] : null;
            }
        }
        if (strncmp('NOT', $operator, 3) === 0) {
            return array_filter($data, function ($row) use ($column, $values) {
                return !in_array($row[$column], $values);
            });
        }
        return array_filter($data, function ($row) use ($column, $values) {
            return in_array($row[$column], $values);
        });
    }

    /**
     * Applies 'LIKE' condition.
     *
     * @param array $data data to be filtered
     * @param string $operator operator
     * @param array $operands the first operand is the column name. The second operand is a single value
     * or an array of values that column value should be compared with
     *
     * @return array filtered data
     *
     * @throws InvalidArgumentException if wrong number of operands have been given
     */
    protected function filterLikeCondition(array $data, $operator, $operands)
    {
        if (!isset($operands[0], $operands[1])) {
            throw new InvalidArgumentException("Operator '$operator' requires two operands.");
        }
        list($column, $values) = $operands;
        if (!is_array($values)) {
            $values = [$values];
        }
        $not = (stripos($operator, 'NOT ') !== false);
        $or = (stripos($operator, 'OR ') !== false);
        if ($not) {
            if (empty($values)) {
                return $data;
            }
            if ($or) {
                return array_filter($data, function ($row) use ($column, $values) {
                    foreach ($values as $value) {
                        if (stripos($row[$column], $value) === false) {
                            return true;
                        }
                    }
                    return false;
                });
            }
            return array_filter($data, function ($row) use ($column, $values) {
                foreach ($values as $value) {
                    if (stripos($row[$column], $value) !== false) {
                        return false;
                    }
                }
                return true;
            });
        }
        if (empty($values)) {
            return [];
        }
        if ($or) {
            return array_filter($data, function ($row) use ($column, $values) {
                foreach ($values as $value) {
                    if (stripos($row[$column], $value) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }
        return array_filter($data, function ($row) use ($column, $values) {
            foreach ($values as $value) {
                if (stripos($row[$column], $value) === false) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * Applies 'CALLBACK' condition.
     *
     * @param array $data data to be filtered
     * @param string $operator operator
     * @param array $operands the only one operand is the PHP callback, which should be compatible with
     * `array_filter()` PHP function, e.g.:
     *
     * ```php
     * function ($row) {
     *     //return bool whether row matches condition or not
     * }
     * ```
     *
     * @return array filtered data
     *
     * @throws InvalidArgumentException if wrong number of operands have been given
     *
     * @since 1.0.3
     */
    public function filterCallbackCondition(array $data, $operator, $operands)
    {
        if (count($operands) != 1) {
            throw new InvalidArgumentException("Operator '$operator' requires exactly one operand.");
        }
        $callback = reset($operands);
        return array_filter($data, $callback);
    }
    /**
     * Applies comparison condition, e.g. `column operator value`.
     *
     * @param array $data data to be filtered
     * @param string $operator operator
     * @param array $operands
     *
     * @return array filtered data
     *
     * @throws InvalidArgumentException if wrong number of operands have been given or operator is not supported
     *
     * @since 1.0.4
     */
    public function filterSimpleCondition(array $data, $operator, $operands)
    {
        if (count($operands) !== 2) {
            throw new InvalidArgumentException("Operator '$operator' requires two operands.");
        }
        list($column, $value) = $operands;
        return array_filter($data, function ($row) use ($operator, $column, $value) {
            switch ($operator) {
                case '=':
                case '==':
                    return $row[$column] == $value;
                case '===':
                    return $row[$column] === $value;
                case '!=':
                case '<>':
                    return $row[$column] != $value;
                case '!==':
                    return $row[$column] !== $value;
                case '>':
                    return $row[$column] > $value;
                case '<':
                    return $row[$column] < $value;
                case '>=':
                    return $row[$column] >= $value;
                case '<=':
                    return $row[$column] <= $value;
                default:
                    throw new InvalidArgumentException("Operator '$operator' is not supported.");
            }
        });
    }
}
