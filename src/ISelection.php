<?php
/**
 * Mesour Selection Component
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <http://mesour.com>
 */

namespace Mesour\UI;

/**
 * @author mesour <http://mesour.com>
 * @package Mesour Selection Component
 */
interface ISelection
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
