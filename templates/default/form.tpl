    <div class="respond">
      <h3>{translate('Leave a Comment')}</h3>
      <form action="{$action}" method="post" class="commentform">
        <input type="hidden" name="section_id" value="{$section_id}" />
          <input type="hidden" name="parent" value="#" />
        <label for="comment_author" class="required">{translate('Your name')}</label>
        {if $errors.comment_author}<label id="comment_author-error" class="error" for="comment_author">{$errors.comment_author}</label>{/if}
          <input type="text" name="comment_author" id="comment_author" value="" tabindex="1" required="required" class="formfield" />
        <label for="email" class="required">{translate('Your email')}</label>
        {if $errors.email}<label id="email-error" class="error" for="email">{$errors.email}</label>{/if}
          <input type="email" name="email" id="email" value="" tabindex="2" required="required" class="formfield" />
        <label for="homepage">{translate('Your homepage (optional)')}</label>
        {if $errors.homepage}<label id="homepage-error" class="error" for="homepage">{$errors.homepage}</label>{/if}
          <input type="text" name="homepage" id="homepage" value="" tabindex="3" class="formfield" />
        <label for="comment" class="required">{translate('Your message')}</label>
        {if $errors.comment}<label id="comment-error" class="error" for="comment">{$errors.comment}</label>{/if}
          <textarea name="comment" id="comment" rows="5" tabindex="4" required="required" class="formfield"></textarea>
        {if $errors.captcha}<label id="captcha-error" class="error" for="captcha">{$errors.captcha}</label>{/if}
        {$captcha}
        <input name="submit" type="submit" value="{translate('Submit comment')}" />
      </form>
    </div>

