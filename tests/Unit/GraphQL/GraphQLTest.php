<?php

declare(strict_types=1);

namespace DanielDeWit\LighthousePaperclip\Tests\Unit\GraphQL;

use DanielDeWit\LighthousePaperclip\Tests\Unit\UnitTest;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Nuwave\Lighthouse\Testing\MocksResolvers;
use Nuwave\Lighthouse\Testing\UsesTestSchema;

abstract class GraphQLTest extends UnitTest
{
    use MakesGraphQLRequests;
    use MocksResolvers;
    use UsesTestSchema;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpTestSchema();
    }
}
