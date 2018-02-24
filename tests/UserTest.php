<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Main\{User, Database};
class UserTest extends TestCase
{
	/** @var $user string */
	protected $user;

	protected function setUp()
	{
		$this->user = new User(new Database());
	}

	/**
	 * Get user by id
	 * @covers User::userByID()
	 * @covers User::checkExisting()
	 */
	public function testMailLogger()
	{
		$one = $this->user->userByID(1);
		$this->assertContains('dre', $one);
		$this->assertContains('1', $one);
		$this->assertTrue($this->user->checkExisting('dre'), true);
	}


	/**
	 * Test bad user input
	 * @covers User::getUser()
	 */
	public function testException() {
		try {
			$this->user->getUser('');
			$this->fail("Username Required");
		} catch (\Throwable $ex) {
			$this->assertEquals($ex->getMessage(), "Username Required");
		}

	}
}