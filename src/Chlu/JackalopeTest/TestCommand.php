<?php
namespace Chlu\JackalopeTest;

use PHPCR\NodeInterface;
use PHPCR\Query\QOM\QueryObjectModelConstantsInterface;
use PHPCR\Util\QOM\QueryBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command {

	protected function configure() {
		$this
			->setName('chlu:jackalopetest:test')
			->setDescription('Do something.');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		/** @var \Jackalope\Session $session */
		$session = $this->getHelper('session')->getSession();

		/*
		$rootNode = $session->getNode("/");
		$sitesNode = $rootNode->addNode("sites");
		$siteNode = $sitesNode->addNode('neostypo3org');
		$siteNode->setProperty('name', 'neos.typo3.org');
		$session->save();
		*/

		$qf = $session->getWorkspace()->getQueryManager()->getQOMFactory();
		$qb = new QueryBuilder($qf);

		$qb->from($qf->selector('n', 'nt:unstructured'))
			->andWhere(
				$qf->comparison(
					$qf->propertyValue('n', 'name'),
					QueryObjectModelConstantsInterface::JCR_OPERATOR_EQUAL_TO,
					$qf->literal('neos.typo3.org')
				)
			);
		$result = $qb->execute();

		foreach ($result->getNodes(TRUE) as $node) {
			if ($node instanceof NodeInterface) {
				echo $node->getName() . " has name: " . $node->getPropertyValue('name') . "\n";
			}
		}
	}
}