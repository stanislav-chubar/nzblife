<?php
/**
 * NZB.life - Home / Landing Page (Public Guest Page)
 * No navbar - just Login/Register buttons top-right + NZB info content
 */
require_once __DIR__ . '/config.php';
ensure_session();

// If logged in, redirect to dashboard
if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="nzb.life,usenet,nzb,indexing,content">
    <meta name="description" content="NZB indexing for usenet servers">
    <title><?= e(SITE_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #0b1a2a;
            font-family: 'Share Tech Mono', 'Courier New', monospace;
            font-size: 14px;
            color: #c8c8c8;
            min-height: 100vh;
        }
        a { color: #00e68a; text-decoration: none; }
        a:hover { color: #33ffaa; }

        /* Guest home layout */
        .guest-home {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 30px 60px;
            position: relative;
        }

        /* Top-right Login/Register buttons */
        .guest-toplinks {
            position: fixed;
            top: 16px;
            right: 24px;
            display: flex;
            gap: 8px;
            z-index: 100;
        }
        .guest-toplinks .btn-login {
            background: #00e68a;
            color: #0b1a2a;
            padding: 6px 18px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 13px;
            font-weight: bold;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        .guest-toplinks .btn-login:hover { background: #33ffaa; color: #0b1a2a; }
        .guest-toplinks .btn-register {
            background: transparent;
            border: 1px solid #8899aa;
            color: #8899aa;
            padding: 6px 18px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 13px;
            border-radius: 4px;
        }
        .guest-toplinks .btn-register:hover { border-color: #00e68a; color: #00e68a; }

        /* Info table */
        .infobox {
            border-collapse: collapse;
            margin-bottom: 24px;
            width: auto;
        }
        .infobox caption {
            text-align: left;
            font-size: 20px;
            color: #c8c8c8;
            padding-bottom: 16px;
        }
        .infobox th {
            text-align: left;
            color: #00e68a;
            font-weight: normal;
            padding: 4px 20px 4px 0;
            font-size: 13px;
            vertical-align: top;
            white-space: nowrap;
        }
        .infobox td {
            color: #8899aa;
            padding: 4px 0;
            font-size: 13px;
            vertical-align: top;
        }
        .infobox td code {
            color: #c8c8c8;
        }
        .infobox td a { color: #00e68a; }

        /* Body content */
        .content-body p {
            color: #8899aa;
            line-height: 1.9;
            margin-bottom: 16px;
            font-size: 13px;
        }
        .content-body p a { color: #00e68a; }
        .content-body p b, .content-body p strong {
            color: #c8c8c8;
        }

        .content-body h2 {
            color: #c8c8c8;
            font-size: 18px;
            font-weight: normal;
            margin: 28px 0 14px;
        }
        .content-body h2 a {
            color: #5a6a7a;
            font-size: 13px;
        }

        /* Code block */
        .code-block {
            background: #060e18;
            border: 1px solid #1a3a4a;
            border-radius: 4px;
            padding: 20px;
            overflow-x: auto;
            font-size: 13px;
            line-height: 1.7;
            color: #8899aa;
        }
        .cp { color: #00e68a; }
        .nt { color: #00e68a; }
        .na { color: #5599cc; }
        .s  { color: #e6db74; }
    </style>
</head>
<body>

    <!-- Top-right auth buttons -->
    <div class="guest-toplinks">
        <a href="login.php" class="btn-login">Login</a>
        <a href="register.php" class="btn-register">Register</a>
    </div>

    <div class="guest-home">
        <div class="content-body">

            <table class="infobox">
                <caption>NZB file format</caption>
                <tbody>
                    <tr>
                        <th><a href="#">Filename extension</a></th>
                        <td><code>.nzb</code></td>
                    </tr>
                    <tr>
                        <th><a href="#">Internet media type</a></th>
                        <td><code>application/x-nzb</code></td>
                    </tr>
                    <tr>
                        <th>Developed by</th>
                        <td><a href="#">NewzBin</a></td>
                    </tr>
                    <tr>
                        <th><a href="#">Latest release</a></th>
                        <td>1.1<br><span style="font-size:12px; color:#5a6a7a;">(November 10, 2009; 7 years ago)</span></td>
                    </tr>
                    <tr>
                        <th>Type of format</th>
                        <td><a href="#">Usenet</a> extender</td>
                    </tr>
                    <tr>
                        <th>Extended from</th>
                        <td><a href="#">XML</a></td>
                    </tr>
                    <tr>
                        <th><a href="#">Open format</a>?</th>
                        <td>yes</td>
                    </tr>
                    <tr>
                        <th>Website</th>
                        <td><a href="#">NZB file specification</a></td>
                    </tr>
                </tbody>
            </table>

            <p><strong>NZB</strong> is an <a href="#">XML</a>-based file format for retrieving posts from <a href="#">NNTP</a> (<a href="#">Usenet</a>) servers. The format was conceived by the developers of the <a href="#">Newzbin.com</a> <a href="#">Usenet</a> Index. NZB is effective when used with search-capable websites. These websites create NZB files out of what is needed to be downloaded. Using this concept, headers would not be downloaded hence the NZB method is quicker and more bandwidth-efficient than traditional methods.</p>

            <p>Each Usenet message has a unique identifier called the "<a href="#">Message-ID</a>". When a large file is posted to a Usenet newsgroup, it is usually divided into multiple messages (called segments or parts) each having its own Message-ID. An NZB-capable Usenet client will read all needed Message-IDs from the NZB file, download them and decode the messages back into a binary file (usually using <a href="#">yEnc</a> or <a href="#">Uuencode</a>).</p>

            <h2>File format example[<a href="#">edit</a>]</h2>

            <p>The following is an example of an NZB 1.1 file.</p>

            <div class="code-block"><pre><span class="cp">&lt;?xml version="1.0" encoding="iso-8859-1" ?&gt;</span>
<span class="cp">&lt;!DOCTYPE nzb PUBLIC "-//newzBin//DTD NZB 1.1//EN" "http://www.newzbin.com/DTD/nzb/nzb-1.1.dtd"&gt;</span>
<span class="nt">&lt;nzb</span> <span class="na">xmlns=</span><span class="s">"http://www.newzbin.com/DTD/2003/nzb"</span><span class="nt">&gt;</span>
 <span class="nt">&lt;head&gt;</span>
   <span class="nt">&lt;meta</span> <span class="na">type=</span><span class="s">"title"</span><span class="nt">&gt;</span>Your File!<span class="nt">&lt;/meta&gt;</span>
   <span class="nt">&lt;meta</span> <span class="na">type=</span><span class="s">"tag"</span><span class="nt">&gt;</span>Example<span class="nt">&lt;/meta&gt;</span>
 <span class="nt">&lt;/head&gt;</span>
 <span class="nt">&lt;file</span> <span class="na">poster=</span><span class="s">"Joe Bloggs &lt;bloggs@nowhere.example&gt;"</span> <span class="na">date=</span><span class="s">"1071674882"</span> <span class="na">subject=</span><span class="s">"Here's your file!  abc-mr2a.r01 (1/2)"</span><span class="nt">&gt;</span>
   <span class="nt">&lt;groups&gt;</span>
     <span class="nt">&lt;group&gt;</span>alt.binaries.newzbin<span class="nt">&lt;/group&gt;</span>
     <span class="nt">&lt;group&gt;</span>alt.binaries.mojo<span class="nt">&lt;/group&gt;</span>
   <span class="nt">&lt;/groups&gt;</span>
   <span class="nt">&lt;segments&gt;</span>
     <span class="nt">&lt;segment</span> <span class="na">bytes=</span><span class="s">"102394"</span> <span class="na">number=</span><span class="s">"1"</span><span class="nt">&gt;</span>123456789abcdef@news.newzbin.com<span class="nt">&lt;/segment&gt;</span>
     <span class="nt">&lt;segment</span> <span class="na">bytes=</span><span class="s">"4501"</span> <span class="na">number=</span><span class="s">"2"</span><span class="nt">&gt;</span>987654321fedbca@news.newzbin.com<span class="nt">&lt;/segment&gt;</span>
   <span class="nt">&lt;/segments&gt;</span>
 <span class="nt">&lt;/file&gt;</span>
<span class="nt">&lt;/nzb&gt;</span></pre>
            </div>

        </div>
    </div>

</body>
</html>
