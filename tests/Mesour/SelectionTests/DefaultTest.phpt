<?php

namespace Mesour\SelectionTests;

use Mesour\Components\RandomString\CapturingRandomStringGenerator;
use Mesour\Components\RandomString\IRandomStringGenerator;
use Mesour\SelectionTests\MockRandomStrings\DefaultTestRandomString;
use Mesour\UI\Application;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/BaseTestCase.php';
require_once __DIR__ . '/MockRandomStrings/DefaultTestRandomString.php';

class DefaultTest extends BaseTestCase
{

	protected $generateRandomString = false;

	/**
	 * @var IRandomStringGenerator|CapturingRandomStringGenerator
	 */
	protected $randomStringGenerator;

	public function setUp()
	{
		parent::setUp();

		if ($this->generateRandomString) {
			$this->randomStringGenerator = new CapturingRandomStringGenerator();
		} else {
			$this->randomStringGenerator = new DefaultTestRandomString();
		}
	}

	public function tearDown()
	{
		parent::tearDown();

		if ($this->generateRandomString) {
			$this->randomStringGenerator->writeToPhpFile(
				__DIR__ . '/MockRandomStrings/DefaultTestRandomString.php',
				'Mesour\SelectionTests\MockRandomStrings\DefaultTestRandomString'
			);
		}
	}

	public function testDefault()
	{
		$application = new Application('mesourApp');

		$application->getContext()
			->setService($this->randomStringGenerator, IRandomStringGenerator::class);

		$application->setRequest([]);

		$application->run();

		$selection = new \Mesour\UI\Selection('test', $application);

		$items = [
			1 => 'active',
			2 => 'inactive',
			3 => 'inactive',
			4 => 'active',
			5 => 'inactive',
		];

		$selection->setItems($items);

		$selection->addStatus('active', 'Active');

		$selection->addStatus('inactive', 'Inactive');

		Assert::same(
			file_get_contents(__DIR__ . '/data/DefaultTestOutput.html'),
			(string) $selection->render(),
			'Output of selection render doest not match'
		);

		foreach ($items as $id => $status) {
			Assert::same(
				$this->getItemExpectedValue($id),
				(string) $selection->createItem($id),
				sprintf('Output of selection item with ID %s render doest not match', $id)
			);
		}
	}

	private function getItemExpectedValue($id)
	{
		switch ($id) {
			case 1:
				return '<a class="btn btn-default btn-xs mesour-select-checkbox" data-status="active" data-id="1" data-name="mesourApp-test">&nbsp;&nbsp;&nbsp;&nbsp;</a>';
			case 2:
				return '<a class="btn btn-default btn-xs mesour-select-checkbox" data-status="inactive" data-id="2" data-name="mesourApp-test">&nbsp;&nbsp;&nbsp;&nbsp;</a>';
			case 3:
				return '<a class="btn btn-default btn-xs mesour-select-checkbox" data-status="inactive" data-id="3" data-name="mesourApp-test">&nbsp;&nbsp;&nbsp;&nbsp;</a>';
			case 4:
				return '<a class="btn btn-default btn-xs mesour-select-checkbox" data-status="active" data-id="4" data-name="mesourApp-test">&nbsp;&nbsp;&nbsp;&nbsp;</a>';
			case 5:
				return '<a class="btn btn-default btn-xs mesour-select-checkbox" data-status="inactive" data-id="5" data-name="mesourApp-test">&nbsp;&nbsp;&nbsp;&nbsp;</a>';
		}
		return $id;
	}

}

$test = new DefaultTest();
$test->run();
