<div class="page_head">
    <div class="page_title">用户资料</div>
</div>
<div class="page_body_b">
    <div id="tabs">
        <ul>
            <li><a href="#t-info">个人资料</a></li>
            <li><a href="#t-headpic">头像</a></li>
            <li><a href="#t-car">爱车</a></li>
            <li><a href="#t-contact">联系方式</a></li>
            <li><a href="#t-locate">位置信息</a></li>
            <li><a href="#t-labels">个人标签</a></li>
            <li><a href="#t-password">修改密码</a></li>
        </ul>
        <div id="t-info">
            <form method="post" action="<?=$this->buildUrl('editinfo')?>">
                <table class="tinfo">
                    <tr>
                        <th>账号：</th>
                        <td><?=$this->base_info['username']?></td>
                    </tr>
                    <tr>
                        <th>昵称：</th>
                        <td><input type="text" value="<?=$this->base_info['nickname']?>" name="p[nickname]" /></td>
                    </tr>
                    <tr>
                        <th>真实姓名：</th>
                        <td><input type="text" value="<?=$this->base_info['realname']?>" name="p[realname]" /></td>
                    </tr>
                    <tr>
                        <th>生日：</th>
                        <td><input type="text" value="<?=join('-',array($this->base_info['birthyear'],$this->base_info['birthmonth'],$this->base_info['birthday']))?>" name="p[realname]" class="Wdate" /></td>
                    </tr>
                    <tr>
                        <th>常用邮箱：</th>
                        <td><input type="text" class="inputTxt" id="email" value="steven.php@gmail.com" name="p[email]"></td>
                    </tr>
                    <tr>
                        <th>职业：</th>
                        <td>
                            <select name="p[occupation]" id="occupation">
                                <option value="">未选择</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>教育程度：</th>
                        <td>          
                            <select name="p[education]" id="education">
                                <option value="">未选择</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><span>*</span>性　别&nbsp;：</th>
                        <td>      
                            <input type="radio" name="p[gender]" value="1">靓MM <input type="radio" name="gender" value="2" checked="">帅GG
                        </td>
                    </tr>
                    <tr>
                        <th>现居住地：<label for="resideprovince2"></label></th>
                        <td><select id="resideprovince" name="p[resideprovince]">
                                <option value="">请选择</option>
                            </select>
                            <label for="residecity"></label>
                            <select id="residecity" name="residecity">
                                <option value="">请选择</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>家乡：</th>
                        <td><select id="birthprovince" name="p[birthprovince]">
                                <option value="">请选择</option>
                            </select>
                            <label for="birthcity"></label>
                            <select id="birthcity" name="p[birthcity]">
                                <option value="">请选择</option>
                            </select></td>
                    </tr>
                    <tr>
                        <th>身份证：</th>
                        <td><input type="text" class="inputTxt" id="idcard" value="<?=$this->base_info['idcard']?>" name="p[idcard]"></td>
                    </tr>
                    <tr>
                        <th>个人介绍：</th>
                        <td><textarea class="inputArea" rows="5" cols="30" name="p[bio]"><?=$this->base_info['bio']?></textarea></td>
                    </tr>
                    <tr>
                    	<td colspan="2"><input type="button" value="保 存" class="submit" /></td>
                    </tr>
                </table>
            </form>
        </div>
        <div id="t-headpic">
            <p>Tab 2 content</p>
        </div>
        <div id="t-car">
            <form id="form100" enctype="multipart/form-data" method="post" action="<?=$this->buildUrl('editcar')?>">
                <table class="tinfo">

                    <tr>
                        <th width="250">爱车名称：</th>
                        <td><input type="text" value="<?=$this->car_info['carname']?>" id="carname" class="inputTxt" name="carname"></td>

                    </tr>
                    <tr>
                        <th><span>*</span>爱车品牌：</th>
                        <td colspan="2"><label for="select"></label>
                            <select id="carfactory" onchange="carfactoryChange(this.value)" name="carfactory">
                                <option value="">请选择</option>
                            </select>
                            <label for="carbrand"></label>
                    </tr>
                    <tr>
                        <th>爱车照片：</th>
                        <td>
                        	<img width="120" height="120" src="<?=$this->car_info['carimage']?>" id="carview">
                            <input type="hidden" name="carimage" />
                            <input type="file" id="carimage" />
                        </td>
                    </tr>
                    <tr>
                        <th>车牌号码：</th>
                        <td><input type="text" value="<?=$this->car_info['carnumber']?>" id="carnumber" class="inputTxt" name="carnumber">&nbsp;<input type="checkbox" value="1" id="cn_open" name="cn_open">
                            <label for="cn_open">是否公开</label></td>
                    </tr>
                    <tr>
                        <th>发动机号码：</th>
                        <td><input type="text" value="<?=$this->car_info['enginenumber']?>" id="enginenumber" class="inputTxt" name="enginenumber">&nbsp;<input type="checkbox" value="1" id="en_open" name="en_open">
                            <label for="en_open">是否公开</label></td>
                    </tr>
                    <tr>
                        <th>排量：</th>
                        <td><input type="text" value="<?=$this->car_info['displacement']?>" id="displacement" class="inputTxt" name="displacement"></td>

                    </tr>
                    <tr>
                        <th>爱车颜色：</th>
                        <td colspan="2"><input type="hidden" id="carcolor" name="carcolor" value=" 紫色" />
                            <ul id="colorList">
                                <li class=""> <a href=""><span class="carColorBlack"></span></a>黑色</li>
                            </ul></td>
                    </tr>
                    <tr>
                        <th>4S店：</th>
                        <td><input type="text" value="<?=$this->car_info['foursshop']?>" id="foursshop" class="inputTxt" name="foursshop"></td>

                    </tr>
                    <tr>
                        <th>交车时间：</th>
                        <td><input type="text" id="datetime" onclick="WdatePicker()" value="<?=$this->car_info['datetime']?>" class="Wdate" name="datetime" /></td>
                    </tr>
                    <tr>
                        <td colspan="2"><input type="button" value="保 存" class="submit" /></td>
                    </tr>
                </table>
            </form>
        </div>
        <div id="t-contact">
            <form method="post" action="<?=$this->buildUrl('editcontact')?>" id="form100">
                <input type="hidden" value="contact" name="action">
                <table class="tinfo">
                    <tr>
                        <th width="241">QQ：</th>
                        <td width="321"><input type="text" class="inputTxt" value="<?=$this->base_info['qq']?>" name="qq"></td>
                    </tr>
                    <tr>
                        <th>MSN：</th>
                        <td><input type="text" class="inputTxt" value="<?=$this->base_info['msn']?>" name="p[msn]"></td>
                    </tr>
                    <tr>
                        <th>手机：</th>
                        <td><input type="text" class="inputTxt" value="<?=$this->base_info['mobile']?>" name="p[mobile]">
                            &nbsp;</td>
                    </tr>
                    <tr>
                        <th>电话：</th>
                        <td><input type="text" class="inputTxt" value="<?=$this->base_info['telephone']?>" name="p[telephone]">&nbsp;</td>
                    </tr>
                    <tr>
                        <th>地址：
                        </th><td><input type="text" class="inputTxt" value="<?=$this->base_info['address']?>" name="p[address]"></td>
                    </tr>
                    <tr>
                        <th>邮编：
                        </th><td><input type="text" class="inputTxt" maxlength="6" value="<?=$this->base_info['zipcode']?>" name="p[zipcode]" /></td>
                    </tr>
                    <tr>
                        <td colspan="2"><input type="button" value="保 存" class="submit" /></td>
                    </tr>
                </table>
            </form>
        </div>
        <div id="t-locate">
            <p>Tab 3 content</p>
        </div>
        <div id="t-labels">
            <p>Tab 3 content</p>
        </div>
        <div id="t-password">
            <form method="post" action="<?=$this->buildUrl('editpasswd')?>">
                <input type="hidden" value="password" name="action">
                <table class="tinfo">
                    <tr>
                        <th width="106">当前密码：</th>
                        <td width="261"><input type="password" id="currentpassword" name="currentpassword"></td>
                    </tr>
                    <tr>
                        <th>新密码：</th>
                        <td><input type="password" id="password" name="password"></td>
                    </tr>
                    <tr>
                        <th>确认密码：</th>
                        <td><input type="password" id="confirmpassword" name="confirmpassword" /></td>
                    </tr>
                    <tr>
                        <td colspan="2"><input type="button" value="更 改" class="submit" /></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<script>
$(function ()
{
	$('#tabs').tabs();
	
	$('.Wdate').datepicker();
	
	$('.submit').click(function ()
	{
		if (!confirm('确定要修改？'))
			return false;
		
		this.form.submit();
	});
})
</script>