<ul id="navigation" class="treeview">
    <ul>
        <li><span>用户管理</span>
            <ul class="sub">
                <li><a href="<?=$this->buildUrl('list','index','user')?>">用户列表</a></li>
                <li><a href="<?=$this->buildUrl('users','index','blog')?>">用户相关博客</a></li>
            </ul>
        </li>
    </ul>
    <ul>
        <li><span>微博管理</span>
            <ul class="sub">
                <li><a href="<?=$this->buildUrl('list','resource','resource')?>">ResourceList</a></li>
                <li><a href="<?=$this->buildUrl('addpage','resource','resource')?>">Add Resource</a></li>
                <li><a href="<?=$this->buildUrl('addpage','volume','resource')?>">Add Volume</a></li>
                <li><a href="<?=$this->buildUrl('addpage','uri','resource')?>">Add URI</a></li>
                <li><a href="<?=$this->buildUrl('list','media','blog')?>">博文媒体列表</a></li>
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
        <li><span>路况管理</span>
            <ul class="sub">
                <li><a href="<?=EXT_URL.'/pages/traffic/digest.php'?>">管理路况(外链)</a></li>
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
        <li><span>系统设置</span>
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
                
                <li><span>权限设置</span>
                    <ul class="sub">
                        <li><a href="<?=$this->buildUrl('index','group','acl')?>">组织架构权限树</a></li>
                        <li><a href="<?=$this->buildUrl('index','permit','acl')?>">权限列表</a></li>
						<li><a href="<?=$this->buildUrl('index','role','acl')?>">角色</a></li>
                        <li><a href="<?=$this->buildUrl('index','user','acl')?>">用户</a></li>
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