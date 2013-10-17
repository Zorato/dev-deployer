<?php

namespace Deployer\Tests\Functional;

use org\bovigo\vfs\vfsStream;
use Mockery;

class GithubTest extends \Slim_Framework_TestCase
{
    protected $payload;

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        unset($this->payload);
        unset($this->fileSystem);

        parent::tearDown();
    }

    public function testPostedPayloadDeploysRepo()
    {
        $this->givenAValidGithubPayload();
        $this->andAValidConfigForThatRepoAndBranch();
        $this->whenIPostItTo('/github');
        $this->thenTheRepoShouldBeDeployed();
    }

    public function testNoBranchInConfigDoesNotDeployRepo()
    {
        $this->givenAValidGitHubPayLoad();
        $this->andAValidConfigForThatRepoButNotBranch();
        $this->whenIPostItTo('/github');
        $this->thenTheRepoShouldNotBeDeployed();
    }

    protected function givenAValidGithubPayload()
    {
        $this->payload = file_get_contents(FIXTURE_PATH . '/github_payload.json');
    }

    protected function andAValidConfigForThatRepoAndBranch()
    {
        $this->setConfig(array(
            'name' => 'octokitty',
            'branch' => 'release/1.0',
            'repository' => $this->getTmpGitRepo(),
            'path' => $this->getTmpFilesystem('/deploy/path')
        ));

    }

    protected function andAValidConfigForThatRepoButNotBranch()
    {
        $this->setConfig(array(
            'name' => 'octokitty',
            'branch' => 'master',
            'repository' => $this->getTmpGitRepo(),
            'path' => $this->getTmpFilesystem('/deploy/path')
        ));
    }

    protected function whenIPostItTo($url)
    {
        $this->post($url, array('payload' => $this->payload));
    }

    protected function thenTheRepoShouldBeDeployed()
    {
        $this->assertTrue($this->fileSystem->hasChild('/deploy/path/test.txt'));
    }

    protected function thenTheRepoShouldNotBeDeployed()
    {
        $this->assertFalse($this->fileSystem->hasChild('/deploy/path/test.txt'));
    }

    private function setConfig($config)
    {
        $this->app->container->singleton('projects', function () use ($config) {
            $projectFactory = new Deploy\Config\ProjectFactory();

            $config = new Config();
            $config->addProject($projectFactory->create($config));

            return $config;
        });
    }

    private function getTmpGitRepo()
    {
        $path = $this->getTmpFilesystem('/repo/path');
        $repo = $this->app->git->workingCopy($path);
        $repo->config('user.name', 'PHPUnit');
        $repo->config('user.email', 'test@local.dev');
        $repo->init();
        touch($path . '/test.txt');
        $repo->add('test.txt')->commit('Adding test.txt');
        return $path;
    }

    private function getTmpFilesystem($path)
    {
        $path = APPLICATION_PATH . '/tmp/tests' . $path;
        if (!is_dir($path)) {
            mkdir($path, 0700, true);
        }

        return $path;
    }
}
