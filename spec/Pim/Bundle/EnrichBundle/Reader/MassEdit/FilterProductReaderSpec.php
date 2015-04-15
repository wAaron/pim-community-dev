<?php

namespace spec\Pim\Bundle\EnrichBundle\Reader\MassEdit;

use Akeneo\Bundle\BatchBundle\Entity\JobExecution;
use Akeneo\Bundle\BatchBundle\Entity\JobInstance;
use Akeneo\Bundle\BatchBundle\Entity\StepExecution;
use Akeneo\Bundle\BatchBundle\Job\DoctrineJobRepository;
use Akeneo\Bundle\StorageUtilsBundle\Doctrine\ORM\Cursor\Cursor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use PhpSpec\ObjectBehavior;
use Pim\Bundle\CatalogBundle\Model\ProductInterface;
use Pim\Bundle\CatalogBundle\Query\ProductQueryBuilder;
use Pim\Bundle\CatalogBundle\Query\ProductQueryBuilderFactory;
use Pim\Bundle\CatalogBundle\Query\ProductQueryBuilderFactoryInterface;
use Pim\Bundle\EnrichBundle\Entity\MassEditJobConfiguration;
use Pim\Bundle\EnrichBundle\Entity\Repository\MassEditRepository;
use Prophecy\Argument;

class FilterProductReaderSpec extends ObjectBehavior
{
    function let(
        ProductQueryBuilderFactoryInterface $pqbFactory,
        EntityManager $entityManager,
        DoctrineJobRepository $jobRepository,
        MassEditRepository $massEditRepository
    ) {
        $this->beConstructedWith(
            $pqbFactory,
            $entityManager,
            $jobRepository,
            $massEditRepository,
            'update_product_value'
        );
    }

    function it_reads_products(
        $entityManager,
        $jobRepository,
        MyCustomMassEditRepository $massEditRepository,
        JobInstance $jobInstance,
        JobExecution $jobExecution,
        MassEditJobConfiguration $massEditJobConf,
        ProductQueryBuilderFactory $pqbFactory,
        ProductQueryBuilder $pqb,
        StepExecution $stepExecution,
        Cursor $cursor,
        ProductInterface $product,
        MyCustomEntityRepository $test
    ) {
        $jobRepository->getJobManager()->willReturn($entityManager);
        $entityManager->getRepository('AkeneoBatchBundle:JobInstance')->willReturn($test);
        $test->findOneByCode('update_product_value')->willReturn($jobInstance);

        $jobInstance->getJobExecutions()->willReturn(new ArrayCollection([$jobExecution]));
        $massEditRepository->findOneByJobExecution($jobExecution)->willReturn($massEditJobConf);

        $pqbFactory->create()->willReturn($pqb);
        $massEditJobConf->getConfiguration()->willReturn(json_encode(['filters' => [], 'actions' => []]));
        $pqb->execute()->willReturn($cursor);
        $cursor->next()->shouldBeCalled();
        $stepExecution->incrementSummaryInfo('read')->shouldBeCalledTimes(1);
        $this->setStepExecution($stepExecution);
        $cursor->current()->willReturn($product);

        $this->read()->shouldReturn(['product' => $product, 'actions' => []]);
    }
}

class MyCustomEntityRepository extends EntityRepository
{
    public function findOneByCode($code)
    {
    }
}

class MyCustomMassEditRepository extends MassEditRepository
{
    public function findOneByJobExecution($jobExecution)
    {
    }
}
