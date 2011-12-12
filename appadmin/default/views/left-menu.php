<ul id="navigation" class="treeview">
    <ul>
        <li><span>Resource</span>
            <ul class="sub">
                <li><a href="<?=$this->buildUrl('list','resource','resource')?>">ResourceList</a></li>
                <li><a href="<?=$this->buildUrl('addpage','resource','resource')?>">Add Resource</a></li>
                <li><a href="<?=$this->buildUrl('addpage','volume','resource')?>">Add Volume</a></li>
                <li><a href="<?=$this->buildUrl('addpage','uri','resource')?>">Add URI</a></li>
            </ul>
        </li>
    </ul>
    
    <ul>
        <li><span>Tags</span>
            <ul class="sub">
                <li><a href="<?=$this->buildUrl('addpage','index','tag')?>">Add Tag</a></li>
                <li><a href="<?=$this->buildUrl('list','index','tag')?>">Tags</a></li>
            </ul>
        </li>
    </ul>
    
    <ul>
        <li><span>日志管理</span>
            <ul class="sub">
                <li><a href="#">前端日志</a></li>
                <li><a href="<?=$this->buildUrl('index','adminlog','default')?>">管理日志</a></li>
            </ul>
        </li>
    </ul>
    
    <ul>
        <li><span>System Settings</span>
            <ul class="sub">
                <!--<li><span>基础资料设置</span>
                    <ul class="sub">
                        <li><a href="#">可选地区设置</a></li>
                        <li><a href="#">可选汽车设置</a></li>
                        <li><a href="#">常用标签</a></li>
                        <li><a href="#">常用分组类型</a></li>
                        <li><a href="#">常用活动类型</a></li>
                    </ul>
                </li>-->
                
                <li><span>ACL</span>
                    <ul class="sub">
                        <li><a href="<?=$this->buildUrl('index','group','acl')?>">组织架构权限树</a></li>
                        <li><a href="<?=$this->buildUrl('list','permitbasic','acl')?>">权限列表</a></li>
						<li><a href="<?=$this->buildUrl('index','role','acl')?>">角色</a></li>
                        <li><a href="<?=$this->buildUrl('list','user','acl')?>">用户</a></li>
                        <li><a href="<?=$this->buildUrl('list','token','acl')?>">当前令牌</a></li>
                    </ul>
                </li>
        
            </ul>
        </li>
    </ul>
</ul>
<?=JsUtils::ob_start();?>
<script type="text/javascript">
$(function ()
{
    $("#navigation").treeview({
        collapsed: false,
        click: function ()
        {
            $(top).trigger('set_frame', this);
            return false;
        }
    });
});
</script>
<?=JsUtils::ob_end();?>