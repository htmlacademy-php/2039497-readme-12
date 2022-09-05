<div class="container">
    <h1 class="page__title page__title--registration">Регистрация</h1>
</div>
<section class="registration container">
    <h2 class="visually-hidden">Форма регистрации</h2>
    <form class="registration__form form" action="registration.php" method="post" enctype="multipart/form-data">
        <div class="form__text-inputs-wrapper">
        <div class="form__text-inputs">
            <div class="registration__input-wrapper form__input-wrapper">
            <label class="registration__label form__label" for="registration-email">Электронная почта <span class="form__input-required">*</span></label>
            <div class="form__input-section">
                <input class="registration__input form__input" id="registration-email" type="email" name="email" placeholder="Укажите эл.почту" value="<?=htmlspecialchars(get_post_val('email'));?>">
                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                <?=include_template('form_error.php', ['text_error' => $errors['email'] ?? '']);?>
            </div>
            </div>
            <div class="registration__input-wrapper form__input-wrapper">
            <label class="registration__label form__label" for="registration-login">Логин <span class="form__input-required">*</span></label>
            <div class="form__input-section">
                <input class="registration__input form__input" id="registration-login" type="text" name="login" placeholder="Укажите логин" value="<?=htmlspecialchars(get_post_val('login'));?>">
                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                <?=include_template('form_error.php', ['text_error' => $errors['login'] ?? '']);?>
            </div>
            </div>
            <div class="registration__input-wrapper form__input-wrapper">
            <label class="registration__label form__label" for="registration-password">Пароль<span class="form__input-required">*</span></label>
            <div class="form__input-section">
                <input class="registration__input form__input" id="registration-password" type="password" name="password" placeholder="Придумайте пароль" value="<?=htmlspecialchars(get_post_val('password'));?>">
                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                <?=include_template('form_error.php', ['text_error' => $errors['password'] ?? '']);?>
            </div>
            </div>
            <div class="registration__input-wrapper form__input-wrapper">
            <label class="registration__label form__label" for="registration-password-repeat">Повтор пароля<span class="form__input-required">*</span></label>
            <div class="form__input-section">
                <input class="registration__input form__input" id="registration-password-repeat" type="password" name="password-repeat" placeholder="Повторите пароль" value="<?=htmlspecialchars(get_post_val('password-repeat'));?>">
                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                <?=include_template('form_error.php', ['text_error' => $errors['password-repeat'] ?? '']);?>
            </div>
            </div>
        </div>
        <?=include_template('form_errors.php', ['errors' => $errors]);?>
        </div>
        <div class="registration__input-file-container form__input-container form__input-container--file">
        <div class="registration__input-file-wrapper form__input-file-wrapper">
            <div class="registration__file-zone form__file-zone dropzone">
            <input class="registration__input-file form__input-file" id="userpic-file" type="file" name="userpic-file" title=" ">
            <div class="form__file-zone-text">
                <span>Перетащите фото сюда</span>
            </div>
            </div>
            <!-- <button class="registration__input-file-button form__input-file-button button" type="button">
            <span>Выбрать фото</span>
            <svg class="registration__attach-icon form__attach-icon" width="10" height="20">
                <use xlink:href="#icon-attach"></use>
            </svg>
            </button> -->
            <label for="userpic-file" class="registration__input-file-button form__input-file-button button" type="button">
            <span>Выбрать фото</span>
            <svg class="registration__attach-icon form__attach-icon" width="10" height="20">
                <use xlink:href="#icon-attach"></use>
            </svg>
            </label>
        </div>
        <div class="registration__file form__file dropzone-previews">

        </div>
        </div>
        <button class="registration__submit button button--main" type="submit">Отправить</button>
    </form>
</section>
