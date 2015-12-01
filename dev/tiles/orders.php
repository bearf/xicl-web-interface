<?php function content($data) { ?>
<?php $orders = _data('orders'); ?>
<?php $rowcount = count($orders); ?>
<?php if (0 == $rowcount): ?>
<p class="message">Заявок пока нет</p>
<?php else: ?>
<h3>заявки</h3>
<table>
<tr><th style="width:50px;">#</th>
    <th style="width:150px;">дата</th>
    <th>команда</th>
    <th style="width:250px;">место обучения</th></tr>
<?php $index = 0; ?>
<?php while (list($key, $f) = each($orders)): //выводим строки ?>
    <?php $index++; ?>
<tr <?=$index%2==0 ? 'class="s"' : ''?>>
    <td><?=$index?></td>
    <td><?=$f->orderdate?></td>
    <td><a href="./order.php?orderid=<?=$f->orderId?>"><?=$f->teamname?></a></td>
    <td><?=$f->studyplace?></td></tr>
<?php endwhile; //конец пробега по строкам ?>
</table>
<?php endif; // конец чека на наличие записей ?>
<?php } ?>

