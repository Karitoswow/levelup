<div class="card" id="main_item">
    <div class="card-header">
        <div class="tab-content border-muted-200 dark:border-muted-700 dark:bg-muted-800 relative w-full border bg-white transition-all duration-300 rounded-xl p-6">
            <div class="tab-pane active" id="items">
                <div class="btn-toolbar justify-content-between">
                    <div class="input-group group/nui-input relative">
                        <div class="text-muted-400 group-focus-within/nui-input:text-primary-500 absolute start-0 top-0 flex items-center justify-center transition-colors duration-300 peer-disabled:cursor-not-allowed peer-disabled:opacity-75 h-10 w-10">
                        </div>
                    </div>
                    {if hasPermission("canAddItems")}
                        <span class="pull-right">
                        <a class="relative font-sans font-normal text-sm inline-flex items-center justify-center leading-5 no-underline h-8 px-3 py-2 space-x-1 border nui-focus transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed hover:enabled:shadow-none text-muted-700 border-muted-300 dark:text-white dark:bg-muted-700 dark:border-muted-600 dark:hover:enabled:bg-muted-600 hover:enabled:bg-muted-50 dark:active:enabled:bg-muted-700/70 active:enabled:bg-muted-100 rounded-md" href="javascript:void(0)" onClick="Items.addGroup()">Add Item</a>
                    </span>
                    {/if}
                </div>
                {if $items}
                    <table class="table table-responsive-md table-hover">
                        <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Realm</th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                        </thead>
                        <tbody id="ItemTableResult">
                        {foreach from=$items item=item}
                            <tr>
                                <td><img style="opacity:1;" src="{$CI->config->item('api_item_icons')}/medium/{$item.icon}.jpg" /></td>

                                <td  href="{$url}item/{$item.realm}/{$item.entryitem}"  data-tip="{$item.entryitem}" rel="item={$item.entryitem}">
                                    <b class="q{$item.quality}">{$item.name}  </b>
                                    ×  {$item.count}
                                </td>
                                <td> {$this->data_model->GetRealmName($item.realm) }	</td>
                                <td style="text-align:center;">
                                    {if $item.active == 1}<span style="color: green">(Active)</span>{else}<span style="color: red">(Disabled)</span>{/if}
                                    <a class="relative font-sans font-normal text-sm inline-flex items-center justify-center leading-5 no-underline h-8 px-3 py-2 space-x-1 border nui-focus transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed hover:enabled:shadow-none text-muted-700 border-muted-300 dark:text-white dark:bg-muted-700 dark:border-muted-600 dark:hover:enabled:bg-muted-600 hover:enabled:bg-muted-50 dark:active:enabled:bg-muted-700/70 active:enabled:bg-muted-100 rounded-md" href="javascript:void(0)" onClick="Items.remove({$item.id}, this)">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                {/if}
            </div>
        </div>
    </div>
</div>
<div class="card" id="add_group" style="display:none;">
    <div class="card-body">
        <h2><a href='javascript:void(0)' onClick="Items.addGroup()" data-tip="Return to List items">Levelup</a> &rarr; Add item </h2>
        <form onSubmit="Items.create(this, true); return false">

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="ItemID">Item ID</label>
                <div class="col-sm-10">
                    <input class="spinner-input form-control nui-focus border-muted-300 text-muted-600 placeholder:text-muted-300 dark:border-muted-700 dark:bg-muted-900/75 dark:text-muted-200 dark:placeholder:text-muted-500 dark:focus:border-muted-700 peer w-full border bg-white font-monospace transition-all duration-300 disabled:cursor-not-allowed disabled:opacity-75 px-2 h-10 py-2 text-sm leading-5 px-3 rounded" type="text" name="ItemEntry" id="ItemEntry" value="">

                </div>
            </div>


    <div class="form-group row" hidden="hidden">
        <label class="col-sm-2 col-form-label" for="Name">Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" hidden name="nameItem" id="nameItem" />
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="ItemCount">Item Count</label>
        <div class="col-sm-10">
            <input class="spinner-input form-control nui-focus border-muted-300 text-muted-600 placeholder:text-muted-300 dark:border-muted-700 dark:bg-muted-900/75 dark:text-muted-200 dark:placeholder:text-muted-500 dark:focus:border-muted-700 peer w-full border bg-white font-monospace transition-all duration-300 disabled:cursor-not-allowed disabled:opacity-75 px-2 h-10 py-2 text-sm leading-5 px-3 rounded" type="text" name="countitem" id="countitem" value="1">

        </div>
    </div>




    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="RealmName">Realm Name</label>
        <div class="col-sm-10">
            <select class="form-control" name="realm" id="realm">
                {foreach from=$realms item=realm}
                    <option value="{$realm->getId()}">{$realm->getName()}</option>
                {/foreach}
            </select>
        </div>
    </div>


    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="RealmActive">Realm Active</label>
        <div class="col-sm-10">
            <select class="form-control" name="active" id="active">
                <option value="0">Disable</option>
                <option value="1" selected>Active</option>
            </select>
        </div>
    </div>




    <button type="submit" class="relative font-sans font-normal text-sm inline-flex items-center justify-center leading-5 no-underline h-8 px-3 py-2 space-x-1 border nui-focus transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed hover:enabled:shadow-none text-muted-700 border-muted-300 dark:text-white dark:bg-muted-700 dark:border-muted-600 dark:hover:enabled:bg-muted-600 hover:enabled:bg-muted-50 dark:active:enabled:bg-muted-700/70 active:enabled:bg-muted-100 rounded-md">Add Item</button>

    </form>
</div>
</div>