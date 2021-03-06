<?php
/**
 * This file is part of the Mesour Selection (http://grid.mesour.com)
 *
 * Copyright (c) 2017 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Selection;

use Mesour;

/**
 * @author Matouš Němec (http://mesour.com)
 */
interface ISelection extends Mesour\Components\ComponentModel\IContainer
{

	public function setItem($id, $status);

	public function setItems(array $items);

	/**
	 * @param string $status
	 * @param string $text
	 * @return mixed
	 */
	public function addStatus($status, $text);

	/**
	 * @return Mesour\UI\DropDown
	 */
	public function getDropDown();

	public function getMainCheckboxPrototype();

	public function createItem($itemId, $data = []);

	public function renderItem($itemId, $data = []);

	public function create($data = []);

}
