<?php

namespace Glhd\LaraLint\Contracts;

use Glhd\LaraLint\ResultCollection;
use Microsoft\PhpParser\Node;

interface Linter
{
	public function enterNode(Node $node) : void;
	
	public function lint() : ResultCollection;
	
	public function leaveNode(Node $node) : void;
}
