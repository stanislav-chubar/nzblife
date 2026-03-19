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
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: #3dff9e;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #060a10;
            background: linear-gradient(160deg, #060a10 0%, #071009 50%, #060a10 80%, #070d0b 100%);
            background-attachment: fixed;
            font-family: "JetBrains Mono", "Fira Code", "SFMono-Regular", Menlo, Consolas, "Liberation Mono", monospace;
            font-size: 14px;
            color: #c8c8c8;
            min-height: 100vh;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        ::-webkit-scrollbar-track {
            background: #0b140d;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(75, 178, 89, 0.9) 0%, rgba(55, 138, 68, 0.9) 100%);
            border: 2px solid #0b140d;
            border-radius: 10px;
        }
        a { color: #5ad7ff; text-decoration: none; }
        a:hover {
            color: var(--accent);
            text-shadow: 0 0 12px rgba(61, 255, 158, 0.35);
        }

        /* Guest home layout */
        .guest-home {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 30px 60px;
            position: relative;
        }

        /* Content card */
        .guest-card {
            border: 1px solid rgba(93, 255, 180, 0.24);
            border-radius: 6px;
            padding: 36px 40px;
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
        .guest-toplinks a {
            background: linear-gradient(180deg, rgba(17, 26, 39, 0.95), rgba(11, 17, 26, 0.96));
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #c8c8c8;
            padding: 6px 18px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 13px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.15s;
        }
        .guest-toplinks a:hover {
            background: linear-gradient(180deg, rgba(16, 40, 20, 0.96) 0%, rgba(10, 26, 13, 0.96) 100%);
            border: 1px solid rgba(100, 210, 114, 0.42);
            color: var(--accent);
            text-shadow: none;
        }
        .guest-toplinks .btn-login {
            background: linear-gradient(180deg, rgba(17, 26, 39, 0.95), rgba(11, 17, 26, 0.96));
            color: #5ad7ff;
        }

        /* Info table */
        .infobox {
            border-collapse: collapse;
            margin-bottom: 24px;
            width: auto;
        }
        .infobox caption {
            text-align: left;
            font-size: 20px;
            color: #d7fbe6;
            padding-bottom: 16px;
        }
        .infobox th {
            text-align: left;
            color: #5ad7ff;
            font-weight: normal;
            padding: 4px 20px 4px 0;
            font-size: 13px;
            vertical-align: top;
            white-space: nowrap;
        }
        .infobox th a {
            color: #5ad7ff;
        }
        .infobox th a:hover {
            color: var(--accent);
            text-shadow: 0 0 12px rgba(61, 255, 158, 0.35);
        }
        .infobox td {
            color: #d7fbe6;
            padding: 4px 0;
            font-size: 13px;
            vertical-align: top;
        }
        .infobox td code {
            color: #d7fbe6 !important;
            background: rgba(6, 14, 7, 0.98) !important;
            border: 1px solid rgba(95, 206, 108, 0.32) !important;
            border-radius: 3px;
            padding: 2px 8px;
            font-size: 13px;
            display: inline-block;
        }
        .infobox td a { color: #5ad7ff; }
        .infobox td a:hover {
            color: var(--accent);
            text-shadow: 0 0 12px rgba(61, 255, 158, 0.35);
        }

        /* Body content */
        .guest-index-body {
            color: #d7fbe6;
            line-height: 1.7;
            font-size: 16px;
            margin-top: 0;
            padding: 2px 4px 6px;
        }
        .content-body p {
            color: #d7fbe6;
            line-height: 1.7;
            margin-bottom: 16px;
            font-size: 16px;
        }
        .content-body p a { color: #5ad7ff; }
        .content-body p a:hover {
            color: var(--accent);
            text-shadow: 0 0 12px rgba(61, 255, 158, 0.35);
        }
        .content-body p b, .content-body p strong {
            color: #fff;
        }

        .content-body h2 {
            color: #d7fbe6;
            font-size: 24px;
            font-weight: normal;
            margin: 28px 0 14px;
        }
        .content-body h2 a {
            color: #5a6a7a;
            font-size: 13px;
        }

        /* Code block */
        code, pre, kbd, samp {
            background: rgba(6, 14, 7, 0.98) !important;
            border: 1px solid rgba(95, 206, 108, 0.32) !important;
            color: #9dffaf !important;
            font-family: Menlo, Monaco, Consolas, "Courier New", monospace;
        }
        pre {
            display: block;
            padding: 9.5px;
            margin: 0 0 10px;
            font-size: 13px;
            line-height: 1.42857143;
            word-break: break-all;
            word-wrap: break-word;
            border-radius: 4px;
            overflow: auto;
        }
        .code-block {
            background: rgba(6, 14, 7, 0.98);
            border: 1px solid rgba(95, 206, 108, 0.32);
            border-radius: 4px;
            padding: 0;
            overflow: auto;
        }
        .code-block pre {
            margin: 0;
            border: none !important;
        }
        .cp { color: #9dffaf; }
        .nt { color: #9dffaf; }
        .na { color: #9dffaf; }
        .s  { color: #9dffaf; }
    </style>
</head>
<body>

    <!-- Top-right auth buttons -->
    <div class="guest-toplinks">
        <a href="login.php" class="btn-login">Login</a>
        <a href="register.php" class="btn-register">Register</a>
    </div>

    <div class="guest-home">
        <div class="guest-card">
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

            <div class="code-block"><pre>
 xmlns="http://www.newzbin.com/DTD/2003/nzb">

    type="title">Your File!
    type="tag">Example

  poster="Joe Bloggs " date="1071674882" subject="Here's your file!  abc-mr2a.r01 (1/2)">

     alt.binaries.newzbin
     alt.binaries.mojo


      bytes="102394" number="1">123456789abcdef@news.newzbin.com
      bytes="4501" number="2">987654321fedbca@news.newzbin.com
</pre>
            </div>

        </div>
        </div>
    </div>

</body>
</html>
