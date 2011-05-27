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
                <li><a href="<?=$this->buildUrl('list','index','resource')?>">博文列表</a></li>
                <li><a href="<?=$this->buildUrl('list','media','blog')?>">博文媒体列表</a></li>
            </ul>
        </li>
    </ul>
    
    <ul>
        <li><span>片区管理</span>
            <ul class="sub">
                <li><a href="<?=EXT_URL.'/pages/area/admin.php'?>">创建片区(外链)</a></li>
                <li><a href="<?=EXT_URL.'/pages/area/admin_area_list.php'?>">片区列表(外链)</a></li>
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
    
    <!--<ul>
        <li><span>公告管理</span>
            <ul class="sub">
                <li><a href="#">发布公告</a></li>
                <li><a href="#">所有公告列表</a></li>
            </ul>
        </li>
    </ul>-->
    
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
                
                <li><span>通用设置</span>
                    <ul class="sub">
                        <li><a href="<?=$this->buildUrl('index','keywordsfilter','setting')?>">用户文本关键字（词）过滤设置</a></li>
                        <li><a href="<?=$this->buildUrl('index','image','setting')?>">用户发布图片设置</a></li>
                        <li><a href="<?=$this->buildUrl('index','video','setting')?>">用户发布音乐设置</a></li>
                    </ul>
                </li>
        
            </ul>
        </li>
    </ul>
</ul>
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