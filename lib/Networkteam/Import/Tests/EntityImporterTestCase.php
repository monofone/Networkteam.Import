<?php
namespace Networkteam\Import\Tests;

/***************************************************************
 *  (c) 2014 networkteam GmbH - all rights reserved
 ***************************************************************/

use Doctrine\ORM\UnitOfWork;

class EntityImporterTestCase extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \Mockery\MockInterface
	 */
	protected $entityManager;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject
	 */
	protected $repository;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject
	 */
	protected $dataProvider;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject
	 */
	protected $unitOfWork;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject
	 */
	protected $eventManager;

	public function setUp() {
		$this->repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
			->setMethods(array('findOneByImportIdentifier'))
			->disableOriginalConstructor()
			->getMock();

		$this->unitOfWork = $this->getMockBuilder('Doctrine\ORM\UnitOfWork')
			->disableOriginalConstructor()
			->getMock();
		$this->unitOfWork->expects($this->any())
			->method('getEntityState')
			->will($this->returnValue(UnitOfWork::STATE_DETACHED));

		$this->eventManager = $this->getMockBuilder('Doctrine\Common\EventManager')->getMock();

		$this->entityManager = \Mockery::mock('Doctrine\ORM\EntityManager', array('getUnitOfWork' => $this->unitOfWork, 'getEventManager' => $this->eventManager));
	}

	/**
	 * @param array $returnValues
	 * @return \PHPUnit_Framework_MockObject_MockObject
	 */
	protected function getDataProviderMock(array $returnValues) {
		$organisationTransformer = $this->getMockBuilder('Networkteam\Import\DataProvider\TransformingProviderDecorator')
			->disableOriginalConstructor()
			->getMock();

		return $this->mockIterator($organisationTransformer, $returnValues);
	}

	/**
	 * Setup methods required to mock an iterator
	 *
	 * @param \PHPUnit_Framework_MockObject_MockObject $iteratorMock The mock to attach the iterator methods to
	 * @param array $items The mock data we're going to use with the iterator
	 * @return \PHPUnit_Framework_MockObject_MockObject The iterator mock
	 */
	public function mockIterator(\PHPUnit_Framework_MockObject_MockObject $iteratorMock, array $items) {
		$iteratorData = new \stdClass();
		$iteratorData->array = $items;
		$iteratorData->position = 0;

		$iteratorMock->expects($this->any())
			->method('rewind')
			->will(
				$this->returnCallback(
					function () use ($iteratorData) {
						$iteratorData->position = 0;
					}
				)
			);

		$iteratorMock->expects($this->any())
			->method('current')
			->will(
				$this->returnCallback(
					function () use ($iteratorData) {
						return $iteratorData->array[$iteratorData->position];
					}
				)
			);

		$iteratorMock->expects($this->any())
			->method('key')
			->will(
				$this->returnCallback(
					function () use ($iteratorData) {
						return $iteratorData->position;
					}
				)
			);

		$iteratorMock->expects($this->any())
			->method('next')
			->will(
				$this->returnCallback(
					function () use ($iteratorData) {
						$iteratorData->position++;
					}
				)
			);

		$iteratorMock->expects($this->any())
			->method('valid')
			->will(
				$this->returnCallback(
					function () use ($iteratorData) {
						return array_key_exists($iteratorData->position, $iteratorData->array);
					}
				)
			);

		$iteratorMock->expects($this->any())
			->method('count')
			->will(
				$this->returnCallback(
					function () use ($iteratorData) {
						return sizeof($iteratorData->array);
					}
				)
			);

		return $iteratorMock;
	}

	public function tearDown() {
		\Mockery::close();
	}
}
