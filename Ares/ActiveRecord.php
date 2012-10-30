<?php

namespace Ares;

class ActiveRecord {

	private $select = '',
	        $table  = '',
	        $where  = '',
	        $order  = '',
	        $limit  = '',
	        $params = array();

	function __construct($model) {
		if (Config::$env != 'production') {
			try {
				// Create the table if it does not exist
				Database::checkTable($model);

			} catch (Exception $e) {
				echo $e->getMessage();
				exit;
			}
		}
	}

	/**
	 * Add a where clause to the ActiveRecord
	 *
	 * examples
	 *     ->where('foo = ?', 'bar');
	 *     ->where('foo = ? AND bar = ?', 1, 'yes');
	 *     ->where('foo = ? AND (bar = ? OR bar = ?)', 1, 'yes', 'maybe');
	 * 
	 * @return ActiveRecord $this
	 */
	function where() {

		// Get the args
		$nargs = func_num_args();
		$args  = func_get_args();

		// Get the query string
		$query = array_shift($args);

		if ($query == 'AND' or $query == 'OR') {
			$join = $query;
			$query = array_shift($args);
		}

		// Check count of ? to params
		$count_q = substr_count($query, '?');

		if ($count_q != $nargs - 1) {
			throw Exception\ActiveRecordException("Incorrect number of arguments in: '".$query."', 
				'".implode(',', $args)."'");
		}

		if ($this->where == '') {
			$this->where = "WHERE";
		} else {
			$this->where .= $join;
		}

		$this->where .= $query;
		$this->params = array_merge($this->params, $args);
	}

	function andWhere() {
		$this->where('AND');
	}

	function orWhere() {
		$this->where('OR');
	}

	function order($order) {
		$this->order = $order;
	}

	function limit($limit) {
		$this->limit = $limit;
	}

}