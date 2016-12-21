<?php
/**
 * This file is part of the Mesour Selection (http://grid.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\UI;

use Mesour;

/**
 * @author Matouš Němec (http://mesour.com)
 *
 * @method null onRender(Selection $selection)
 */
class Selection extends Mesour\Components\Control\OptionsControl implements Mesour\Selection\ISelection
{

	use Mesour\Icon\HasIcon;

	const ITEMS = 'items';
	const DROP_DOWN = 'drop-down';
	const MAIN = 'main';

	/**
	 * Array of items ID => string|array (statuses)
	 * @var array
	 */
	protected $items = [];

	/** @var Mesour\Components\Utils\Html */
	protected $mainCheckbox;

	/** @var Mesour\Components\Utils\Html */
	protected $button;

	public $onRender = [];

	protected $defaults = [
		self::MAIN => [
			'el' => 'a',
			'attributes' => [
				'class' => 'btn btn-default btn-xs mesour-main-checkbox',
			],
			'content' => '&nbsp;&nbsp;&nbsp;&nbsp;',
		],
		self::ITEMS => [
			'el' => 'a',
			'attributes' => [
				'class' => 'btn btn-default btn-xs mesour-select-checkbox',
			],
			'content' => '&nbsp;&nbsp;&nbsp;&nbsp;',
		],
		self::DROP_DOWN => [
			'class' => 'dropdown selection-dropdown',
		],
	];

	public function __construct($name = null, Mesour\Components\ComponentModel\IContainer $parent = null)
	{
		if (is_null($name)) {
			throw new Mesour\InvalidStateException('Component name is required.');
		}
		parent::__construct($name, $parent);
	}

	public function setItem($id, $status)
	{
		$statuses = [];
		if (is_string($status)) {
			$statuses[] = $status;
		} elseif (is_array($status)) {
			$statuses = $status;
		} else {
			throw new Mesour\InvalidArgumentException(
				sprintf('Status must be string or array. %s given.', gettype($status))
			);
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

	protected function getItemPrototype($itemId)
	{
		$attributes = $this->getOption(self::ITEMS, 'attributes');
		if (count($this->items) > 0) {
			$attributes['data-status'] = implode('|', $this->items[$itemId]);
		}

		$attributes = array_merge(
			$attributes,
			[
				'data-id' => $itemId,
				'data-name' => $this->createLinkName(),
			]
		);
		return Mesour\Components\Utils\Html::el($this->getOption(self::ITEMS, 'el'), $attributes)
			->setHtml($this->getOption(self::ITEMS, 'content'));
	}

	/**
	 * @param string $status
	 * @param string $text
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
			$dropDown->setAttribute('class', $this->getOption(self::DROP_DOWN, 'class'))
				->setAttribute('data-name', $this->createLinkName());
		}
		return $this['dropDown'];
	}

	public function getMainCheckboxPrototype()
	{
		$attributes = $this->getOption(self::MAIN, 'attributes');
		$attributes = array_merge(
			$attributes,
			[
				'data-name' => $this->createLinkName(),
				'data-icon-prefix' => $this->createNewIcon('cog')->getPrefix(),
			]
		);
		return $this->mainCheckbox
			? $this->mainCheckbox
			: ($this->mainCheckbox = Mesour\Components\Utils\Html::el(
				$this->getOption(self::MAIN, 'el'),
				$attributes
			)->setHtml(
				$this->getOption(self::MAIN, 'content')
			));
	}

	public function createItem($itemId, $data = [])
	{
		$item = $this->getItemPrototype($itemId);

		return $item;
	}

	public function renderItem($itemId, $data = [])
	{
		echo $this->createItem($itemId, $data);
	}

	public function create($data = [])
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
