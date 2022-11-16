<h2><?= esc($news['title']); ?></h2>
<p><?= esc($news['body']); ?></p>

<p><a href="<?= site_url($controller . '/edit/'. $news['id']) ?>">Edit</a></p>
