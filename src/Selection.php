<?php
/**
 * Mesour Selection Component
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\UI;

use Mesour\Components;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Selection Component
 */
class Selection extends Control implements ISelection
{

    const ITEMS = 'items',
        DROP_DOWN = 'drop-down',
        MAIN = 'main';

    /**
     * Array of items ID => string|array (statuses)
     * @var array
     */
    protected $items = array();

    protected $option = array();

    /**
     * @var Components\Html
     */
    protected $mainCheckbox;

    /**
     * @var Components\Html
     */
    protected $wrapper;

    /**
     * @var Components\Html
     */
    protected $button;

    public $onRender = array();

    static public $defaults = array(
        self::MAIN => array(
            'el' => 'a',
            'attributes' => array(
                'class' => 'btn btn-default btn-xs main-checkbox',
            ),
            'content' => '&nbsp;&nbsp;&nbsp;&nbsp;',
        ),
        self::ITEMS => array(
            'el' => 'a',
            'attributes' => array(
                'class' => 'btn btn-default btn-xs select-checkbox',
            ),
            'content' => '&nbsp;&nbsp;&nbsp;&nbsp;',
        ),
        self::DROP_DOWN => array(
            'class' => 'dropdown selection-dropdown'
        )
    );

    public function __construct($name = NULL, Components\IComponent $parent = NULL)
    {
        if (is_null($name)) {
            throw new Components\InvalidArgumentException('Component name is required.');
        }
        parent::__construct($name, $parent);
        $this->option = self::$defaults;
    }

    public function setItem($id, $status)
    {
        $statuses = array();
        if (is_string($status)) {
            $statuses[] = $status;
        } elseif (is_array($status)) {
            $statuses = $status;
        } else {
            throw new Components\InvalidArgumentException('Status must be string or array. ' . gettype($status) . ' given.');
        }
        $this->items[$id] = $statuses;
        return $this;
    }

    public function setItems(array $items)
    {
        foreach ($items as $id => $status) {
            $this->setItem($id, $status);
        }
        return $this;
    }

    protected function getItemPrototype($item_id)
    {
        $attributes = $this->option[self::ITEMS]['attributes'];
        if (count($this->items) > 0) {
            $attributes['data-status'] = implode('|', $this->items[$item_id]);
        }

        $attributes = array_merge($attributes, array(
            'data-id' => $item_id,
            'data-name' => $this->createLinkName(),
        ));
        return Components\Html::el($this->option[self::ITEMS]['el'], $attributes)->setHtml($this->option[self::ITEMS]['content']);
    }

    /**
     * @param $status
     * @param $text
     * @return $this
     */
    public function addStatus($status, $text)
    {
        $this->getDropDown()->addButton($text)
            ->setAttribute('href', '#')
            ->setAttribute('data-status', $status);
        return $this;
    }

    /**
     * @return DropDown
     */
    public function getDropDown()
    {
        if (!isset($this['dropDown'])) {
            $this['dropDown'] = $dropDown = new DropDown;
            $dropDown->getControlPrototype()
                ->class($this->option[self::DROP_DOWN]['class'])
                ->{'data-name'}($this->createLinkName());
        }
        return $this['dropDown'];
    }

    public function getMainCheckboxPrototype()
    {
        $attributes = $this->option[self::MAIN]['attributes'];
        $attributes = array_merge($attributes, array(
            'data-name' => $this->createLinkName(),
        ));
        return $this->mainCheckbox ? $this->mainCheckbox : ($this->mainCheckbox = Components\Html::el($this->option[self::MAIN]['el'], $attributes)->setHtml($this->option[self::MAIN]['content']));
    }

    public function createItem($item_id, $data = array())
    {
        $item = $this->getItemPrototype($item_id);

        return $item;
    }

    public function renderItem($item_id, $data = array())
    {
        echo $this->createItem($item_id, $data);
    }

    public function create($data = array())
    {
        parent::create();

        $main = $this->getMainCheckboxPrototype();

        $this->onRender($this);

        $dropdown = $this->getDropDown();

        $button = $dropdown->getMainButton();

        $button->setHtml($main);

        if (count($dropdown->getItems()) > 0) {
            $dropdown->addDivider();
        }

        $dropdown->addButton('Inverse selection')
            ->setAttribute('href', '#')
            ->setAttribute('data-status', 'm_-inverse');

        return $dropdown;
    }

}
