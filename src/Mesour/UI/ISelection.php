<?php
/**
 * This file is part of the Mesour Selection (http://grid.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\UI;

use Mesour\Components\IContainer;

/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
interface ISelection extends IContainer
{

    public function setItem($id, $status);

    public function setItems(array $items);

    /**
     * @param $status
     * @param $text
     * @return mixed
     */
    public function addStatus($status, $text);

    /**
     * @return DropDown
     */
    public function getDropDown();

    public function getMainCheckboxPrototype();

    public function createItem($item_id, $data = array());

    public function renderItem($item_id, $data = array());

    public function create($data = array());

}
