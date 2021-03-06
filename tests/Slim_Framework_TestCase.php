<?php

class Slim_Framework_TestCase extends PHPUnit_Framework_TestCase
{
    protected $app;

    // Initialize our own copy of the slim application
    public function setUp()
    {
        $app = new \Slim\Slim(array(
            'version' => '0.0.0',
            'debug'   => false,
            'mode'    => 'testing'
        ));

        require APPLICATION_PATH . '/src/app.php';

        // Establish a local reference to the Slim app object
        $this->app = $app;
    }

    public function tearDown()
    {
        unset($this->app);
    }

    // Abstract way to make a request to SlimPHP, this allows us to mock the
    // slim environment
    public function request($method, $path, $options = array())
    {
        // Capture STDOUT
        ob_start();

        // Prepare a mock environment
        \Slim\Environment::mock(array_merge(array(
            'REQUEST_METHOD' => $method,
            'PATH_INFO'      => $path,
            'SERVER_NAME'    => 'local.dev',
        ), $options));

        // Establish some useful references to the slim app properties
        $this->request  = $this->app->request();
        $this->response = $this->app->response();

        // Execute our app
        $this->app->run();

        // Return the application output. Also available in `response->body()`
        return ob_get_clean();
    }

    public function get($path, $options = array())
    {
        return $this->request('GET', $path, $options);
    }

    public function post($path, $postVars = array(), $options = array())
    {
        $options['slim.input'] = http_build_query($postVars);
        return $this->request('POST', $path, $options);
    }

    public function patch($path, $postVars = array(), $options = array())
    {
        $options['slim.input'] = http_build_query($postVars);
        return $this->request('PATCH', $path, $options);
    }

    public function put($path, $postVars = array(), $options = array())
    {
        $options['slim.input'] = http_build_query($postVars);
        return $this->request('PUT', $path, $options);
    }

    public function delete($path, $options = array())
    {
        return $this->request('DELETE', $path, $options);
    }

    public function head($path, $options = array())
    {
        return $this->request('HEAD', $path, $options);
    }

}
