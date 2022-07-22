<div class="container">
    <h1 class="page__title page__title--publication"><?=htmlspecialchars($post['header']);?></h1>
    <section class="post-details">
      <h2 class="visually-hidden">Публикация</h2>
      <div class="post-details__wrapper post-<?=$post['class_name'];?>">
        <?php if ($post['class_name'] === 'quote'):?>
        <!-- пост-цитата -->
        <div class="post-details__image-wrapper post-quote">
        <div class="post__main">
            <blockquote>
            <p>
                <?=htmlspecialchars($post['content']);?>
            </p>
            <cite><?=htmlspecialchars($post['author']);?></cite>
            </blockquote>
        </div>
        </div>
        <?php elseif($post['class_name'] === 'text'):?>
        <!-- пост-текст -->
        <div class="post-details__image-wrapper post-text">
        <div class="post__main">
            <p>
                <?=htmlspecialchars($post['content']);?>
            </p>
        </div>
        </div>
        <?php elseif($post['class_name'] === 'link'):?>
        <!-- пост-ссылка -->
        <div class="post__main">
        <div class="post-link__wrapper">
            <a class="post-link__external" href="<?=htmlspecialchars($post['content']);?>" title="Перейти по ссылке">
            <div class="post-link__info-wrapper">
                <div class="post-link__icon-wrapper">
                <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                </div>
                <div class="post-link__info">
                <h3><?=htmlspecialchars($post['header']);?></h3>
                </div>
            </div>
            </a>
        </div>
        </div>
        <?php elseif($post['class_name'] === 'photo'):?>
        <!-- пост-изображение -->
        <div class="post-details__image-wrapper post-photo__image-wrapper">
            <img src="<?=htmlspecialchars($post['content']);?>" alt="Фото от пользователя" width="760" height="507">
        </div>
        <?php elseif($post['class_name'] === 'video'):?>
        <!-- пост-видео -->
        <div class="post-details__image-wrapper post-photo__image-wrapper">
        <?=embed_youtube_video(htmlspecialchars($post['content']));?>
        </div>
        <?php endif;?>
        <div class="post-details__user user">
          <div class="post-details__user-info user__info">
            <div class="post-details__avatar user__avatar">
              <a class="post-details__avatar-link user__avatar-link" href="#">
                <img class="post-details__picture user__picture" src="img/<?=htmlspecialchars($post['avatar']);?>" alt="Аватар пользователя">
              </a>
            </div>
            <div class="post-details__name-wrapper user__name-wrapper">
              <a class="post-details__name user__name" href="#">
                <span><?=htmlspecialchars($post['name_user']);?></span>
              </a>
              <time class="post-details__time user__time" datetime="2014-03-20"><?=get_diff_date(date_create($created_at_user), "%d %s на сайте");?></time>
            </div>
          </div>
          <div class="post-details__rating user__rating">
            <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
              <span class="post-details__rating-amount user__rating-amount"><?=$count_subscriptions_user;?></span>
              <span class="post-details__rating-text user__rating-text">подписчиков</span>
            </p>
            <p class="post-details__rating-item user__rating-item user__rating-item--publications">
              <span class="post-details__rating-amount user__rating-amount"><?=$count_post_user;?></span>
              <span class="post-details__rating-text user__rating-text">публикаций</span>
            </p>
          </div>
          <div class="post-details__user-buttons user__buttons">
            <button class="user__button user__button--subscription button button--main" type="button">Подписаться</button>
            <a class="user__button user__button--writing button button--green" href="#">Сообщение</a>
          </div>
        </div>
      </div>
    </section>
</div>
