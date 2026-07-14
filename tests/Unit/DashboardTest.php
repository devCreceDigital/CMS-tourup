<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\DashboardController;

class DashboardTest extends TestCase
{
    public function test_year_validation_rules()
    {
        $request = Request::create('/admin', 'GET', ['year' => 'abcd']);
        $rules = ['year' => 'nullable|integer|min:2000|max:2099'];
        $validator = validator($request->all(), $rules);
        $this->assertTrue($validator->fails());
    }

    public function test_year_accepts_valid_year()
    {
        $request = Request::create('/admin', 'GET', ['year' => '2026']);
        $rules = ['year' => 'nullable|integer|min:2000|max:2099'];
        $validator = validator($request->all(), $rules);
        $this->assertFalse($validator->fails());
    }
}
