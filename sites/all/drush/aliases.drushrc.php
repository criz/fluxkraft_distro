<?php

// The name of the project.
$project = 'fluxtest';

// Sets up site aliases for the 'dev', 'test' and 'live' environments. If a
// specific environment breaks out of this default pattern (e.g. other
// remote-host for the live site) you can simply override it by specifying the
// alias manually after the loop.
$host = 'drunomics.com';
$port = '50122';

foreach (array('dev', 'test', 'live') as $site) {
  $aliases[$site] = array(
    'uri' => "$project.$site.$host",
    'root' => "/srv/$site/web/$site.$host/$project/web",
    'remote-host' => $host,
    'remote-user' => $site,
    'ssh-options' => "-p $port",
  );
}

