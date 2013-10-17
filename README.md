dev-deployer
=========

Deploy your app from github automatically!

Do you deploy your applications by logging in to your server and running "git pull"?  If so, why not automate the process?  Using github's web hooks you can notify a URL whenever someone pushes to a branch or accepts a pull-request.  At the end of that URL can be this little script, which has all the knowledge it needs to deploy your app every time it's called.

I initially wrote this script because my team were working on over 50 new features in one of our apps, and they were forgetting to log in to our dev server and run "git pull" after their code reviews had passed.  This meant our users were hitting up the dev server expecting to test their _Awesome New Feature_ and were failing the issue because they couldn't see it.

Now whenever a code review passes the code-reviewer can merge it (they're just pull-requests), and github will notify our dev server to pull down the changes automatically.  Now our users never fail issues just because they don't have access to the latest dev code and everyone is much happier.

Requirements/Roadmap
--------------------

This script was build with the following in mind

1. Must run on a webserver (apache, nginx)
2. Must be one install per server (deploy multiple apps/sites)
3. Must run without root access
4. Must run a series of commands after deployment (post-deploy scripts)
5. Must not require any additional application files (e.g. config), the app shouldn't be aware of how it's deployed

### Optional

6. Could run tests and revert deployment if they fail
7. Send success/failure emails to list of people

Installation
------------

Upload the files from this repository to somewhere your server.

Create a new virtual host and set the `DocumentRoot` to be the `src/public` directory from this app.  I'd recommend a subdomain like https://deploy.dev.yourapp.com or a non-standard port https://dev.yourapp.com:3241.

Edit `config/projects.php` and update as per the config section below.

Go to your github repository, click settings, web hooks, then add the URL to your new vitual host.


Config
------

Configuration is done by a simple PHP array in the `./config/projects.php` file.

    return array(
        array(
            'name' => 'dev-deployer',
            'branch' => 'develop',
            'repository' => 'git@github.com:adambrett/dev-deployer.git',
            'path' => '/srv/tools/deployer/%commit%',
            'flags' => array(
                'depth' => 50,
                'recursive' => true
            ),
            'post-deploy' => array()
        )
    );

### Example

The example below will deploy any pushes or pull-requests the `master` and `develop` branches of the `dev-deployer` repository to the directory `/srv/releases/dev-deployer/%commit%` where `%commit%` is the id of the commit for this change (see replacements below).

They will then both run a post-deploy script which for master deletes the "current" symlink and re-creates it pointing at our new "release" and the develop branch does the same for the "develop" symlink.

This allows us to create virtual hosts for `www.your-app.com` which point at the symlink rather than the directory meaning we can maintain multiple versions of the app on the server (for easy roll-backs).  This is not a requirement, and dev-deployer will work just fine updating the same directory each time.

    return array(
        array(
            'name' => 'dev-deployer',
            'branch' => 'master',
            'repository' => 'git@github.com:adambrett/dev-deployer.git',
            'path' => '/srv/releases/master/%commit%',
            'flags' => array(
                'depth' => 50,
                'recursive' => true
            ),
            'post-deploy' => array(
                "rm -f /srv/releases/master/current",
                "ln -s %path% /srv/releases/master/current"
            )
        ),
        array(
            'name' => 'dev-deployer',
            'branch' => 'develop',
            'repository' => 'git@github.com:adambrett/dev-deployer.git',
            'path' => '/srv/releases/develop/%commit%',
            'flags' => array(
                'depth' => 50,
                'recursive' => true
            ),
            'post-deploy' => array(
                "rm -f /srv/releases/develop/current",
                "ln -s %path% /srv/releases/develop/current"
            )
        )
    );

### Replacements

You can use a number of replacements to aid in your deployment, either in paths or your post-deploy scripts.

<table>
    <tr>
        <th>Code</th>
        <th>Replacement</th>
    </tr>
    <tr>
        <td><code>%commit</code></td>
        <td>The new commit, as sent over by github</td>
    </tr>
    <tr>
        <td><code>%name%</code></td>
        <td>the repo name</td>
    </tr>
    <tr>
        <td><code>%branch%</code></td>
        <td>the branch name</td>
    </tr>
    <tr>
        <td><code>%path%</code></td>
        <td>the deployment path</td>
    </tr>
    <tr>
        <td><code>%url%</code></td>
        <td>the url of the repo</td>
    </tr>
    <tr>
        <td><code>%dateString%</code></td>
        <td>PHP compatible date string</td>
    </tr>
</table>

With `%dateString%` you can use any PHP date compatible string with `%` before and after to have that replaced by the eqivilient date value (any spaces will be replaced with a hyphen `-`).

Requirements
------------

PHP >=5.3

Authors
-------

Adam Brett - http://twitter.com/sixdaysad - http://adamcod.es

License
-------

dev-deployer is licensed under the BSD-3-Clause License - see the LICENSE file for details
