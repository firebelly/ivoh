<?php if (!empty($intro_links)): ?>
	<p class="intro-links">
		<?php foreach ($intro_links as $link): ?>
			<?php
			// External link?
			$external_link = (!preg_match('/ivoh\.org/', $link['url']) && preg_match('/^http/', $link['url'])) ? 'rel="noopener" target="_blank" ' : '';
		  ?>
			<a <?= $external_link ?>href="<?= $link['url'] ?>" class="button"><?= $link['link_text'] ?></a>
		<?php endforeach; ?>
	</p>
<?php endif ?>
