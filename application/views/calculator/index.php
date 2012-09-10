<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>计算服务器流量</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="<?php echo $this->site_url();?>Calculator/result.html">
  <p>
    <label for="textfield"></label>
  流　　量：
  <input type="text" name="dayvalue" id="dayvalue" value="<?php echo $dayvalue;?>" />
（次/天　ＰＶ）</p>
  <p>峰　　值：
    <label for="textfield2"></label>
    <input type="text" name="maxvalue" id="maxvalue" value="<?php echo $maxvalue;?>"/>
  （次/秒　ＰＶ）</p>
  <p>页面大小：
    <label for="textfield3"></label>
    <input type="text" name="pagesize" id="pagesize" value="<?php echo $pagesize;?>"/>
    （ＫＢ）
  </p>
  <p>单台服务器并发数：
    <label for="textfield3"></label>
    <input type="text" name="serverMaxClick" id="serverMaxClick" value="250" />
    （次）
  </p>
  <p>
    　　　　　
      <input type="submit" name="button" id="button" value="开始计算" />
  </p>
</form>
<p><strong>计算平均结果：</strong></p>
<p>ｗｅｂ服务器数量：<?php echo $webServer1?>（台）</p>
<p>ｄａｔａｂａｓｅ服务器数量：<?php echo $databaseServer1?>（台）</p>
<p>带宽要求：<?php echo $net1?>（ＭＢ）</p>
<p><strong>计算峰值结果：</strong></p>
<p>ｗｅｂ服务器数量：<?php echo $webServer2?>（台）</p>
<p>ｄａｔａｂａｓｅ服务器数量：<?php echo $databaseServer2?>（台）</p>
<p>带宽要求：<?php echo $net2?>（ＭＢ），页面加载<?php echo $waitTime;?>秒</p>
<p>以上结果是按照本人经验而来，视具体情况来调整</p>
</body>
</html>
