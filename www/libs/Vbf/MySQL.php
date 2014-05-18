<?php

/**
 * Class Vbf_MySQL
 */
class Vbf_MySQL extends mysqli
{
    /**
     * Throw an error message to the user and rollback the transaction.
     */
    private function ThrowQueryError($query, $error)
    {
        SatimoPortalPage::ThrowFatalError('MySQL error', $error . '<h2>SQL Query</h2><pre>' . htmlentities($query) . '</pre>');
    }

    /**
     * Throw a false error, usefull to spot problems in queries.
     *
     * @param $query The query who will be displayed
     */
    public function ShowQuery($query)
    {
        SatimoPortalPage::ThrowFatalError('MySQL showQuery', '<pre>' . htmlentities($query) . '</pre>');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function query_fetch_one($query)
    {
        return array_pop($this->query($query)->fetch_row());
    }

    /**
     * @param $query
     * @return array
     */
    public function query_fetch_one_row($query)
    {
        return $this->query($query)->fetch_assoc();
    }

    /**
     * @param string $query
     * @return bool|mysqli_result|null
     */
    public function query($query)
    {
        $result = parent::query($query);
        if ($this->errno) {
            $error = $this->error;
            $this->rollback();
            $this->ThrowQueryError($query, $error);
            return null;
        } else {
            return $result;
        }
    }

    /**
     * @param string $query
     * @return bool|null
     */
    public function real_query($query)
    {

        $result = parent::real_query($query);
        if ($this->errno) {
            $error = $this->error;
            $this->rollback();
            $this->ThrowQueryError($query, $error);
            return null;
        } else {
            return $result;
        }
    }

    /**
     * @param string $query
     * @return bool|null
     */
    public function multi_query($query)
    {
        $result = parent::multi_query($query);
        if ($this->errno) {
            $error = $this->error;
            $this->rollback();
            $this->ThrowQueryError($query, $error);
            return null;
        } else {
            return $result;
        }
    }
}
