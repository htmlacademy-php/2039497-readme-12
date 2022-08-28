
<h1 class="visually-hidden">Страница результатов поиска</h1>
<section class="search">
<h2 class="visually-hidden">Результаты поиска</h2>
<div class="search__query-wrapper">
    <div class="search__query container">
    <span>Вы искали:</span>
    <span class="search__query-text"><?=htmlspecialchars($search);?></span>
    </div>
</div>
<?php if(!empty($posts_array)):?>
<div class="search__results-wrapper">
    <div class="container">
        <div class="search__content">
        <?php foreach($posts_array as $post):?>
        <article class="search__post post post-<?=$post['class_name'];?>">
        <header class="post__header post__author">
            <a class="post__author-link" href="#" title="Автор">
            <div class="post__avatar-wrapper">
                <img class="post__author-avatar" src="uploads/<?=htmlspecialchars($post['avatar']);?>" alt="Аватар пользователя" width="60" height="60">
            </div>
            <div class="post__info">
                <b class="post__author-name"><?=htmlspecialchars($post['login']);?></b>
                <span class="post__time"><?=get_diff_date(date_create(date('Y-m-d H:i:s', strtotime($post['created_at']))));?></span>
            </div>
            </a>
        </header>
        <div class="post__main">
            <?php if($post['class_name'] === 'photo'):?>
                <h2><a href="post.php?id=<?=$post['id'];?>"><?=htmlspecialchars($post['header']);?></a></h2>
                <div class="post-photo__image-wrapper">
                    <img src="<?=htmlspecialchars($post['content']);?>" alt="Фото от пользователя" width="760" height="396">
                </div>
            <?php elseif($post['class_name'] === 'text'):?>
                <h2><a href="post.php?id=<?=$post['id'];?>"><?=htmlspecialchars($post['header']);?></a></h2>
                <p>
                <?=cut_text_post(htmlspecialchars($post['content']), $post['id']);?>
                </p>
                <a class="post-text__more-link" href="post.php?id=<?=$post['id'];?>">Читать далее</a>
            <?php elseif($post['class_name'] === 'video'):?>
                <div class="post-video__block">
                    <div class="post-video__preview">
                        <?=embed_youtube_cover(htmlspecialchars($post['content']));?>
                    </div>
                    <div class="post-video__control">
                        <button class="post-video__play post-video__play--paused button button--video" type="button"><span class="visually-hidden">Запустить видео</span></button>
                        <div class="post-video__scale-wrapper">
                        <div class="post-video__scale">
                            <div class="post-video__bar">
                            <div class="post-video__toggle"></div>
                            </div>
                        </div>
                        </div>
                        <button class="post-video__fullscreen post-video__fullscreen--inactive button button--video" type="button"><span class="visually-hidden">Полноэкранный режим</span></button>
                    </div>
                    <button class="post-video__play-big button" type="button">
                        <svg class="post-video__play-big-icon" width="27" height="28">
                        <use xlink:href="#icon-video-play-big"></use>
                        </svg>
                        <span class="visually-hidden">Запустить проигрыватель</span>
                    </button>
                </div>
            <?php elseif($post['class_name'] === 'quote'):?>
                <blockquote>
                <p>
                <?=htmlspecialchars($post['content']);?>
                </p>
                <cite><?=htmlspecialchars($post['author']);?></cite>
                </blockquote>
            <?php elseif($post['class_name'] === 'link'):?>
                <div class="post-link__wrapper">
                    <a class="post-link__external" href="http://<?=htmlspecialchars($post['content']);?>" title="Перейти по ссылке">
                        <div class="post-link__icon-wrapper">
                        <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                        </div>
                        <div class="post-link__info">
                        <h3><?=htmlspecialchars($post['header']);?></h3>
                        <span><?=htmlspecialchars($post['content']);?></span>
                        </div>
                        <svg class="post-link__arrow" width="11" height="16">
                        <use xlink:href="#icon-arrow-right-ad"></use>
                        </svg>
                    </a>
                </div>
            <?php endif;?>
        </div>
        <footer class="post__footer post__indicators">
            <div class="post__buttons">
                <a class="post__indicator post__indicator--likes button" href="?like_post=<?=$post['id'];?>" title="Лайк">
                    <svg class="post__indicator-icon" width="20" height="17">
                    <use xlink:href="#icon-heart"></use>
                    </svg>
                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                    <use xlink:href="#icon-heart-active"></use>
                    </svg>
                    <span><?=$post['count_likes'];?></span>
                    <span class="visually-hidden">количество лайков</span>
                </a>
                <a class="post__indicator post__indicator--comments button" href="post.php?id=<?=$post['id'];?>" title="Комментарии">
                    <svg class="post__indicator-icon" width="19" height="17">
                    <use xlink:href="#icon-comment"></use>
                    </svg>
                    <span><?=$post['count_comment'];?></span>
                    <span class="visually-hidden">количество комментариев</span>
                </a>
                <a class="post__indicator post__indicator--repost button" href="?repost=<?=$post['id'];?>" title="Репост">
                    <svg class="post__indicator-icon" width="19" height="17">
                    <use xlink:href="#icon-repost"></use>
                    </svg>
                    <span><?=$post['count_reposts'];?></span>
                    <span class="visually-hidden">количество репостов</span>
                </a>
            </div>
        </footer>
        </article>
        <?php endforeach;?>
        </div>
    </div>
</div>
<?php else:?>
<div class="search__results-wrapper">
<div class="search__no-results container">
    <p class="search__no-results-info">К сожалению, ничего не найдено.</p>
    <p class="search__no-results-desc">
        Попробуйте изменить поисковый запрос или просто зайти в раздел &laquo;Популярное&raquo;, там живет самый крутой контент.
    </p>
    <div class="search__links">
        <a class="search__popular-link button button--main" href="popular.php">Популярное</a>
        <a class="search__back-link" href="<?=$_SERVER['HTTP_REFERER'];?>">Вернуться назад</a>
    </div>
    </div>
</div>
<?php endif;?>
</section>
