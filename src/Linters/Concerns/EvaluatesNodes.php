<?php

namespace Glhd\LaraLint\Linters\Concerns;

use Illuminate\Support\Collection;
use Microsoft\PhpParser\Node\Expression\ObjectCreationExpression;
use Microsoft\PhpParser\Node\Statement\ClassDeclaration;
use Microsoft\PhpParser\Token;
use Microsoft\PhpParser\TokenKind;

trait EvaluatesNodes
{
	protected function isClassDeclarationOrAnonymousClass($node) : bool 
	{
		return $node instanceof ClassDeclaration || $this->isAnonymousClassExpression($node);
	}
	
	protected function isAnonymousClassExpression($node) : bool
	{
		return $node instanceof ObjectCreationExpression
			&& $node->classTypeDesignator instanceof Token
			&& TokenKind::ClassKeyword === $node->classTypeDesignator->kind;
	}
	
	protected function isPublic($node) : bool
	{
		return false === $this->hasModifier($node, TokenKind::ProtectedKeyword)
			&& false === $this->hasModifier($node, TokenKind::PrivateKeyword);
	}
	
	protected function isPrivate($node) : bool
	{
		return $this->hasModifier($node, TokenKind::PrivateKeyword);
	}
	
	protected function isProtected($node) : bool
	{
		return $this->hasModifier($node, TokenKind::ProtectedKeyword);
	}
	
	protected function isStatic($node) : bool
	{
		return $this->hasModifier($node, TokenKind::StaticKeyword);
	}
	
	protected function isAbstract($node) : bool
	{
		return $this->hasModifier($node, TokenKind::AbstractKeyword);
	}
	
	protected function hasModifier($node, $kind) : bool
	{
		return Collection::make($node->modifiers)
			->contains(function(Token $modifier) use ($kind) {
				return $modifier->kind === $kind;
			});
	}
}
