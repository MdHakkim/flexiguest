<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="<?= lang('Pager.pageNavigation') ?>">
	<ul class="pagination pagination-secondary">
		<?php if ($pager->hasPrevious()) : ?>
			<li class="page-item first">
				<a class="page-link" href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>">
				<i class="tf-icon bx bx-chevrons-left"></i>
				</a>
			</li>
			<li class="page-item prev">
				<a class="page-link" href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>">
				<i class="tf-icon bx bx-chevron-left"></i>
				</a>
			</li>
		<?php endif ?>

		<?php foreach ($pager->links() as $link) : ?>
			<li class="page-item <?= $link['active'] ? 'active' : '' ?>">
				<a class="page-link" href="<?= $link['uri'] ?>">
					<?= $link['title'] ?>
				</a>
			</li>
		<?php endforeach ?>

		<?php if ($pager->hasNext()) : ?>
			<li class="page-item next">
				<a class="page-link" href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>">
				<i class="tf-icon bx bx-chevron-right"></i>
				</a>
			</li>
			<li class="page-item last">
				<a class="page-link" href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>">
				<i class="tf-icon bx bx-chevrons-right"></i>
				</a>
			</li>
		<?php endif ?>
	</ul>
</nav>
