<div class="page__main-section">
    <div class="container">
        <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
    </div>


    <div class="adding-post container">
        <div class="adding-post__tabs-wrapper tabs">
            <div class="adding-post__tabs filters">
                <ul class="adding-post__tabs-list filters__list tabs__list">
                <?php foreach($content_type_array as $type_content):?>
                    <li class="adding-post__tabs-item filters__item">
                    <a class="adding-post__tabs-link filters__button filters__button--<?=$type_content['class_name'];?> tabs__item button <?=get_class_active($type_content['class_name']);?>" href="/add.php?type_post=<?=$type_content['class_name'];?>">
                    <?php if($type_content['class_name'] === 'photo'):?>
                    <svg class="filters__icon" width="22" height="18">
                        <use xlink:href="#icon-filter-<?=$type_content['class_name'];?>"></use>
                    </svg>
                    <span>Фото</span>
                    <?php elseif($type_content['class_name'] === 'video'):?>
                    <svg class="filters__icon" width="24" height="16">
                        <use xlink:href="#icon-filter-video"></use>
                    </svg>
                    <span>Видео</span>
                    <?php elseif($type_content['class_name'] === 'text'):?>
                    <svg class="filters__icon" width="20" height="21">
                        <use xlink:href="#icon-filter-text"></use>
                    </svg>
                    <span>Текст</span>
                    <?php elseif($type_content['class_name'] === 'quote'):?>
                    <svg class="filters__icon" width="21" height="20">
                        <use xlink:href="#icon-filter-quote"></use>
                    </svg>
                    <span>Цитата</span>
                    <?php elseif($type_content['class_name'] === 'link'):?>
                    <svg class="filters__icon" width="21" height="18">
                        <use xlink:href="#icon-filter-link"></use>
                    </svg>
                    <span>Ссылка</span>
                    <?php endif;?>
                    </a>
                </li>
                <?php endforeach;?>
                </ul>
            </div>
            <div class="adding-post__tab-content">
                <?php foreach($content_type_array as $type_content):?>
                    <section class="adding-post__<?=$type_content['class_name'];?> tabs__content <?=get_class_active($type_content['class_name'], "section");?>">
                    <?php if($type_content['class_name'] === 'photo'):?>
                        <h2 class="visually-hidden">Форма добавления фото</h2>
                        <form class="adding-post__form form" action="add.php?type_post=photo" method="post" enctype="multipart/form-data">
                    <?php elseif($type_content['class_name'] === 'video'):?>
                        <h2 class="visually-hidden">Форма добавления видео</h2>
                        <form class="adding-post__form form" action="add.php?type_post=video" method="post" enctype="multipart/form-data">
                    <?php elseif($type_content['class_name'] === 'quote'):?>
                        <h2 class="visually-hidden">Форма добавления цитаты</h2>
                        <form class="adding-post__form form" action="add.php?type_post=quote" method="post">
                    <?php elseif($type_content['class_name'] === 'link'):?>
                        <h2 class="visually-hidden">Форма добавления ссылки</h2>
                        <form class="adding-post__form form" action="add.php?type_post=link" method="post">
                    <?php elseif($type_content['class_name'] === 'text'):?>
                        <h2 class="visually-hidden">Форма добавления текста</h2>
                        <form class="adding-post__form form" action="add.php?type_post=text" method="post">
                    <?php endif;?>
                        <div class="form__text-inputs-wrapper">
                            <div class="form__text-inputs">
                                <?=include_template('form_header.php', ['type_content' => $type_content['class_name'], 'errors' => $errors]);?>
                                <?php if($type_content['class_name'] === 'photo'):?>
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                    <label class="adding-post__label form__label" for="photo-url">Ссылка из интернета</label>
                                    <div class="form__input-section <?=get_class_error($errors, 'photo');?>">
                                        <input class="adding-post__input form__input" id="photo-url" type="text" name="photo" placeholder="Введите ссылку" value="<?=htmlspecialchars(get_post_val('photo'));?>">
                                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                        <?=include_template('form_error.php', ['text_error' => $errors['photo'] ?? '']);?>
                                    </div>
                                    </div>
                                <?php elseif($type_content['class_name'] === 'video'):?>
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                    <label class="adding-post__label form__label" for="video-url">Ссылка youtube <span class="form__input-required">*</span></label>
                                    <div class="form__input-section <?=get_class_error($errors, 'video');?>">
                                        <input class="adding-post__input form__input" id="video-url" type="text" name="video" placeholder="Введите ссылку" value="<?=htmlspecialchars(get_post_val('video'));?>">
                                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                        <?=include_template('form_error.php', ['text_error' => $errors['video'] ?? '']);?>
                                    </div>
                                    </div>
                                <?php elseif($type_content['class_name'] === 'quote'):?>
                                    <div class="adding-post__input-wrapper form__textarea-wrapper">
                                    <label class="adding-post__label form__label" for="cite-text">Текст цитаты <span class="form__input-required">*</span></label>
                                    <div class="form__input-section <?=get_class_error($errors, 'quote');?>">
                                        <textarea class="adding-post__textarea adding-post__textarea--quote form__textarea form__input" id="cite-text" name="quote" placeholder="Текст цитаты"><?=htmlspecialchars(get_post_val('quote'));?></textarea>
                                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                        <?=include_template('form_error.php', ['text_error' => $errors['quote'] ?? '']);?>
                                    </div>
                                    </div>
                                    <div class="adding-post__textarea-wrapper form__input-wrapper">
                                    <label class="adding-post__label form__label" for="quote-author">Автор <span class="form__input-required">*</span></label>
                                    <div class="form__input-section <?=get_class_error($errors, 'quote-author');?>">
                                        <input class="adding-post__input form__input" id="quote-author" type="text" name="quote-author" value="<?=htmlspecialchars(get_post_val('quote-author'));?>">
                                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                        <?=include_template('form_error.php', ['text_error' => $errors['quote-author'] ?? '']);?>
                                    </div>
                                    </div>
                                <?php elseif($type_content['class_name'] === 'link'):?>
                                    <div class="adding-post__textarea-wrapper form__input-wrapper">
                                    <label class="adding-post__label form__label" for="post-link">Ссылка <span class="form__input-required">*</span></label>
                                    <div class="form__input-section <?=get_class_error($errors, 'link');?>">
                                        <input class="adding-post__input form__input" id="post-link" type="text" name="link" value="<?=htmlspecialchars(get_post_val('link'));?>">
                                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                        <?=include_template('form_error.php', ['text_error' => $errors['link'] ?? '']);?>
                                    </div>
                                    </div>
                                <?php elseif($type_content['class_name'] === 'text'):?>
                                    <div class="adding-post__textarea-wrapper form__textarea-wrapper">
                                    <label class="adding-post__label form__label" for="post-text">Текст поста <span class="form__input-required">*</span></label>
                                    <div class="form__input-section <?=get_class_error($errors, 'text');?>">
                                        <textarea class="adding-post__textarea form__textarea form__input" id="post-text" name="text" placeholder="Введите текст публикации"><?=htmlspecialchars(get_post_val('text'));?></textarea>
                                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                        <?=include_template('form_error.php', ['text_error' => $errors['text'] ?? '']);?>
                                    </div>
                                    </div>
                                <?php endif;?>
                                <?=include_template('form_tags.php', ['type_content' => $type_content['class_name'], 'errors' => $errors]);?>
                                <div><input type="text" name="type-content" hidden value="<?=$type_content['class_name'];?>"></div>
                            </div>
                            <?=include_template('form_errors.php', ['errors' => $errors]);?>
                        </div>
                            <?php if($type_content['class_name'] === 'photo'):?>
                                <div class="adding-post__input-file-container form__input-container form__input-container--file">
                                <div class="adding-post__input-file-wrapper form__input-file-wrapper">
                                    <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
                                    <input class="adding-post__input-file form__input-file <?=get_class_error($errors, 'userpic-file-photo');?>" id="userpic-file-photo" type="file" name="userpic-file-photo" title=" ">
                                    <div class="form__file-zone-text">
                                        <span>Перетащите фото сюда</span>
                                    </div>
                                    </div>
                                    <!-- <button class="adding-post__input-file-button form__input-file-button form__input-file-button--photo button" type="button">
                                    <span>Выбрать фото</span>
                                    <svg class="adding-post__attach-icon form__attach-icon" width="10" height="20">
                                        <use xlink:href="#icon-attach"></use>
                                    </svg>
                                    </button> -->
                                    <label for="userpic-file-photo" class="adding-post__input-file-button form__input-file-button form__input-file-button--photo button" type="button">
                                    <span>Выбрать фото</span>
                                    <svg class="adding-post__attach-icon form__attach-icon" width="10" height="20">
                                        <use xlink:href="#icon-attach"></use>
                                    </svg>
                                    </label>
                                </div>
                                <div class="adding-post__file adding-post__file--photo form__file dropzone-previews">

                                </div>
                                </div>
                            <?php endif;?>
                            <?=include_template('form_submit.php');?>
                        </form>
                    </section>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</div>














