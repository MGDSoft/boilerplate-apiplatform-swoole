<?php

namespace App\User\Test\Acceptance;

use App\Shared\Test\Acceptance\AbstractAcceptanceTest;
use App\Shared\Test\Acceptance\MatcherPatternCreator;
use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;

class PostUserTest extends AbstractAcceptanceTest
{
    /**
     * @dataProvider providerBasicFlow
     */
    public function testBasicFlow(string $data, int $statusResponse, mixed $textPatternResponse = null, int $numInserted = 0): void
    {
        $this->client->request('PUT', '/user-custom', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT'  => 'application/json',
        ], $data);

        $this->assertResponseMatchesPatternContent($textPatternResponse);
        $this->assertResponseStatusCodeSame($statusResponse);

        /** @var UserRepositoryInterface $repository */
        $repository = $this->get(UserRepositoryInterface::class);
        $this->assertEquals($repository->count([]), $numInserted);
    }

    /**
     * @return mixed[]
     */
    public function providerBasicFlow(): array
    {
        $dataOK = [[
            'name' => 'one asdasdasd asd asd',
        ], [
            'name' => 'two asdasdasd asd asd',
        ]];

        $dataFailInvalidType = $dataOK;
        $dataFailInvalidType[1]['name'] = 300;

        $invalidJson = json_encode($dataFailInvalidType).'wrong';

        return [
            'ok'                => [json_encode($dataOK), 200, '[{"name": "one asdasdasd asd asd", "@*@": "@*@"},{"name": "two asdasdasd asd asd", "@*@": "@*@"}]', 2],

            'fail INVALID TYPE' => [json_encode($dataFailInvalidType), 422, MatcherPatternCreator::violations([Type::INVALID_TYPE_ERROR, Range::NOT_IN_RANGE_ERROR])],
            'fail INVALID JSON' => [$invalidJson, 400],
        ];
    }
}
