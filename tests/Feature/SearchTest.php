<?php

namespace Tests\Feature;

use Tests\TestCase;

class SearchTest extends TestCase
{
    public function test_search_query_builds_correctly()
    {
        $searchTerm = 'Juan';
        $travelerQuery = \App\Models\Traveler::where('first_name', 'like', "%{$searchTerm}%")
            ->orWhere('last_name', 'like', "%{$searchTerm}%");
        $sql = $travelerQuery->toSql();
        $this->assertStringContainsString('first_name', $sql);
        $this->assertStringContainsString('last_name', $sql);
        $this->assertStringNotContainsString('full_name', $sql);
    }

    public function test_search_by_full_name_uses_concat()
    {
        $searchTerm = 'Juan Perez';
        $query = \App\Models\Traveler::where('first_name', 'like', "%{$searchTerm}%")
            ->orWhere('last_name', 'like', "%{$searchTerm}%")
            ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$searchTerm}%"]);
        $sql = $query->toSql();
        $this->assertStringContainsString('CONCAT', $sql);
    }
}
