<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */
namespace Slim\PDO\Statement;

use Slim\PDO\AbstractStatement;
use Slim\PDO\Database;

/**
 * Class InsertStatement.
 *
 * @author Fabian de Laender <fabian@faapz.nl>
 */
class Insert extends AbstractStatement
{
    /**
     * Constructor.
     *
     * @param Database $dbh
     * @param array    $columns
     */
    public function __construct(Database $dbh, array $columns = [])
    {
        parent::__construct($dbh);

        $this->columns($columns);
    }

    /**
     * @param $table
     *
     * @return $this
     */
    public function into($table)
    {
        $this->setTable($table);

        return $this;
    }

    /**
     * @param array $columns
     *
     * @return $this
     */
    public function columns(array $columns)
    {
        $this->setColumns($columns);

        return $this;
    }

    /**
     * @param array $values
     *
     * @return $this
     */
    public function values(array $values)
    {
        $this->setValues($values);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if (empty($this->table)) {
            trigger_error("No table is set for insertion", E_USER_ERROR);
        }

        if (empty($this->columns)) {
            trigger_error("Missing columns for insertion", E_USER_ERROR);
        }

        if (empty($this->values)) {
            trigger_error("Missing values for insertion", E_USER_ERROR);
        }

        $columns = "( " . implode(", ", $this->columns) . " )";
        $placeholders = rtrim(str_repeat("?, ", $this->values), ", ");

        $sql = "INSERT INTO {$this->table} ({$columns})";
        $sql .= " VALUES ({$placeholders})";

        return $sql;
    }

    /**
     * @return string
     */
    public function execute()
    {
        parent::execute();

        return $this->dbh->lastInsertId();
    }
}
