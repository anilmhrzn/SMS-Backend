<?php
namespace App\Service\Trie_based_search;

class TrieNode
{
    public bool $isEndOfWord = false;
    public array $children = [];
    public array $students = [];
}