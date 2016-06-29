<?php

// 严格开发模式
ini_set('display_errors', 'On');
ini_set('memory_limit', '64M');
error_reporting(E_ALL);

$t1 = $ntime = microtime(true);
$endtime = '未执行任何操作，不统计！';
function print_memory($rc, &$infostr)
{
	global $ntime;
	$cutime = microtime(true);
	$etime = sprintf('%0.4f', $cutime - $ntime);
	$m = sprintf('%0.2f', memory_get_usage()/1024/1024);
	$infostr .= "{$rc}: &nbsp;{$m} MB 用时：{$etime} 秒<br />\n";
	$ntime = $cutime;
}

header('Content-Type: text/html; charset=utf-8');

$memory_info = '';
print_memory('没任何操作', $memory_info);

require_once 'phpanalysis.class.php';

$str = (isset($_POST['source']) ? $_POST['source'] : '');

$loadtime = $endtime1  = $endtime2 = $slen = 0;

$do_fork = $do_unit = true;
$do_multi = $do_prop = $pri_dict = false;

if($str != '')
{
	//岐义处理
	$do_fork = empty($_POST['do_fork']) ? false : true;
	//新词识别
	$do_unit = empty($_POST['do_unit']) ? false : true;
	//多元切分
	$do_multi = empty($_POST['do_multi']) ? false : true;
	//词性标注
	$do_prop = empty($_POST['do_prop']) ? false : true;
	//是否预载全部词条
	$pri_dict = empty($_POST['pri_dict']) ? false : true;
	
	$tall = microtime(true);
	
	//初始化类
	PhpAnalysis::$loadInit = false;
	$pa = new PhpAnalysis('utf-8', 'utf-8', $pri_dict);
	print_memory('初始化对象', $memory_info);
	
	//载入词典
	$pa->LoadDict();
	print_memory('载入基本词典', $memory_info);    
		
	//执行分词
	$pa->SetSource($str);
	$pa->differMax = $do_multi;
	$pa->unitWord = $do_unit;
	
	$pa->StartAnalysis( $do_fork );
	print_memory('执行分词', $memory_info);
	
	$okresult = $pa->GetFinallyResult(' ', $do_prop);
	print_memory('输出分词结果', $memory_info);
	
	$pa_foundWordStr = $pa->foundWordStr;
	
	$t2 = microtime(true);
	$endtime = sprintf('%0.4f', $t2 - $t1);
	
	$slen = strlen($str);
	$slen = sprintf('%0.2f', $slen/1024);
	
	$pa = '';
	
}

$teststr = "2010年1月，美国国际消费电子展 (CES)上，联想将展出一款基于ARM架构的新产品，这有可能是传统四大PC厂商首次推出的基于ARM架构的消费电子产品，也意味着在移动互联网和产业融合趋势下，传统的PC芯片霸主英特尔正在遭遇挑战。
11月12日，联想集团副总裁兼中国区总裁夏立向本报证实，联想基于ARM架构的新产品正在筹备中。
英特尔新闻发言人孟轶嘉表示，对第三方合作伙伴信息不便评论。
正面交锋
ARM内部人士透露，11月5日，ARM高级副总裁lanDrew参观了联想研究院，拜访了联想负责消费产品的负责人，进一步商讨基于ARM架构的新产品。ARM是英国芯片设计厂商，全球几乎95%的手机都采用ARM设计的芯片。
据悉，这是一款采用高通芯片(基于ARM架构)的新产品，高通产品市场总监钱志军表示，联想对此次项目很谨慎，对于产品细节不方便透露。
夏立告诉记者，联想研究院正在考虑多种方案，此款基于ARM架构的新产品应用邻域多样化，并不是替代传统的PC，而是更丰富的满足用户的需求。目前，客户调研还没有完成，“设计、研发更前瞻一些，最终还要看市场、用户接受程度。”";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>分词测试</title>
</head>
<body>
<table width='90%' align='center'>
<tr>
	<td>    

<hr size='1' />

<form id="form1" name="form1" method="post" action="?ac=done" style="margin:0px;padding:0px;line-height:24px;">
  <b>源文本：</b>&nbsp; <a href="PHPAnalysis.dict_build.php" target="_blank">[更新词典]</a> <br/>
	<textarea name="source" style="width:98%;height:150px;font-size:14px;"><?php echo (isset($_POST['source']) ? $_POST['source'] : $teststr); ?></textarea>
	<br/>
	<input type='checkbox' name='do_fork' value='1' <?php echo ($do_fork ? "checked='1'" : ''); ?>/>岐义处理
	<input type='checkbox' name='do_unit' value='1' <?php echo ($do_unit ? "checked='1'" : ''); ?>/>新词识别
	<input type='checkbox' name='do_multi' value='1' <?php echo ($do_multi ? "checked='1'" : ''); ?>/>多元切分
	<input type='checkbox' name='do_prop' value='1' <?php echo ($do_prop ? "checked='1'" : ''); ?>/>词性标注
	<input type='checkbox' name='pri_dict' value='1' <?php echo ($pri_dict ? "checked='1'" : ''); ?>/>预载全部词条
	<br/>
	<input type="submit" name="Submit" value="提交进行分词" />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="reset" name="Submit2" value="重设表单数据" />
</form>
<br />
<textarea name="result" id="result" style="width:98%;height:120px;font-size:14px;color:#555"><?php echo (isset($okresult) ? $okresult : ''); ?></textarea>
<br /><br />
<b>调试信息：</b>
<hr />
<font color='blue'>字串长度：</font><?php echo $slen; ?>K <font color='blue'>自动识别词：</font><?php echo (isset($pa_foundWordStr)) ? $pa_foundWordStr : ''; ?><br />
<hr />
<font color='blue'>内存占用及执行时间：</font>(表示完成某个动作后正在占用的内存)<hr />
<?php echo $memory_info; ?>
总用时：<?php echo $endtime; ?> 秒
</td>
</tr>
</table>
</body>
</html>

