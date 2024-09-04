<script type="text/javascript">
    $(document).ready(function () {
        function initializeLevelUp() {
            if (typeof LevelUp != "undefined") {
                LevelUp.User.initialize({
                    dp: {$dp}
                });
            } else {
                setTimeout(initializeLevelUp, 50);
            }
        }
        initializeLevelUp();
    });
</script>
<div id="info_text"> {$description} </div>
<section id="character_tools">
        <label for="realm">Realm</label>
        <select name="realm" id="realm" onChange="LevelUp.RealmChanged();">
            {if $characters}
                {foreach from=$characters item=realm}
                    <option value="{$realm.realmId}">{$realm.realmName}</option>
                {/foreach}
            {else}
                <option value="0">No realms</option>
            {/if}
        </select>
        <label for="character" class="character_select">Character</label>
        {foreach from=$characters item=realm}
            <select {if !$realm@first}style="display:none"{/if} name="character_selector" data-character id="character_select_{$realm.realmId}" onChange="LevelUp.CharacterChanged(this, {$realm.realmId});">
                {if $realm.characters}
                    <option value="0"> Select Character</option>
                    {foreach from=$realm.characters item=character}
                        {if $character.level < $maxlevel}
                            <option value="{$character.guid}">{$character.name} - Lvl {$character.level}</option>
                        {/if}
                    {/foreach}
                {else}
                    <option value="0">No character</option>
                {/if}
            </select>
        {/foreach}
        <label for="price">Price</label>
        <select name="price" id="price" onChange="LevelUp.CharacterPrice(this);">
            {foreach from=$prices item=price key=key}
                <option value="{$price}">Level {$key} DP {$price} </option>
            {/foreach}
        </select>
        <br/>
        <br/>
        <center>Your prize comes with Levelup
            <div style="padding: 8px 17px 8px 17px; margin-bottom: 20px; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5); background-color: #0b0c0b;  -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px;">
                <div>
                    <div style="text-align: center;color: green;">
                        <div class="table-responsive text-nowrap">
                            <table class="nice_table mb-3" style="text-align: center;">
                                <tr>
                                    <td class="col-0">
                                        {if $config->item('gold_count') != 0 }
                                            <img src="{$url}application/modules/levelup/images/money-gold.gif" align="absmiddle" height="15" width="15" />
                                            {if $config->item('gold_count') > 1000 }
                                                {$config->item('gold_count') / 1000 }K
                                            {else}
                                                {$config->item('gold_count')}
                                            {/if}
                                        {/if}
                                    </td>
                                    <td>
                                        {if $count != 0}
                                            Item({$count})s and sent to you.
                                        {/if}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </center>
        <div class="table-responsive text-nowrap">
            <table class="nice_table mb-3">
                {if $Items}
                    {foreach from=$Items item=item}
                        {if $item.active}
                            <tr>
                                <td class="col-0">
                                    <img class="item_icon" src="{$CI->config->item('api_item_icons')}/medium/{$item.icon}.jpg" align="absmiddle" {if $item.tooltip}data-realm="{$item.realm}" rel="item={$item.entryitem}" {/if}>
                                    <a {if $item.tooltip}href="{$url}item/{$item.realm}/{$item.entryitem}" data-realm="{$item.realm}" rel="item={$item.entryitem}" {/if} class="item_name q{$item.quality}">
                                        {character_limiter({$item.name}, 20)}
                                    </a>
                                    × {$item.count}
                                </td>
                            </tr>
                        {/if}
                    {/foreach}
                {/if}
            </table>
        </div>
    <center>
        <a href="javascript:void(0)"  class="nice_button mt-2" onClick="LevelUp.Submit(this)">
            Levelup
        </a>
    </center>

</section>
<section id="character_tools_message" class="form_message" style="display: none;"></section>