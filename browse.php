<?php
/**
 * NZB.life - Browse Page
 */
require_once __DIR__ . '/auth.php';

$page_title = 'Browse';
$category = $_GET['cat'] ?? 'all';
$search_query = trim($_GET['q'] ?? '');

// Category display names
$categories = [
    'all' => 'All',
    'movies' => 'Movies',
    'movies-hd' => 'Movies HD',
    'movies-sd' => 'Movies SD',
    'movies-4k' => 'Movies 4K',
    'tv' => 'TV',
    'tv-hd' => 'TV HD',
    'tv-sd' => 'TV SD',
    'books' => 'Books',
    'books-ebook' => 'eBooks',
    'books-audio' => 'Audiobooks',
    'misc' => 'Misc',
    'misc-games' => 'Games',
    'misc-apps' => 'Apps',
    'misc-audio' => 'Music',
];

$cat_display = $categories[$category] ?? 'All';

// Sample browse data (placeholder - in production this would query a real index)
$sample_items = [];
$sample_titles = [
    'The.Adventure.of.Our.Time.2025.1080p.BluRay.x264-GROUP',
    'The.Adventure.of.Our.Time.2025.2160p.UHD.BluRay.x265-GROUP',
    'The.Adventure.of.Our.Time.2025.720p.WEB.h264-GROUP',
    'The.Adventure.of.Our.Time.2025.SDRip.XviD-GROUP',
    'The.Adventure.of.Our.Time.2025.NORDiC.1080p.WEB.H264-GROUP',
    'The.Adventure.of.Our.Time.2025.FRENCH.1080p.BluRay.x264-GROUP',
    'The.Adventure.of.Our.Time.2025.GERMAN.DL.1080p.BluRay.x264-GROUP',
    'The.Adventure.of.Our.Time.2025.iTALiAN.1080p.BluRay.x264-GROUP',
];

foreach ($sample_titles as $i => $title) {
    $sample_items[] = [
        'title' => $title,
        'category' => 'Movies > HD',
        'size' => rand(700, 4500) . ' MB',
        'posted' => date('M d, Y', strtotime("-{$i} hours")),
        'grabs' => rand(1, 150),
    ];
}

$extra_css = '<style>
    .browse-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .browse-header h1 {
        font-size: 24px;
        color: #c8c8c8;
    }
    .browse-header .results-info {
        color: #5a6a7a;
        font-size: 13px;
    }
    .cat-filters {
        display: flex;
        gap: 6px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .cat-filter {
        display: inline-block;
        padding: 4px 12px;
        font-size: 12px;
        font-family: "Share Tech Mono", monospace;
        border: 1px solid #1a3a4a;
        border-radius: 3px;
        color: #8899aa;
        background: #0d2137;
        transition: all 0.15s;
    }
    .cat-filter:hover, .cat-filter.active {
        background: rgba(0,230,138,0.1);
        border-color: #00e68a;
        color: #00e68a;
    }
    .browse-table {
        width: 100%;
        border-collapse: collapse;
    }
    .browse-table thead th {
        background: #0a1525;
        color: #5a6a7a;
        font-size: 12px;
        font-weight: normal;
        text-align: left;
        padding: 8px 12px;
        border-bottom: 1px solid #1a3a4a;
    }
    .browse-table tbody tr {
        border-bottom: 1px solid #0f2a3a;
        transition: background 0.1s;
    }
    .browse-table tbody tr:hover {
        background: rgba(0,230,138,0.03);
    }
    .browse-table td {
        padding: 10px 12px;
        font-size: 13px;
        vertical-align: middle;
    }
    .browse-table .item-title {
        color: #00e68a;
        font-size: 13px;
    }
    .browse-table .item-title:hover { color: #33ffaa; }
    .browse-table .item-meta {
        color: #5a6a7a;
        font-size: 11px;
        margin-top: 3px;
    }
    .browse-table .td-cat { color: #8899aa; font-size: 12px; }
    .browse-table .td-date { color: #5a6a7a; font-size: 12px; white-space: nowrap; }
    .browse-table .td-size { color: #8899aa; font-size: 12px; white-space: nowrap; }
    .item-icons {
        display: flex;
        gap: 6px;
    }
    .item-icons a {
        color: #3a4a5a;
        font-size: 13px;
    }
    .item-icons a:hover { color: #00e68a; }
    .browse-wrapper {
        max-width: 1100px;
        margin: 0 auto;
    }
</style>';
include __DIR__ . '/includes/header.php';
?>

    <div class="page-content">
        <div class="browse-wrapper">
        <div class="browse-header">
            <h1>Browse &raquo; <?= e($cat_display) ?></h1>
            <span class="results-info"><?= count($sample_items) ?> results</span>
        </div>

        <div class="cat-filters">
            <a href="browse.php?cat=all" class="cat-filter <?= $category === 'all' ? 'active' : '' ?>">All</a>
            <a href="browse.php?cat=movies" class="cat-filter <?= $category === 'movies' ? 'active' : '' ?>"><i class="fas fa-film"></i></a>
            <a href="browse.php?cat=tv" class="cat-filter <?= $category === 'tv' ? 'active' : '' ?>"><i class="fas fa-tv"></i></a>
            <a href="browse.php?cat=books" class="cat-filter <?= $category === 'books' ? 'active' : '' ?>"><i class="fas fa-book"></i></a>
            <a href="browse.php?cat=misc" class="cat-filter <?= $category === 'misc' ? 'active' : '' ?>"><i class="fas fa-th"></i></a>
        </div>

        <table class="browse-table">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Posted</th>
                    <th style="text-align:right;">Size</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sample_items as $item): ?>
                <tr>
                    <td>
                        <div class="item-icons">
                            <a href="#" title="Download NZB"><i class="fas fa-download"></i></a>
                        </div>
                    </td>
                    <td>
                        <a href="#" class="item-title"><?= e($item['title']) ?></a>
                        <div class="item-meta">
                            <?= $item['grabs'] ?> grabs &middot; TV > HD
                        </div>
                    </td>
                    <td class="td-cat"><?= e($item['category']) ?></td>
                    <td class="td-date"><?= e($item['posted']) ?></td>
                    <td class="td-size" style="text-align:right;"><?= e($item['size']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>

<?php include __DIR__ . '/includes/footer.php'; ?>
