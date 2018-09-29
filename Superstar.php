<?php
$typeerror='TypeError';
$faceerror='FaceError';
$typeerrorINFO='是不是选错了？请上传jpg/png等图片格式的照片哈';
$faceerrorINFO='不要调皮，请不要上传看不清或者非人类的照片哦~';
header('Content-Type:text/html;charset=gb2312');
$congradulation=array("(oﾟ▽ﾟ)o  你们简直是一个模子里刻出来的！","(づ｡◕ᴗᴗ◕｡)づ瞧瞧，你们可真是神似~","ಠᴗಠ你简直冰姿玉骨冰肌雪肤才貌双全！","＜(▰˘◡˘▰)您真是才貌双绝沉鱼落雁齿如含贝！","(ノ￣▽￣)ノ简直就是同一个人嘛！","我只能找到他跟你长得像了 ==||","没关系，你比他们好看多了！","(▼ヘ▼#)哪来的勇气长这么丑？？？","(▼へ▼メ)钱都没你长得好看。","(；′⌒`)等等！这一定是BUG！！！","─━ _ ─━✧你难道就是传说中的颜值担当？");
//$infoError='(〃`▽`〃) 你上传文件类型不正确哦~\\n请重新上传png/jpg/jpeg格式图片！';

if($_FILES["file"]["error"])
{
 echo $_FILES["file"]["error"];
}
else
{
  //$im=$_FILES["file"]["tmp_name"]; //上传图片资源
  //$maxwidth="1056"; //设置图片的最大宽度
  //$maxheight="500"; //设置图片的最大高度
  //$imgname = $_FILES["file"]["tmp_name"];  //图片存放路径 根据自己图片路径而定
  //$filetype=$_FILES["file"]["type"];//图片类型
//  $result = thumbImage($im,$maxwidth,$maxheight,$imgname,$filetype);
 if(($_FILES["file"]["type"]=="image/jpeg" || $_FILES["file"]["type"]=="image/png")&& $_FILES["file"]["size"]<99999999999)
 {
  $dirArray[]=NULL;
  $tempDirNum=0;
  $dirArray=getDir("./UserImg/");
if(sizeof($dirArray)>=300){

    deldir("./UserImg/");
    $dirArray[]=NULL;
    $tempDirNum=0;
  }
  for($i=0;$i<sizeof($dirArray);$i++){  //遍历数组找到最大值作为下一个用户创建文件夹命名
      $dirNum=substr($dirArray[$i],strpos($dirArray[$i],'_')+1); //截取文件名User_后面的数字
      if($dirNum>$tempDirNum){
          $tempDirNum=$dirNum;// 找出最大值，为创建下一个用户目录做准备
        }
  }
  mkdirs("./UserImg/User_".($tempDirNum+1));//创建文件夹
  $fname = "./UserImg/User_".($tempDirNum+1)."/"."photo.jpg"; //保存上传的图片
   
  $filename = iconv("UTF-8","gb2312",$fname);
   
  if(file_exists($filename))
  {
   echo "<script>alert('该文件已存在！');</script>";
  }
  else
  {
   move_uploaded_file($_FILES["file"]["tmp_name"],$filename);
  // echo "<script> {window.alert('{$_FILES["file"]["tmp_name"]}');} </script>";  
// echo $_FILES["file"]["tmp_name"];
   chmod($filename,0777);
   //$path="/usr/bin/python3 test.py ";
   $path="/usr/bin/python3 CompareSuperstar.py "; //需要注意的是：末尾要加一个空格
   //echo $filename;
   $set_charset = 'export LANG=en_US.UTF-8;';//exec传输的关键点
   $returns=exec($set_charset.$path.$filename,$return); //string类型
   //echo $returns;
   $results=substr($returns,2,-1);
   if (!strcmp($returns,$typeerror)) {
     echo "<script> {window.alert('{$typeerrorINFO}');location.href='uploadPhoto.html'} </script>";
   }
   if (!strcmp($returns,$faceerror)) {
     
     echo "<script> {window.alert('{$faceerrorINFO}');location.href='uploadPhoto.html'} </script>";
   }
   $arr=explode(" ",$results);
   //print_r($arr);
   for ($i=0; $i < 3; $i++) {
      $img[$i]=".".getNeedBetween($arr[$i],'.','#');
      $imgs[$i]=urldecode(str_replace('\x','%',$img[$i]));
    }

    for ($i=0; $i < 3; $i++) { 
      $names[$i]=getNeedBetween(getNeedBetween($arr[$i],'/','j'),'/','.');
    }
    for ($i=0; $i < 3; $i++) { 
      $like[$i]=getNeedBetween($arr[$i],'#','%');
    }
    for ($i=0; $i < 3; $i++) { 
      $info[$i]=iconv("GBK","UTF-8",$names[$i]).'<br/>'.'<br/>'.iconv("GBK","UTF-8",$like[$i]).'%';
      # code...
    }
   
    //echo "<script> {window.alert('{$return}');} </script>";
   //header("location:AI_FaceRecognition.php");
  }
 }
 else{
  echo "<script> {window.alert('{$typeerrorINFO}');location.href='uploadPhoto.html'} </script>";
 }
}

function getNeedBetween($kw1,$mark1,$mark2){
      $kw=$kw1;
      $kw='123'.$kw.'123';
      $st =stripos($kw,$mark1);
      $ed =stripos($kw,$mark2);
      if(($st==false||$ed==false)||$st>=$ed)
        return 0;
      $kw=substr($kw,($st+1),($ed-$st-1));
      return $kw;
    }
function getDir($dir) {
    $dirArray[]=NULL;
    if (false != ($handle = opendir ( $dir ))) {
        $i=0;
        while ( false !== ($file = readdir ( $handle )) ) {
            //去掉"“.”、“..”以及带“.xxx”后缀的文件
            if ($file != "." && $file != ".."&&!strpos($file,".")) {
                $dirArray[$i]=$file;
                $i++;
            }
        }
        //关闭句柄
        closedir ( $handle );
    }
    return $dirArray;
}


function mkdirs($dir)
{
    if(!is_dir($dir))
        mkdir($dir,0777);
        chmod($dir,0777); //给目录操作权限
    return true; 
}
function deldir($path)
{
    //如果是目录则继续
    if(is_dir($path)){
        //扫描一个文件夹内的所有文件夹和文件并返回数组
        $p = scandir($path);
        foreach($p as $val){
            //排除目录中的.和..
            if($val !="." && $val !=".."){
                //如果是目录则递归子目录，继续操作
                if(is_dir($path.$val)){
                    //子目录中操作删除文件夹和文件
                    deldir($path.$val.'/');
                    //目录清空后删除空文件夹
                    @rmdir($path.$val.'/');
                }else{
                    //如果是文件直接删除
                    unlink($path.$val);
                }
            }
        }
    }
}
function thumbImage($im,$maxwidth,$maxheight,$name,$filetype)
    {
        switch ($filetype) {     
            case 'image/pjpeg':     
            case 'image/jpeg':     
                $im = imagecreatefromjpeg($im);    //PHP图片处理系统函数
                break;     
            case 'image/gif':     
                $im = imagecreatefromgif($im);    
                break;     
            case 'image/png':     
                $im = imagecreatefrompng($im);    
                break;
            case 'image/wbmp':     
                $im = imagecreatefromwbmp($im);    
                break;             
        }  
 
 
        $resizewidth_tag = $resizeheight_tag = false;
        $pic_width = imagesx($im);
        $pic_height = imagesy($im);
 
 
        if(($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight))
        {
$resizewidth_tag = $resizeheight_tag = false;
 
            if($maxwidth && $pic_width>$maxwidth)
            {
                $widthratio = $maxwidth / $pic_width;
                $resizewidth_tag = true;
            }
 
 
            if($maxheight && $pic_height>$maxheight)
            {
                $heightratio = $maxheight / $pic_height;
                $resizeheight_tag = true;
            }
 
 
            if($resizewidth_tag && $resizeheight_tag)
            {
                if($widthratio < $heightratio)
                 $ratio = $widthratio;
                else
                 $ratio = $heightratio;
            }
 
 
            if($resizewidth_tag && !$resizeheight_tag)
            $ratio = $widthratio;
 
 
            if($resizeheight_tag && !$resizewidth_tag)
            $ratio = $heightratio;
 
 
            $newwidth = $pic_width * $ratio;
            $newheight = $pic_height * $ratio;
                        
 
 
            if(function_exists("imagecopyresampled"))
            {
                $newim = imagecreatetruecolor($newwidth,$newheight);//PHP图片处理系统函数
                imagecopyresampled($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);//PHP图片处理系统函数
            }
            else
            {
                $newim = imagecreate($newwidth,$newheight);
                imagecopyresized($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
            }
 
 
            switch ($filetype) {     
                case 'image/pjpeg' :     
                case 'image/jpeg' :     
                    $result = imagejpeg($newim,$name);   
                    break;     
                case 'image/gif' :     
                    $result = imagegif($newim,$name);   
                    break;     
                case 'image/png' :     
                    $result = imagepng($newim,$name);    
                    break;
                case 'image/wbmp' :     
                    $result = imagewbmp($newim,$name);    
                    break;             
            } 
            imagedestroy($newim);
        }
        else
        {
            switch ($filetype) {     
                case 'image/pjpeg' :     
                case 'image/jpeg' :     
                    $result = imagejpeg($im,$name);   
                    break;     
                case 'image/gif' :     
                    $result = imagegif($im,$name);   
                    break;     
                case 'image/png' :     
                    $result = imagepng($im,$name);    
                    break;
                case 'image/wbmp' :     
                    $result = imagewbmp($im,$name);    
                    break;             
            }
        }
        return $result;
    }
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Superstar</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="" />
<meta name="author" content="" />
<!-- styles -->
<link href="/AICrazy/images/icon.png" rel="shortcut icon" type="image/png">
<link rel="stylesheet" href="assets/css/fancybox/jquery.fancybox.css">
<link href="assets/css/bootstrap.css" rel="stylesheet" />
<link href="assets/css/bootstrap-theme.css" rel="stylesheet" />
<link rel="stylesheet" href="assets/css/slippry.css">
<link href="assets/css/style.css" rel="stylesheet" />
<link rel="stylesheet" href="assets/color/default.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<script src="assets/js/modernizr.custom.js"></script>
</head>
<body>
<header>

<div id="navigation" class="navbar navbar-inverse navbar-fixed-top default" role="navigation">
  <div class="container">

    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#top" >You Are Superstar</a>
    </div>

  <div class="navigation">
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1"><nav>
      <ul class="nav navbar-nav navbar-right">
    <li><a href="#about">Result</a></li>

    <li><a href="#works">Superstar</a></li>
      </ul></nav>
    </div><!-- /.navbar-collapse -->
  </div>

  </div>
</div>

</header>

<!-- Section about -->
<section id="about" class="section">
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="heading">
        <h3><span>Look This!</span></h3>
      </div>
      <div class="sub-heading">
        <h2 class="text-center col-xs-12"><strong> &nbsp; &nbsp; <?php echo $congradulation[rand(0,sizeof($congradulation)-1)];?> </strong></h2>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <div class="team-box">
      <img src=<?php echo "./UserImg/User_".($tempDirNum+1)."/"."photo.jpg"?> alt="" class="img-responsive" />
      <div class="roles">
        <h5><strong>你自己</strong></h5>
      </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="team-box">
      <img src=<?php echo $imgs[0]; ?> alt="" class="img-responsive" />
      <div class="roles">
        <h5><strong><?php echo urldecode(str_replace('\x','%',$info[0]));?></strong></h5>
      </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="team-box">
      <img src=<?php echo $imgs[1]; ?>  alt="" class="img-responsive" />
      <div class="roles">
        <h5><strong><?php echo urldecode(str_replace('\x','%',$info[1]))?></strong></h5>
      </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="team-box">
      <img src=<?php echo $imgs[2]; ?>  alt="" class="img-responsive" />
      <div class="roles">
        <h5><strong><?php echo urldecode(str_replace('\x','%',$info[2]))?></strong></h5>
      </div>
      </div>
    </div>
  </div>
</div>
</section>
<!-- end section about -->
<!-- section works -->
<section id="works" class="section gray">
<div class="container">
  <h1 class="text-center"><strong>SuperStar<br>
  </strong></h1>
  <div class="row">
    <div class="col-md-12">
            <ul class="grid effect" id="grid">
            <li>
              <a class="fancybox" data-fancybox-group="gallery" title="1" href="assets/img/portfolio/1.jpg">
                <img src="assets/img/portfolio/1.jpg" alt="" />
              </a>            
            </li>
            <li><a href="assets/img/portfolio/2.jpg" class="fancybox" data-fancybox-group="gallery" title=" 2"><img src="assets/img/portfolio/2.jpg" alt="" /></a></li>
            <li><a href="assets/img/portfolio/3.jpg" class="fancybox" data-fancybox-group="gallery" title="3"><img src="assets/img/portfolio/3.jpg" alt="" /></a></li>
            <li><a href="assets/img/portfolio/4.jpg" class="fancybox" data-fancybox-group="gallery" title="4"><img src="assets/img/portfolio/4.jpg" alt="" /></a></li>
            <li><a href="assets/img/portfolio/5.jpg" class="fancybox" data-fancybox-group="gallery" title="5"><img src="assets/img/portfolio/5.jpg" alt="" /></a></li>
            <li><a href="assets/img/portfolio/6.jpg" class="fancybox" data-fancybox-group="gallery" title="6"><img src="assets/img/portfolio/6.jpg" alt="" /></a></li>
            <li><a href="assets/img/portfolio/7.jpg" class="fancybox" data-fancybox-group="gallery" title="7"><img src="assets/img/portfolio/7.jpg" alt="" /></a></li>
            <li><a href="assets/img/portfolio/8.jpg" class="fancybox" data-fancybox-group="gallery" title="8"><img src="assets/img/portfolio/8.jpg" alt="" /></a></li>
            <li><a href="assets/img/portfolio/9.jpg" class="fancybox" data-fancybox-group="gallery" title="9"><img src="assets/img/portfolio/9.jpg" alt="" /></a></li>
            <li><a href="assets/img/portfolio/10.jpg" class="fancybox" data-fancybox-group="gallery" title="10"><img src="assets/img/portfolio/10.jpg" alt="" /></a></li>  
            <li><a href="assets/img/portfolio/11.jpg" class="fancybox" data-fancybox-group="gallery" title="11"><img src="assets/img/portfolio/11.jpg" alt="" /></a></li>
            <li><a href="assets/img/portfolio/12.jpg" class="fancybox" data-fancybox-group="gallery" title="12"><img src="assets/img/portfolio/12.jpg" alt="" /></a></li>
            <li><a href="assets/img/portfolio/13.jpg" class="fancybox" data-fancybox-group="gallery" title="13"><img src="assets/img/portfolio/13.jpg" alt="" /></a></li>
            <li><a href="assets/img/portfolio/14.jpg" class="fancybox" data-fancybox-group="gallery" title="14"><img src="assets/img/portfolio/14.jpg" alt="" /></a></li>   
            </ul>
    </div>
  </div>
</div>
</section>
<!-- section works -->
<!-- section contact -->
<!-- end section contact -->
<footer>
<div class="verybottom">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="aligncenter">
                <ul class="social-network social-circle">
                    <li><a href="https://github.com/zhongzhiguo" class="github" title="github"><i class="fa fa-github fa-lg"></i></a></li>
                    <li><a href="tencent://AddContact/?fromId=45&amp;fromSubId=1&amp;subcmd=all&amp;uin=867959943&amp;website=www.oicqzone.com" class="qq" title="qq"><i class="fa fa-qq fa-lg"></i></a></li>
                    <li><a href="/AICrazy/images/wechat.png" class="wechat" title="wechat"><i class="fa fa-wechat fa-lg"></i></a></li>
                    <li><a href="https://weibo.com/u/3986714591?refer_flag=1005055014_&amp;is_hot=1" class="weibo" title="weibo"><i class="fa fa-weibo fa-lg"></i></a></li>
                    <li><a href="/AICrazy/resume/myresume.html" class="info" title="info"><i class="fa fa-info fa-lg"></i></a></li>
                </ul> 
        </div>          
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="aligncenter">
          <p>Copyright &copy; 2018.Company name All rights reserved.<a target="_blank" href="http://AICrazy.top/"> <br>AICrazy</a></p>
        </div>
      </div>
    </div>
  </div>
</div>
</footer>
<a href="#" class="scrollup"><i class="fa fa-angle-up fa-2x"></i></a>
<!-- javascript -->
<script src="assets/js/jquery-1.9.1.min.js"></script>
<script src="assets/js/jquery.easing.js"></script>
<script src="assets/js/classie.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/slippry.min.js"></script>
<script src="assets/js/nagging-menu.js"></script>
<script src="assets/js/jquery.nav.js"></script>
<script src="assets/js/jquery.scrollTo.js"></script>
<script src="assets/js/jquery.fancybox.pack.js"></script> 
<script src="assets/js/jquery.fancybox-media.js"></script> 
<!---<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASm3CwaK9qtcZEWYa-iQwHaGi3gcosAJc&sensor=false"></script>--->
<script src="assets/js/masonry.pkgd.min.js"></script>
<script src="assets/js/imagesloaded.js"></script>
<script src="assets/js/jquery.nicescroll.min.js"></script>
<script src="assets/js/validate.js"></script> 
<script src="assets/js/AnimOnScroll.js"></script>
    <script>
        new AnimOnScroll( document.getElementById( 'grid' ), {
            minDuration : 0.4,
            maxDuration : 0.7,
            viewportFactor : 0.2
        } );
    </script>
<script>
  $(document).ready(function(){
    $('#slippry-slider').slippry(
    defaults = {
      transition: 'vertical',
      useCSS: true,
      speed: 5000,
      pause: 3000,
      initSingle: false,
      auto: true,
      preload: 'visible',
      pager: false,   
    } 
    
    )
  });
</script>
<script src="assets/js/custom.js"></script>
</body>
</html>
