<?php
/**
 * NZB.life - Home / Landing Page (Public)
 */
require_once __DIR__ . '/config.php';
ensure_session();

// If logged in, redirect to dashboard
if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$page_title = 'Home';
$hide_notification = true;
$extra_css = '<style>
    .home-content {
        max-width: 1200px;
        margin: 30px auto;
        padding: 0 20px;
    }
    .info-card {
        background: #0d2137;
        border: 1px solid #1a3a4a;
        border-radius: 6px;
        padding: 30px 35px;
    }
    .info-card h2 {
        color: #c8c8c8;
        font-size: 22px;
        margin-bottom: 24px;
    }
    .info-table {
        margin-bottom: 24px;
    }
    .info-table .info-row {
        display: flex;
        padding: 4px 0;
        font-size: 14px;
    }
    .info-row .info-label {
        color: #00e68a;
        min-width: 200px;
        font-weight: bold;
    }
    .info-row .info-value {
        color: #8899aa;
    }
    .info-row .info-value a {
        color: #00e68a;
    }
    .info-card p {
        color: #8899aa;
        line-height: 1.8;
        margin-bottom: 12px;
        font-size: 14px;
    }
    .info-card h3 {
        color: #c8c8c8;
        font-size: 18px;
        margin: 24px 0 16px;
    }
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
    .code-block .tag { color: #00e68a; }
    .code-block .attr { color: #5599cc; }
    .code-block .val { color: #e6db74; }
    .code-block .comment { color: #556677; }
</style>';
include __DIR__ . '/includes/header.php';
?>

    <div class="home-content">
        <div class="info-card">
            <h2>NZB file format</h2>

            <div class="info-table">
                <div class="info-row">
                    <span class="info-label">Filename extension</span>
                    <span class="info-value">.nzb</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Internet media type</span>
                    <span class="info-value">application/x-nzb</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Developed by</span>
                    <span class="info-value"><a href="#">newzbin</a> (was)</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Initial release</span>
                    <span class="info-value">1.0</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Latest release</span>
                    <span class="info-value">1.1 / January 13, 2009; ??</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Type of format</span>
                    <span class="info-value">open, xml</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Extended from</span>
                    <span class="info-value">XML</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Open format?</span>
                    <span class="info-value">Yes</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Website</span>
                    <span class="info-value"><a href="#">NZB file specification</a></span>
                </div>
            </div>

            <p>NZB is an XML-based file format for retrieving posts from NNTP (Usenet) servers. The format was conceived by the developers of the newzbin.com Usenet index. NZB is effective when used with search/index websites. These specify certain NZB files and of what is needed to be downloaded. Using this concept, headers need not be downloaded hence the NZB method is quicker and more bandwidth-efficient than traditional methods.</p>

            <p>Each Usenet message has a unique identifier called the "Message-ID". When a large file is posted to a Usenet newsgroup, it is usually divided into multiple messages (called segments or parts) each having its own Message-ID. An NZB file usually stores all segment Message-IDs of the entire NZB file, download them and decode the messages back into a binary file (locally) using yEnc or UUencode.</p>

            <h3>File format example[edit]</h3>

            <p>The following is an example of an NZB 1.1 file.</p>

            <div class="code-block">
<span class="tag">&lt;?xml</span> <span class="attr">version</span>=<span class="val">"1.0"</span> <span class="attr">encoding</span>=<span class="val">"iso-8859-1"</span> <span class="tag">?&gt;</span>
<span class="tag">&lt;!DOCTYPE</span> nzb PUBLIC <span class="val">"-//newzBin//DTD NZB 1.1//EN"</span> <span class="val">"http://www.newzbin.com/DTD/nzb/nzb-1.1.dtd"</span><span class="tag">&gt;</span>
<span class="tag">&lt;nzb</span> <span class="attr">xmlns</span>=<span class="val">"http://www.newzbin.com/DTD/2003/nzb"</span><span class="tag">&gt;</span>

  <span class="tag">&lt;head&gt;</span>
    <span class="tag">&lt;meta</span> <span class="attr">type</span>=<span class="val">"title"</span><span class="tag">&gt;</span>Your File!<span class="tag">&lt;/meta&gt;</span>
    <span class="tag">&lt;meta</span> <span class="attr">type</span>=<span class="val">"password"</span><span class="tag">&gt;</span>secret<span class="tag">&lt;/meta&gt;</span>
  <span class="tag">&lt;/head&gt;</span>

  <span class="comment">&lt;!-- Example segments --&gt;</span>
  <span class="tag">&lt;file</span> <span class="attr">poster</span>=<span class="val">"Joe Bloggs &amp;lt;user@example.com&amp;gt;"</span>
        <span class="attr">date</span>=<span class="val">"1071674882"</span>
        <span class="attr">subject</span>=<span class="val">"Here's your file! doc.rar (1/2)"</span><span class="tag">&gt;</span>
    <span class="tag">&lt;groups&gt;</span>
      <span class="tag">&lt;group&gt;</span>alt.binaries.example<span class="tag">&lt;/group&gt;</span>
    <span class="tag">&lt;/groups&gt;</span>
    <span class="tag">&lt;segments&gt;</span>
      <span class="tag">&lt;segment</span> <span class="attr">bytes</span>=<span class="val">"102394"</span> <span class="attr">number</span>=<span class="val">"1"</span><span class="tag">&gt;</span>123456789abcdef@example.com<span class="tag">&lt;/segment&gt;</span>
      <span class="tag">&lt;segment</span> <span class="attr">bytes</span>=<span class="val">"4501"</span> <span class="attr">number</span>=<span class="val">"2"</span><span class="tag">&gt;</span>987654321fedbca@example.com<span class="tag">&lt;/segment&gt;</span>
    <span class="tag">&lt;/segments&gt;</span>
  <span class="tag">&lt;/file&gt;</span>

<span class="tag">&lt;/nzb&gt;</span>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/includes/footer.php'; ?>
