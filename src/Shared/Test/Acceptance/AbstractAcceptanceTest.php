<?php

namespace App\Shared\Test\Acceptance;

use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractAcceptanceTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use PHPMatcherAssertions;

    protected \Symfony\Bundle\FrameworkBundle\KernelBrowser $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client=self::createClient();
    }

    /**
     * @return mixed[]|null
     */
    protected function getClientResponseContentArray(): ?array
    {
        $content=$this->client->getResponse()->getContent();

        if (!$content) {
            throw new \RuntimeException('Invalid content');
        }

        return json_decode($content, true);
    }

    protected function assertResponseMatchesPatternContent(mixed $checkPatternResponse): void
    {
        if (!$checkPatternResponse) {
            return;
        }

        $assertFn=fn ($pattern) => $this->assertMatchesPatternPrintPrettyError((string) $pattern);

        if (is_array($checkPatternResponse) || $checkPatternResponse instanceof \iterator) {
            array_walk($checkPatternResponse, $assertFn);

            return;
        }

        $assertFn($checkPatternResponse);
    }

    private function assertMatchesPatternPrintPrettyError(mixed $pattern) : void
    {
        $data = $this->client->getResponse()->getContent();

        // remove long trace...
        if ($this->client->getResponse()->getStatusCode() >= 300) {
            $data = $this->getClientResponseContentArray();
            if (isset($data['trace'])) {
                $data['trace'] = array_slice($data['trace'], 0, 1);
            }
            $data = json_encode($data);
        }

        $this->assertMatchesPattern($pattern, $data);
    }

    protected function get(string $service): object
    {
        return $this->getContainer()->get($service);
    }
}
