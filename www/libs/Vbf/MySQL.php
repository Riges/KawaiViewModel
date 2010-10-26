<?php

class Vbf_MySQL extends mysqli
{
	/**
	 * Throw an error message to the user and rollback the transaction.
	 */
	private function ThrowQueryError($query, $error)
	{
		SatimoPortalPage::ThrowFatalError('MySQL error', $error.'<h2>SQL Query</h2><pre>'.htmlentities($query).'</pre>');
	}
	
	/**
	 * Throw a false error, usefull to spot problems in queries.
	 * 
	 * @param $query The query who will be displayed
	 */
	public function ShowQuery($query)
	{
		SatimoPortalPage::ThrowFatalError('MySQL showQuery', '<pre>'.htmlentities($query).'</pre>');
	}
	
	public function query_fetch_one($query)
	{
	    return array_pop($this->query($query)->fetch_row());
	}
	
	public function query_fetch_one_row($query)
	{
		return $this->query($query)->fetch_assoc();
	}
	
	public function query($query)
	{
		$result = parent::query($query);
		if ($this->errno)
		{
			$error = $this->error;
			$this->rollback();
			$this->ThrowQueryError($query, $error);
			return null;
		}
		else
		{
			return $result;
		}
	}
	
	public function real_query($query)
	{
		
		$result = parent::real_query($query);
		if ($this->errno)
		{
			$error = $this->error;
			$this->rollback();
			$this->ThrowQueryError($query);
			return null;
		}
		else
		{
			return $result;
		}
	}
	
	public function multi_query($query)
	{
		$result = parent::multi_query($query);
		if ($this->errno)
		{
			$error = $this->error;
			$this->rollback();
			$this->ThrowQueryError($query);
			return null;
		}
		else
		{
			return $result;
		}
	}
}

?>
