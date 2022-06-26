<?php if(count($errors)):?>
<div class="form__invalid-block">
    <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
    <ul class="form__invalid-list">
    <?php foreach($errors as $error):?>
    <li class="form__invalid-item"><?=$error;?></li>
    <?php endforeach;?>
    </ul>
</div>
<?php endif;?>
