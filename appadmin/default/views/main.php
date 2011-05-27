<div id="container">
    <div id="top_navigation">
        <div class="logo">LBS管理后台 test</div>
        <div id="nav">
            <?=$this->token->uname?>
            <a href="<?=$this->buildUrl('logout','auth')?>">[登出]</a>
        </div>
        <div class="clear"></div>
    </div>
    <div id="outerContainer">
        <div id="leftPanel">
            <div id="interfaceControlFrame">
                <div id="interfaceControlFrameMinimizeContainer"></div>
            </div>
            <?=$this->render('left-menu.php')?>
        </div>
        <div id="rightPanel">
            <iframe id="main_frame" frameborder="0" width="100%" height="100%" src="<?=$this->buildUrl('welcome')?>" scrolling="auto"></iframe>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function()
{
    $(top).bind('set_frame', function (evn, url)
    {
		document.getElementById('main_frame').src = url;
    });

    $('#outerContainer').splitter({
        sizeLeft: 260
    });
    
    function resizeContent()
    {
        $('#outerContainer').trigger('resize');
    }
    
    $(window).resize(function () {
        resizeContent();
    });
});
</script>