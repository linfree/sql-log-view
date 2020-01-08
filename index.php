<?php
/**
 * Created by PhpStorm.
 * User: linfree
 * Date: 2020-01-08
 * Time: 14:07
 */

$db_host = 'localhost';
$db_name = 'mysql';
$db_user = 'root';
$db_pwd = 'root';

//面向对象方式
@$mysqli = new mysqli($db_host, $db_user, $db_pwd, $db_name);
define("LOG_DIR", __DIR__ . DIRECTORY_SEPARATOR . 'tmp.log');

$status = get_status($mysqli);
////设置编码
//$mysqli->set_charset("utf8");//或者 $mysqli->query("set names 'utf8'")


function get_status($link)
{
    if (mysqli_connect_error()) {
        return 'Connect Error';
    }
    $res = $link->query("SHOW VARIABLES LIKE 'general_log%'");
    $status = $res->fetch_assoc();
    return $status['Value'];
}


function modify_status($link)
{
    $log_dir = str_replace('\\', '/', LOG_DIR);
    $status = get_status($link);
    if ($status === "OFF") {
        $link->query("SET GLOBAL general_log = 'ON';");
        $link->query("SET GLOBAL general_log_file = '{$log_dir}';");
        return "start";
    } elseif ($status === "ON") {
        $link->query("SET GLOBAL general_log = 'OFF';");
        return "close";
    } else {
        return $status;
    }
}

function get_logs()
{
    $log_dir = str_replace('\\', '/', LOG_DIR);
    if (file_exists($log_dir)) {
        $str = file_get_contents($log_dir);
        $re = '/^(\d{4}.*Z)\s*\d*\sQuery\s(.*)$/m';
        preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
        /*按时间顺序倒排*/
        for ($i = count($matches); $i > 0; $i--) {
            if ($matches[$i - 1][2] != 'SHOW VARIABLES LIKE \'general_log%\'') {
                echo date('m-d/H:i:s', strtotime($matches[$i - 1][1])) . '    ';
                echo $matches[$i - 1][2] . ';<br/>';
            }
        }
        if(count($matches)===0){
            echo 'not found';
        }
    }
}

function clear_log()
{
    $log_dir = str_replace('\\', '/', LOG_DIR);
    if (file_exists($log_dir)) {
        file_put_contents($log_dir, '');
    }
    return 'clear';
}

$do = isset($_GET['do']) ? $_GET['do'] : "";

//路由
if ($do) {
    switch ($do) {
        case "status":
            echo get_status($mysqli);
            break;
        case "modify":
            echo modify_status($mysqli);
            break;
        case "clear":
            echo clear_log();
            break;
        case "logs":
            echo get_logs();
            break;

    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- Begin Jekyll SEO tag v2.5.0 -->
    <title>SQL Log Show | A tool for mysql log display.</title>
    <meta name="generator" content="SQL Log Show"/>
    <meta property="og:title" content="SQL Log Show"/>
    <meta property="og:locale" content="en_US"/>
    <meta name="description" content=" A tool for mysql log display."/>
    <meta property="og:description" content=" A tool for mysql log display."/>
    <link rel="canonical" href="https://github.com/linfree/linfree.github.io"/>
    <meta property="og:url" content="https://github.com/linfree/linfree.github.io"/>
    <meta property="og:site_name" content="SQL Log Show"/>

    <style>
        .highlight {
            color: #d0d0d0
        }

        .highlight table td {
            padding: 5px
        }

        .highlight table pre {
            margin: 0
        }

        body {
            margin: 0;
            padding: 0;
            background: #151515;
            color: #eaeaea;
            font-size: 16px;
            line-height: 1.5;
            font-family: Monaco, "Bitstream Vera Sans Mono", "Lucida Console", Terminal, monospace
        }

        .container {
            width: 90%;
            max-width: 1000px;
            margin: 0 auto
        }

        section {
            display: block;
            margin: 0 0 20px 0
        }

        h1, h2, h3, h4, h5, h6 {
            margin: 0 0 20px
        }

        li {
            line-height: 1.4
        }

        header {
            background: rgba(0, 0, 0, 0.1);
            width: 100%;
            border-bottom: 1px dashed #b5e853;
            padding: 20px 0;
            margin: 0 0 40px 0
        }

        header h1 {
            font-size: 30px;
            line-height: 1.5;
            margin: 0 0 0 -40px;
            font-weight: bold;
            font-family: Monaco, "Bitstream Vera Sans Mono", "Lucida Console", Terminal, monospace;
            color: #b5e853;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1), 0 0 5px rgba(181, 232, 83, 0.1), 0 0 10px rgba(181, 232, 83, 0.1);
            letter-spacing: -1px;
            -webkit-font-smoothing: antialiased
        }

        header h1:before {
            content: "./ ";
            font-size: 24px
        }

        header h2 {
            font-size: 18px;
            font-weight: 300;
            color: #666
        }

        #downloads .btn {
            display: inline-block;
            text-align: center;
            margin: 0
        }

        #main_content {
            width: 100%;
            -webkit-font-smoothing: antialiased
        }

        section img {
            max-width: 100%
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: normal;
            font-family: Monaco, "Bitstream Vera Sans Mono", "Lucida Console", Terminal, monospace;
            color: #b5e853;
            letter-spacing: -0.03em;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1), 0 0 5px rgba(181, 232, 83, 0.1), 0 0 10px rgba(181, 232, 83, 0.1)
        }

        #main_content h1 {
            font-size: 30px
        }

        #main_content h2 {
            font-size: 24px
        }

        #main_content h3 {
            font-size: 18px
        }

        #main_content h4 {
            font-size: 14px
        }

        #main_content h5 {
            font-size: 12px;
            text-transform: uppercase;
            margin: 0 0 5px 0
        }

        #main_content h6 {
            font-size: 12px;
            text-transform: uppercase;
            color: #999;
            margin: 0 0 5px 0
        }

        dt {
            font-style: italic;
            font-weight: bold
        }

        blockquote {
            color: #aaa;
            padding-left: 10px;
            border-left: 1px dotted #666
        }

        pre {
            background: rgba(0, 0, 0, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 10px;
            font-size: 16px;
            color: #b5e853;
            border-radius: 2px;
            word-wrap: normal;
            overflow: auto;
            overflow-y: hidden
        }

        code.highlighter-rouge {
            background: rgba(0, 0, 0, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 0px 3px;
            margin: 0px -3px;
            color: #aa759f;
            border-radius: 2px
        }

        table {
            width: 100%;
            margin: 0 0 20px 0
        }

        th {
            text-align: left;
            border-bottom: 1px dashed #b5e853;
            padding: 5px 10px
        }

        td {
            padding: 5px 10px
        }

        hr {
            height: 0;
            border: 0;
            border-bottom: 1px dashed #b5e853;
            color: #b5e853
        }

        .btn {
            display: inline-block;
            background: -webkit-linear-gradient(top, rgba(40, 40, 40, 0.3), rgba(35, 35, 35, 0.3) 50%, rgba(10, 10, 10, 0.3) 50%, rgba(0, 0, 0, 0.3));
            padding: 8px 18px;
            border-radius: 50px;
            border: 2px solid rgba(0, 0, 0, 0.7);
            border-bottom: 2px solid rgba(0, 0, 0, 0.7);
            border-top: 2px solid #000;
            color: rgba(255, 255, 255, 0.8);
            font-family: Helvetica, Arial, sans-serif;
            font-weight: bold;
            font-size: 13px;
            text-decoration: none;
            text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.75);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05)
        }

        .btn:hover {
            background: -webkit-linear-gradient(top, rgba(40, 40, 40, 0.6), rgba(35, 35, 35, 0.6) 50%, rgba(10, 10, 10, 0.8) 50%, rgba(0, 0, 0, 0.8))
        }

        .btn .icon {
            display: inline-block;
            width: 16px;
            height: 16px;
            margin: 1px 8px 0 0;
            float: left
        }

        a {
            color: #63c0f5;
            text-shadow: 0 0 5px rgba(104, 182, 255, 0.5)
        }

        .cf:before, .cf:after {
            content: "";
            display: table
        }

        .cf:after {
            clear: both
        }

        .cf {
            zoom: 1
        }

        #a-title {
            text-decoration: none
        }

    </style>
</head>

<body>

<header>
    <div class="container">
        <a id="a-title" href="/">
            <h1>SQL Log Show</h1>
        </a>
        <h2>A tool for mysql log display.</h2>
        <section id="downloads">
            <?php
            if ($status === 'OFF') {
                echo '<button  class="btn" id="status" onclick="modify_status()">Start listening</button>';
            } elseif ($status === 'ON') {
                echo '<button  class="btn" id="status" onclick="modify_status()">Stop listening</button>';
            } else {
                echo '<button  id="status" class="btn" >' . $status . '</button>';
            }
            ?>
            <button class="btn" onclick="clear_log()">Clear log</button>
            <a href="/" class="btn">Refresh</a>
        </section>
    </div>
</header>

<div class="container">
    <section id="main_content">
            <h2 id="header-2">Logs</h2>
            <div class="language-js highlighter-rouge">
                <div class="highlight">
                    <pre class="highlight"><code><?php
                            if(mysqli_connect_error()){
                                echo mysqli_connect_error();
                            }else{
                                get_logs();
                            }
                            ?></code></pre>
                </div>
            </div>
    </section>
</div>


<script>
    var ajax = function (options) {
        let {url, method, body} = options
        return new Promise(function (resolve, reject) {
            let request = new XMLHttpRequest()
            request.open(method, url)
            request.send(body)
            request.onreadystatechange = () => {
                if (request.readyState === 4) {
                    if (request.status >= 200 && request.status < 300) {
                        resolve.call(undefined, request.responseText)
                    } else if (request.status >= 400) {
                        reject.call(undefined, request)
                    }
                }
            }
        })
    }
    // 修改状态
    function modify_status() {
        var res = ajax({
            'url': '?do=modify',
            'method': 'GET',
        })
        window.location = '/';
    }

    function clear_log() {
        var res = ajax({
            'url': '?do=clear',
            'method': 'GET',
        })
        window.location = '/';
    }

</script>
</body>
</html>
