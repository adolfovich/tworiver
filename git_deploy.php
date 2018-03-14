<?php
function cidr_match($ip, $ranges)
{
    $ranges = (array)$ranges;
    foreach($ranges as $range) {
        list($subnet, $mask) = explode('/', $range);
        if((ip2long($ip) & ~((1 << (32 - $mask)) - 1)) == ip2long($subnet)) {
            return true;
        }
    }
    return false;
}
$github_ips = array('192.30.252.1', '192.30.255.254', '185.199.108.1', '185.199.111.254');
$github_cidrs = array('192.30.252.0/22', '185.199.108.0/22');
if(in_array($_SERVER['REMOTE_ADDR'], $github_ips) || cidr_match($_SERVER['REMOTE_ADDR'], $github_cidrs)) {
    $dir = '/var/www_snt/html/tworiver';
    exec("cd $dir && git pull 2>&1", $output);
    echo $output;
}
else {
    header('HTTP/1.1 404 Not Found');
    echo '404 Not Found.';
    exit;
}