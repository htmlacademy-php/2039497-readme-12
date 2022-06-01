<div class="container">
        <h1 class="page__title page__title--popular">Популярное</h1>
    </div>
    <div class="popular container">
        <div class="popular__filters-wrapper">
            <div class="popular__sorting sorting">
                <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
                <ul class="popular__sorting-list sorting__list">
                    <li class="sorting__item sorting__item--popular">
                        <a class="sorting__link sorting__link--active" href="#">
                            <span>Популярность</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Лайки</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Дата</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="popular__filters filters">
                <b class="popular__filters-caption filters__caption">Тип контента:</b>
                <ul class="popular__filters-list filters__list">
                    <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                        <a class="filters__button filters__button--ellipse filters__button--all filters__button--active" href="#">
                            <span>Все</span>
                        </a>
                    </li>
                    <?php foreach($content_type_array as $type_content):?>
                    <li class="popular__filters-item filters__item">
                        <a class="filters__button filters__button--<?=$type_content['class_name'];?> button" href="#">
                        <?php if($type_content['type'] === 'Картинка'):?>
                            <span class="visually-hidden">Фото</span>
                            <svg class="filters__icon" width="22" height="18">
                        <?php elseif($type_content['type'] === 'Цитата'):?>
                            <span class="visually-hidden">Цитата</span>
                            <svg class="filters__icon" width="21" height="20">                        
                        <?php elseif($type_content['type'] === 'Ссылка'):?>
                            <span class="visually-hidden">Ссылка</span>
                            <svg class="filters__icon" width="21" height="18">
                        <?php elseif($type_content['type'] === 'Видео'):?>
                            <span class="visually-hidden">Видео</span>
                            <svg class="filters__icon" width="24" height="16">
                        <?php elseif($type_content['type'] === 'Текст'):?>
                            <span class="visually-hidden">Текст</span>
                            <svg class="filters__icon" width="20" height="21">
                        <?php endif;?>
                            <use xlink:href="#icon-filter-<?=$type_content['class_name'];?>"></use>
                            </svg>
                        </a>
                    </li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
        <div class="popular__posts">
            <?php foreach($posts_array as $idx => $post):?>
                <?php $date = generate_random_date($idx);?>
                <article class="popular__post post post-<?=$post['class_name'];?>">
                    <header class="post__header">
                        <h2><!--здесь заголовок--><?=htmlspecialchars($post['header']);?></h2>
                    </header>
                    <div class="post__main">
                        <!--здесь содержимое карточки-->
                        <?php if($post['type'] === 'Цитата'):?>
                            <!--содержимое для поста-цитаты-->
                            <blockquote>
                                <p>
                                    <!--здесь текст-->
                                    <?=htmlspecialchars($post['content']);?>
                                </p>
                                <cite>Неизвестный Автор</cite>
                            </blockquote>

                        <?php elseif($post['type'] === 'Ссылка'):?>
                            <!--содержимое для поста-ссылки-->
                            <div class="post-link__wrapper">
                                <a class="post-link__external" href="http://" title="Перейти по ссылке">
                                    <div class="post-link__info-wrapper">
                                        <div class="post-link__icon-wrapper">
                                            <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                                        </div>
                                        <div class="post-link__info">
                                            <h3><!--здесь заголовок--><?=htmlspecialchars($post['header']);?></h3>
                                        </div>
                                    </div>
                                    <span><!--здесь ссылка--><?=$post['content'];?></span>
                                </a>
                            </div>

                        <?php elseif($post['type'] === 'Картинка'):?>
                            <!--содержимое для поста-фото-->
                            <div class="post-photo__image-wrapper">
                                <img src="img/<?=$post['content'];?>" alt="Фото от пользователя" width="360" height="240">
                            </div>

                        <?php elseif($post['type'] === 'Видео'): ?>
                            <!--содержимое для поста-видео-->
                            <div class="post-video__block">
                                <div class="post-video__preview">
                                    <?=embed_youtube_cover(/* вставьте ссылку на видео */$post['content']);?>
                                </div>
                                <a href="post-details.html" class="post-video__play-big button">
                                    <svg class="post-video__play-big-icon" width="14" height="14">
                                        <use xlink:href="#icon-video-play-big"></use>
                                    </svg>
                                    <span class="visually-hidden">Запустить проигрыватель</span>
                                </a>
                            </div>
                        <?php elseif($post['type'] === 'Текст'):?>
                            <!--содержимое для поста-текста-->
                            <!--здесь текст--><?=cut_text_post(htmlspecialchars($post['content']));?>
                        <?php endif;?>
                    </div>
                    <footer class="post__footer">
                        <div class="post__author">
                            <a class="post__author-link" href="#" title="Автор">
                                <div class="post__avatar-wrapper">
                                    <!--укажите путь к файлу аватара-->
                                    <img class="post__author-avatar" src="img/<?=$post['avatar'];?>" alt="Аватар пользователя">
                                </div>
                                <div class="post__info">
                                    <b class="post__author-name"><!--здесь имя пользоателя--><?=$post['name_user'];?></b>
                                    <time class="post__time" datetime="<?=$date;?>" title="<?=date('d.m.Y H:i', strtotime($date));?>"><?=get_diff_date(date_create($date));?></time>
                                </div>
                            </a>
                        </div>
                        <div class="post__indicators">
                            <div class="post__buttons">
                                <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                                    <svg class="post__indicator-icon" width="20" height="17">
                                        <use xlink:href="#icon-heart"></use>
                                    </svg>
                                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                        <use xlink:href="#icon-heart-active"></use>
                                    </svg>
                                    <span>0</span>
                                    <span class="visually-hidden">количество лайков</span>
                                </a>
                                <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                                    <svg class="post__indicator-icon" width="19" height="17">
                                        <use xlink:href="#icon-comment"></use>
                                    </svg>
                                    <span>0</span>
                                    <span class="visually-hidden">количество комментариев</span>
                                </a>
                            </div>
                        </div>
                    </footer>
                </article>
            <?php endforeach;?>
        </div>
    </div>
