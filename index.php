<?php
$url = 'https://spys.one/en/socks-proxy-list/';
$web = get($url);
preg_match_all('#type=\'hidden\' name=\'xx0\' value=\'(.*)\'><font class=spy1>Show <#', $web, $out);
$xx0 = $out[1][0];
$post = ['xx0' => $xx0, 'xpp' => 5, 'xf1' => 0, 'xf2' => 0, 'xf4' => 0, 'xf5' => 2];
$web = get($url, $post);
preg_match_all('/<tr><td>&nbsp;<\/td><\/tr><tr><td>&nbsp;<\/td><\/tr><\/td><\/tr><\/table><script type="text\/javascript">(.*)<\/script>/', $web, $out);
$e = explode(';', $out[1][0]);
$remove = array_slice($e, 10, 10);
foreach ($remove as $eval)
    eval('$' . explode('^', $eval)[0] . ';');
preg_match_all('/colspan=1><font class=spy14>(.*)<script type="text\/javascript">document\.write\("<font class=spy2>:<\\\\\/font>"\+\((.*)\)\)<\/script><\/font><\/td><td colspan=1>/U', $web, $out);
foreach ($out[2] as $key => $port) {
    $e = explode(')+(', $port);
    foreach ($e as $p)
        $pp[$key][] = ${explode('^', $p)[0]};
}
foreach ($out[1] as $key => $proxy)
    file_put_contents('proxy.txt', $proxy . ':' . implode('', $pp[$key]) . (isset($out[1][$key+1]) ? "\n" : null), FILE_APPEND);
function get($url, $post = null)
{
    $ch = curl_init($url);
    $options[CURLOPT_USERAGENT] = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:82.0) Gecko/20100101 Firefox/82.0';
    $options[CURLOPT_SSL_VERIFYHOST] = false;
    $options[CURLOPT_SSL_VERIFYPEER] = false;
    $options[CURLOPT_RETURNTRANSFER] = true;
    if ($post) {
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = http_build_query($post);
    }
    curl_setopt_array($ch, $options);
    $res = curl_exec($ch);
    echo curl_error($ch);
    curl_close($ch);
    return $res;
}