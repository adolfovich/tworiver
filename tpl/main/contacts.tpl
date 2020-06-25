<div class="row">

  <div class="col-md-12">
    <h2>Контакты</h2>
    <hr>
  </div>
  <?php
    foreach ($contacts as $contact) {
      ?>
      <div class="col-md-6">
      <p><strong><?=$contact['post']?>:</strong> <?=$contact['name']?></p>
      <p><strong>Тел.:</strong> <a href="tel:<?=$contact['phone']?>"><?=$contact['phone']?></a></p>
      <p><strong>Email:</strong> <a href="mailto:<?=$contact['email']?>"><?=$contact['email']?></a></p>
      </div>
      <?php
    }
  ?>

  <div class="col-md-12">
    <hr>
    <h2>Реквизиты</h2>
    <hr>
    <p><strong><?=$r_name?></strong></p>
    <p><strong>Юридический адрес:</strong> <?=$r_addres?></p>
    <p><strong>Почтовый адрес:</strong> <?=$r_addres_post?></p>
    <p><strong>ИНН:</strong> <?=$r_inn?></p>
    <p><strong>КПП:</strong> <?=$r_kpp?></p>
    <p><strong>р/с:</strong> <?=$r_bank_rs?></p>
    <p><strong>Банк:</strong> <?=$r_bank_name?></p>
    <p><strong>БИК:</strong> <?=$r_bank_bik?></p>
    <p><strong>к/с:</strong> <?=$r_bank_ks?></p>
  </div>

  <div class="col-md-12">
    <hr>
    <h2>Форма обратной связи</h2>
    <p>Для того что бы отправить сообщение Председателю правления СНТ заполните форму. Все поля обязательны для заполнения</p>
    <hr>
    <form class="form-horizontal" role="form" method="POST">
      <input type="hidden" name="send_email" value="1">
      <?php
      if (isset($user_info['name'])) {
        $name = $user_info['name'];
      } elseif (isset($form['input_name'])) {
        $name = $form['input_email'];
      } else {
        $name = '';
      }
      ?>
      <div class="form-group">
        <label for="input_name" class="col-sm-2 control-label">ФИО</label>
        <div class="col-sm-10">
          <input name="input_name" type="text" class="form-control" id="input_name" placeholder="ФИО" value="<?=$name?>">
        </div>
      </div>
      <div class="form-group">
        <label for="input_email" class="col-sm-2 control-label">Email</label>
        <?php
        if (isset($user_info['email'])) {
          $email = $user_info['email'];
        } elseif (isset($form['input_email'])) {
          $email = $form['input_email'];
        } else {
          $email = '';
        }
        ?>
        <div class="col-sm-10">
          <input name="input_email" type="text" class="form-control" id="input_email" placeholder="address@yourmail.ru" value="<?=$email?>">
        </div>
      </div>
      <?php
      if (isset($form['input_subject'])) {
        $subject = $form['input_subject'];
      } else {
        $subject = '';
      }
      ?>
      <div class="form-group">
        <label for="input_subject" class="col-sm-2 control-label">Тема сообщения</label>
        <div class="col-sm-10">
          <input name="input_subject" type="text" class="form-control" id="input_subject" placeholder="Тема" value="<?=$subject?>">
        </div>
      </div>
      <?php
      if (isset($form['input_text'])) {
        $text = $form['input_text'];
      } else {
        $text = '';
      }
      ?>
      <div class="form-group">
        <label for="input_text" class="col-sm-2 control-label">Сообщение</label>
        <div class="col-sm-10">
          <textarea name="input_text" class="form-control" rows="5" id="input_text"><?=$text?></textarea>
        </div>
      </div>
      <div class="g-recaptcha" style="margin: 0 auto; width: fit-content; margin-bottom: 10px;" data-sitekey="6LdOkzQUAAAAAFzCX0LrwRiczr49spcUG7nrFWY1" style="margin-left: 160px; margin-bottom: 10px;"></div>

      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-secondary">Отправить</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script src='https://www.google.com/recaptcha/api.js'></script>
