<?php

namespace Glhd\LaraLint\Linters;

use Glhd\LaraLint\Linters\Helpers\FlagsNodesByRuleset;
use Glhd\LaraLint\Linters\Strategies\ClassMethodLinter;
use Microsoft\PhpParser\Node\MethodDeclaration;
use Microsoft\PhpParser\Node\Statement\ClassDeclaration;

class PrefixTestsWithTest extends ClassMethodLinter
{
	use FlagsNodesByRuleset;
	
	protected const MESSAGE = 'Test methods must start with test_';
	
	protected function shouldWalkClass(ClassDeclaration $node) : bool
	{
		return preg_match('/Test$/', $node->getNamespacedName()->getFullyQualifiedNameText());
	}
	
	protected function enterMethod(MethodDeclaration $node) : void
	{
		$this->flagNodeIfAllRulesMatch($node, static::MESSAGE, [
			false === $node->isStatic(),
			$node->isPublic(),
			0 !== strpos($node->getName(), 'test_'),
		]);
	}
}