<div class="mod_comments">
    {if count($comments)}
    <ul>
    {foreach $comments c}
        <li>
            <span class="icons">
                <a href="mailto:{$c.email}" title="{translate('Send this visitor a mail (using your locally installed eMail program)')}"><span class="icon icon-mail"></span></a>
                <a class="view" href="" title="{translate('View this comment')}"><span class="icon icon-eye"></span></a>
                <a href="{$action}&{if $c.moderation_pending == 'Y'}un{/if}lock" title="{translate('Lock or unlock this comment')}"><span class="icon icon-{if $c.moderation_pending == 'N'}un{/if}locked"></span></a>
                <a class="del" href="{$action}&delXXX={$c.id}" title="{translate('Remove this comment')}"><span class="icon icon-remove"></span></a>
            </span>
            <span class="name">{$c.name}</span>
            <span class="date">{$c.created}</span>
            <span class="content">{$c.content}</span>
        </li>
    {/foreach}
    </ul>
    {else}
    <span class="info">{translate('No comments yet')}</span>
    {/if}
</div>