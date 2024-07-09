<?php
namespace App\Service\Trie_based_search;


class Trie
{
    private TrieNode $root;

    public function __construct()
    {
        $this->root = new TrieNode();
    }

    public function insert(string $key, $student): void
    {
        $node = $this->root;

        for ($i = 0; $i < strlen($key); $i++) {
            $char = $key[$i];

            if (!isset($node->children[$char])) {
                $node->children[$char] = new TrieNode();
            }

            $node = $node->children[$char];
        }

        $node->isEndOfWord = true;
        $node->students[] = $student;
    }

    public function search(string $key): array
    {
        $node = $this->root;

        for ($i = 0; $i < strlen($key); $i++) {
            $char = $key[$i];

            if (!isset($node->children[$char])) {
                return [];
            }

            $node = $node->children[$char];
        }

        return $this->collectAllStudents($node);
    }

    private function collectAllStudents(TrieNode $node): array
    {
        $result = $node->students;

        foreach ($node->children as $child) {
            $result = array_merge($result, $this->collectAllStudents($child));
        }

        return $result;
    }
}
