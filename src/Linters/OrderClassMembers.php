<?php

namespace Glhd\LaraLint\Linters;

use Glhd\LaraLint\Linters\Concerns\EvaluatesNodes;
use Glhd\LaraLint\Linters\Strategies\OrderingLinter;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Microsoft\PhpParser\Node\ClassConstDeclaration;
use Microsoft\PhpParser\Node\MethodDeclaration;
use Microsoft\PhpParser\Node\PropertyDeclaration;
use Microsoft\PhpParser\Node\Statement\ClassDeclaration;
use Microsoft\PhpParser\Node\TraitUseClause;

class OrderClassMembers extends OrderingLinter
{
	use EvaluatesNodes;
	
	protected function matchers() : Collection
	{
		return new Collection([
			'a trait' => $this->orderedMatcher()
				->withChild(TraitUseClause::class),
			
			'a public constant' => $this->orderedMatcher()
				->withChild(function(ClassConstDeclaration $node) {
					return $this->isPublic($node);
				}),
			
			'a protected constant' => $this->orderedMatcher()
				->withChild(function(ClassConstDeclaration $node) {
					return $this->isProtected($node);
				}),
			
			'a private constant' => $this->orderedMatcher()
				->withChild(function(ClassConstDeclaration $node) {
					return $this->isPrivate($node);
				}),
			
			'a public static property' => $this->orderedMatcher()
				->withChild(function(PropertyDeclaration $node) {
					return $this->isPublic($node)
						&& $this->isStatic($node);
				}),
			
			'a protected static property' => $this->orderedMatcher()
				->withChild(function(PropertyDeclaration $node) {
					return $this->isProtected($node)
						&& $this->isStatic($node);
				}),
			
			'a private static property' => $this->orderedMatcher()
				->withChild(function(PropertyDeclaration $node) {
					return $this->isPrivate($node)
						&& $this->isStatic($node);
				}),
			
			'a public property' => $this->orderedMatcher()
				->withChild(function(PropertyDeclaration $node) {
					return $this->isPublic($node)
						&& false === $this->isStatic($node);
				}),
			
			'a protected property' => $this->orderedMatcher()
				->withChild(function(PropertyDeclaration $node) {
					return $this->isProtected($node)
						&& false === $this->isStatic($node);
				}),
			
			'a private property' => $this->orderedMatcher()
				->withChild(function(PropertyDeclaration $node) {
					return $this->isPrivate($node)
						&& false === $this->isStatic($node);
				}),
			
			'a public static method' => $this->orderedMatcher()
				->withChild(function(MethodDeclaration $node) {
					return $this->isPublic($node)
						&& $this->isStatic($node);
				}),
			
			'a protected static method' => $this->orderedMatcher()
				->withChild(function(MethodDeclaration $node) {
					return $this->isProtected($node)
						&& $this->isStatic($node);
				}),
			
			'a private static method' => $this->orderedMatcher()
				->withChild(function(MethodDeclaration $node) {
					return $this->isPrivate($node)
						&& $this->isStatic($node);
				}),
			
			'the constructor' => $this->orderedMatcher()
				->withChild(function(MethodDeclaration $node) {
					return '__construct' === $node->getName();
				}),
			
			'the setUp method' => $this->orderedMatcher()
				->withChild(function(ClassDeclaration $node) {
					return Str::endsWith($node->getNamespacedName(), 'Test');
				})
				->withChild(function(MethodDeclaration $node) {
					return 'setUp' === $node->getName();
				}),
			
			'the tearDown method' => $this->orderedMatcher()
				->withChild(function(ClassDeclaration $node) {
					return Str::endsWith($node->getNamespacedName(), 'Test');
				})
				->withChild(function(MethodDeclaration $node) {
					return 'tearDown' === $node->getName();
				}),
			
			'a public method' => $this->orderedMatcher()
				->withChild(function(MethodDeclaration $node) {
					return $this->isPublic($node)
						&& false === $this->isStatic($node)
						&& 0 !== strpos($node->getName(), '__');
				}),
			
			'a protected method' => $this->orderedMatcher()
				->withChild(function(MethodDeclaration $node) {
					return $this->isProtected($node)
						&& false === $this->isStatic($node)
						&& 0 !== strpos($node->getName(), '__');
				}),
			
			'a private method' => $this->orderedMatcher()
				->withChild(function(MethodDeclaration $node) {
					return $this->isPrivate($node)
						&& false === $this->isStatic($node)
						&& 0 !== strpos($node->getName(), '__');
				}),
			
			'a magic method' => $this->orderedMatcher()
				->withChild(function(MethodDeclaration $node) {
					return 0 === strpos($node->getName(), '__');
				}),
		]);
	}
}
