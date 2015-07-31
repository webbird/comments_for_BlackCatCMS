<div class="mod_comments">
    {if count($comments)}
    <ul class="navigation">
        <li>{count($comments)} {translate('Comments')}</li>
        <li><a href="#" class="scroll" data-parent="0">{translate('Join the discussion')}</a></li>
    </ul>
    {foreach $comments c}
    <div class="comment" style="margin-left:{$c.level * 70}px">
        <div class="avatar">
            <img src="http://www.gravatar.com/avatar/{$c.email_md5}?size=50&d=identicon" class="img-circle" />
        </div>
        <div class="bubble">
            <div class="name">{$c.name}</div>
            <div title="{translate('Added at')} {$c.created}" class="date">{$c.created}</div>
            <a class="reply" data-parent="{$c.id}" href="#" title="{translate('Reply')}"><img src="{$CAT_URL}/modules/comments/arrow.png" alt="arrow" /></a><br />
            <div>{$c.content}</div>
        </div>
    </div>
    {/foreach}
    {/if}
    {include form.tpl}
</div>