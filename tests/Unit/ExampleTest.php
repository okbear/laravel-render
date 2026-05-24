<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_example_string_contains_expected_text(): void
    {
        $this->assertStringContainsString('Laravel', 'Laravel security checks');
    }
}
