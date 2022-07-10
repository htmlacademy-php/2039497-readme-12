<div class="adding-post__input-wrapper form__input-wrapper">
<label class="adding-post__label form__label" for="<?=$type_content;?>-tags">Теги <span class="form__input-required">*</span></label>
<div class="form__input-section <?=get_class_error($errors, "$type_content-tags");?>">
    <input class="adding-post__input form__input" id="<?=$type_content;?>-tags" type="text" name="<?=$type_content;?>-tags" placeholder="Введите теги" value="<?=htmlspecialchars(get_post_val("$type_content-tags"));?>">
    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
    <?=include_template('form_error.php', ['text_error' => isset($errors["$type_content-tags"]) ?? '']);?>
</div>
</div>
