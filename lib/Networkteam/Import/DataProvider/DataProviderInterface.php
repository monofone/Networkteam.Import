<?php

namespace Networkteam\Import\DataProvider;

/***************************************************************
 *  (c) 2014 networkteam GmbH - all rights reserved
 ***************************************************************/

interface DataProviderInterface extends \Iterator {

	/**
	 * @return array The current import item as an associative array (key / value)
	 */
	public function current();

	/**
	 * @throws \Networkteam\Import\Exception
	 */
	public function open();

	/**
	 * @throws \Networkteam\Import\Exception
	 */
	public function close();

	/**
	 * @param array $options
	 */
	public function setOptions(array $options);

}
