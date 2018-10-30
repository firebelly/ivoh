<?php if (!empty($intro_links)): ?>
	<p class="intro-links">
		<?php foreach ($intro_links as $link): ?>
			<a href="<?= $link['url'] ?>" class="button"><?= $link['link_text'] ?></a>
		<?php endforeach; ?>
	</p>
<?php endif ?>
