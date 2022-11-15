<h2><?= esc($news['title']); ?></h2>
<p><?= esc($news['body']); ?></p>

<p><a href="<?= base_url('news/edit/'. $news['id']) ?>">Edit</a></p>
