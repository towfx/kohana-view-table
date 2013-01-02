<?php echo $form; ?>
<p>&nbsp;</p>
<table class="table">
    <caption><?php $title ?></caption>
    <thead>
        <tr>
            <?php foreach ($columns as $column): ?>
                <th><?php echo $column->header->render() ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php if ($count): ?>
            <?php foreach ($rows as $data): ?>
                <tr class="<?php echo html::even_odd(sequence::next('table')) ?>">
                    <?php foreach ($columns as $ref => $column): ?>
                        <td <?php if ($column->cell->class): ?>class="<?php echo $column->cell->class ?>"<?php endif; ?>><?php echo $column->cell->render($data) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr class="<?php echo html::even_odd(sequence::next('table')) ?>">
                <td colspan="<?php echo count($columns) ?>" class="align-center"><?php echo __('Query returned no result.') ?></td>
            </tr>    
        <?php endif; ?>
    </tbody>
</table>

<?php echo $pagination ?>