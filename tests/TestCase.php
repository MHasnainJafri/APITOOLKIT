<?php
namespace Mhasnainjafri\APIToolkit\Tests;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Mhasnainjafri\APIToolkit\Tests\TestServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            TestServiceProvider::class,
        ];
    }
}
