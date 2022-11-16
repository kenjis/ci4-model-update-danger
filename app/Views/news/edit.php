<h2><?= esc($title); ?></h2>

<?= validation_list_errors(); ?>

<form action="<?= site_url($controller . '/update') ?>" method="post">
    <?= csrf_field() ?>

    <label for="title">Title</label>
    <input type="input" name="title" value="<?= esc($news['title'], 'attr') ?>" size="50"><br>

    <label for="body">Text</label>
    <textarea name="body" cols="80" rows="5"><?= esc($news['body']) ?></textarea><br>

    <input type="submit" name="submit" value="Update news item">
    <input type="hidden" name="id" value="<?= esc($news['id'], 'attr') ?>">
</form>
