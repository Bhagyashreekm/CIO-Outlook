<?php
/**
 * @author Anton
 * 11-Dec-2018
 */
//require("geo.php");
error_reporting(1);
//ob_start("ob_gzhandler");
$doc_root="/var/www/html";
require("$doc_root/smarty/libs/smarty_connect.php");
$smarty= new smarty_connect();
$dbip['ip']='localhost';
$dbuser['user']='root';
$dbpass['pass']='';
$dbname['name']='fbtech';   

$link = mysqli_connect($dbip['ip'],$dbuser['user'],$dbpass['pass'],$dbname['name']); 

if(!$link){
	$link = mysqli_connect('localhost', "root", '','fbtech');
		 if(!$link)
		{
			Header( "HTTP/1.1 301 Moved Permanently" );
			header('location:https://www.fbtechreview.com/offline.html');
			exit(0);
		}  
	}

$FRONT_TPL_PATH = '/var/www/html/templates/fbtech';
$smarty->assign('FRONT_TPL_PATH',$FRONT_TPL_PATH);

$ROOT_PATH = 'https://www.fbtechreview.com/';
$smarty->assign('ROOT_PATH',$ROOT_PATH);

$url_main="https://www.fbtechreview.com"; 
$smarty->assign('url_main',$url_main); 
$website_name="fbtechreview"; 
$smarty->assign('website_name',$website_name); 
    
$FRONT_IMAGE_PATH="/images";
$smarty->assign('FRONT_IMAGE_PATH',$FRONT_IMAGE_PATH);

$domain="https://www.fbtechreview.com";
$smarty->assign('domain',$domain);

$UPLOADED_IMAGE_PATH=$domain."uploaded_images";
$smarty->assign('UPLOADED_IMAGE_PATH',$UPLOADED_IMAGE_PATH);

$smarty->caching = 0;       
$cache_dir="/var/www/html/fbtech/cache_fbtech/";
$smarty->assign('cache_dir',$cache_dir);


$canonical_url="https://".mysqli_real_escape_string($link,$_SERVER['HTTP_HOST']).mysqli_real_escape_string($link,$_SERVER['REQUEST_URI']);
$smarty->assign('canonical_url',$canonical_url);

$get_main=mysqli_real_escape_string($link,$_SERVER['HTTP_HOST']);
$exp_cat=explode('.',$get_main);
$res_exp=$exp_cat[0];


$get_uri=mysqli_real_escape_string($link,$_SERVER['REQUEST_URI']);
$full_domain=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$exp_cat2=explode('/',$get_uri);
$res_exp2=$exp_cat2[1];
//echo "<div style='display:none' id='sai'>".$res_exp."</div>";
$smarty->assign('res_exp',$res_exp);
$smarty->assign('res_exp2',$res_exp2);
$smarty->assign('get_uri',$get_uri);   
$smarty->assign('full_domain',$full_domain);                                                     


function clearCache($filename){
	global $link;
$path = '/var/www/html/fbtech/cache_fbtech';
$files = $path.'/'.$filename.'*.php';  
foreach (glob($files) as $filename) {
unlink($filename);
}
return true;
}

  function genRandomString()
 {
    $length = 5;
    $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
    $string = "";

    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
 }
function get_url_with_filter($str){
    $filter=array('-','/','&','%','_','$','^','(',')','^','*','.',',',':',';','>','<','?','`','~','@','!','#','^','+','=');               
    $filtered=str_replace($filter,'',$str);
    $filter_new = stripslashes(strip_tags(strtolower($filtered)));
    $str = str_replace("   "," ",$filter_new);
    $str = str_replace("  "," ",$str);
    $str = str_replace(" "," ",$str);
	$str = str_replace(" ","-",$str);
	$str = str_replace("---","-",$str);
	$str = str_replace("--","-",$str);
	$str = str_replace(",","-",$str);
	$str = str_replace("'","-",$str);
	$str = str_replace('"','-',$str);
	$str = str_replace('.','-',$str);
        $str = str_replace('~','-',$str);
        $str = str_replace('!','-',$str);
        $str = str_replace('@','-',$str);
        $str = str_replace('%','-',$str);
        $str = str_replace('*','-',$str);
    return $str;  
}
function cat_id_from_newsdata($catnews)
 {
	global $link;
   $sel_cat_news="select category_id from category where shorturl='$catnews'";
   $sql_cat_news=mysqli_query($link,$sel_cat_news);
   if($row_cat_main_news=mysqli_fetch_row($sql_cat_news))
   {
	   $cat_name_main_news=$row_cat_main_news[0];
   }
   return $cat_name_main_news;
 }
function cat_name($catid)
{
	global $link;
   $sel_cat_main_1="select category_name from category where category_id=$catid";
   $sql_cat_main_1=mysqli_query($link,$sel_cat_main_1);
   if($sql_cat_main_1)
   {
	   $row_cat_main=mysqli_fetch_row($sql_cat_main_1);
	   $cat_name_main=$row_cat_main[0];
   }
   return $cat_name_main;
}
 function get_cat_name($cat_id)    
{
	global $link;
    $sql = "SELECT shorturl FROM category where category_id='$cat_id'";   
    $result = mysqli_query($link,$sql) or die(mysqli_error());  
    if($result)
    {
		$row = mysqli_fetch_row($result);
        $shorturl = strip_tags($row[0]);
        $shorturl = str_replace('&','and',$shorturl);
    } 
	return $shorturl;
} 
function get_categoryname($cat_id)    
{
	global $link;
    $sql1 = "SELECT category_name FROM category where category_id='$cat_id'";   
    $result1 = mysqli_query($link,$sql1) or die(mysqli_error());  
    if($result1)
    {
		$row1 = mysqli_fetch_row($result1);
        $category_name = $row1[0];
        
    } 
	return $category_name;
} 
function http_replce_https($html){
	$domains = array("fbtechreview.com","fbtechreview.com");
	$re = '/http(?=:\/\/(?:[^\/]+\.)?(?:' 
	      . implode("|", array_map(function ($x) {
	             return preg_quote($x); 
	          }, $domains)) 
	      . ')\b)/i'; 
	return preg_replace($re, "https", $html);
} 
//seo
$sect=cat_id_from_newsdata($res_exp);

	$sql_seo="SELECT sno,seotitle,metatitle FROM seotitle WHERE section=$sect";
	$res_seo=mysqli_query($link,$sql_seo);	
	if(mysqli_num_rows($res_seo)>0)
	{
		$row_seo=mysqli_fetch_row($res_seo);	
		$seo_title=stripslashes($row_seo[1]);
		$seo_meta_title=$row_seo[2];
	}
	$smarty->assign('seo_title',$seo_title);
	$smarty->assign('seo_meta_title',$seo_meta_title);


/*$smarty->cache_lifetime = -1;
$cachid_header="header_".$res_exp;    
$smarty->cache_dir = $cache_dir; */

/*if(!$smarty->isCached($FRONT_TPL_PATH.'/top_header.tpl',$cachid_header)) 
{*/
/**Select Category For top drop down menu**/

/*  $sql_cattop="select category_id,category_name,shorturl from category where status=1 order by category_name asc";
	$res_cattop=mysqli_query($link,$sql_cattop) or die(mysqli_error());
	$news_count_cattop=mysqli_num_rows($res_cattop);
	
		while($row_cattop=mysqli_fetch_array($res_cattop))	
		{
		 $cat_idtop[] = $row_cattop[0];
		 $cat_nametop[] = $row_cattop[1];
		 $cat_shorturltop[] = $row_cattop[2];
		 $cattop_url[]="https://".trim(get_cat_name($row_cattop[0])).".fbtechreview.com";
		 }
	
	$smarty->assign('cattop_url',$cattop_url);	
	$smarty->assign('cat_idtop',$cat_idtop);	
	$smarty->assign('cat_nametop',$cat_nametop);	
	$smarty->assign('cat_shorturltop',$cat_shorturltop);*/
	
//}

/*$top_header= $smarty->fetch($FRONT_TPL_PATH.'/top_header.tpl',$cachid_header);
$smarty->assign('top_header',$top_header);
$smarty->caching = 0;
*/
	//navigation
	$vertical_id=cat_id_from_newsdata($res_exp);
	$vertical_name=cat_name($vertical_id);
	$vertical_url="https://".$res_exp.".fbtechreview.com";
	$smarty->assign('vertical_name',$vertical_name);
	$smarty->assign('vertical_url',$vertical_url);	
	

//right side  ciooutlook
$curr_cate_mag=cat_id_from_newsdata($res_exp);

	$select_rt_news="select news_id,headline,source,short_desc,image1,news_type,category_type,image5 from news_data where news_type=2 ORDER BY news_id desc LIMIT 10";     
	$res_rt_news=mysqli_query($link,$select_rt_news);
	while($row_rt_news=mysqli_fetch_array($res_rt_news))
	{  
		$news_rt_news[]=$_cmp=$row_rt_news['news_id'];
		$headline_rt_news[]=stripslashes($row_rt_news['headline']);
		$source_rt_news[]="By ".$row_rt_news['source'];
		$short_des_rt_news[]=stripslashes(stripslashes(strip_tags($row_rt_news['short_desc'])));
		if($row_rt_news['category_type']!='')
		{
			$URL_rt_news[]="https://".trim(get_cat_name($row_rt_news['category_type'])).".fbtechreview.com/cioviewpoint/".get_url_with_filter(strtolower($row_rt_news['headline'])).'-nwid-'.$row_rt_news['news_id'].'.html';     
		}
		else
		{
			$URL_rt_news[]=$domain."/ciooutlook/".get_url_with_filter(strtolower($row_rt_news['headline'])).'-nwid-'.$row_rt_news['news_id'].'.html';  
		}
		$image1_rt_news[]=$domain."/newstransfer/upload/".$row_rt_news['image1']; 
		$image5_rt_news[]=$domain."/newstransfer/upload/".$row_rt_news['image5'];
	}
      
	$smarty->assign('news_rt_news',$news_rt_news);
	$smarty->assign('headline_rt_news',$headline_rt_news);
	$smarty->assign('source_rt_news',$source_rt_news);     
	$smarty->assign('short_des_rt_news',$short_des_rt_news);
	$smarty->assign('URL_rt_news',$URL_rt_news); 
	$smarty->assign('image1_rt_news',$image1_rt_news); 
	$smarty->assign('image5_rt_news',$image5_rt_news); 
//right side cxo
	$select_rt_cxo="select news_id,headline,source,short_desc,image1,news_type,category_type,image5 from news_data where news_type=1 ORDER BY news_id desc LIMIT 10";     
	$res_rt_cxo=mysqli_query($link,$select_rt_cxo);
	while($row_rt_cxo=mysqli_fetch_array($res_rt_cxo))
	{  
		$news_rt_cxo[]=$_cmp=$row_rt_cxo['news_id'];
		$headline_rt_cxo[]=stripslashes($row_rt_cxo['headline']);
		$source_rt_cxo[]="By ".$row_rt_cxo['source'];
		$short_des_rt_cxo[]=stripslashes(stripslashes(strip_tags($row_rt_cxo['short_desc'])));
		$URL_rt_cxo[]="https://".trim(get_cat_name($row_rt_cxo['category_type'])).".fbtechreview.com/cxoinsight/".get_url_with_filter(strtolower($row_rt_cxo['headline'])).'-nwid-'.$row_rt_cxo['news_id'].'.html';     
		$image1_rt_cxo[]=$domain."/newstransfer/upload/".$row_rt_cxo['image1']; 
		$image5_rt_cxo[]=$domain."/newstransfer/upload/".$row_rt_cxo['image5']; 
	}
      
	$smarty->assign('news_rt_cxo',$news_rt_cxo);
	$smarty->assign('headline_rt_cxo',$headline_rt_cxo);
	$smarty->assign('source_rt_cxo',$source_rt_cxo);     
	$smarty->assign('short_des_rt_cxo',$short_des_rt_cxo);
	$smarty->assign('URL_rt_cxo',$URL_rt_cxo); 
	$smarty->assign('image1_rt_cxo',$image1_rt_cxo);   
	$smarty->assign('image5_rt_cxo',$image5_rt_cxo);  
//rightside rank

	if($res_exp!='www')
	{
	$sql_currentmag_rank = "SELECT a.image_name,a.url,a.label1,a.label2,a.description,a.date,a.year,b.rnkid,b.rnktitle,b.rankpicname,b.weburl,a.cat_id,b.app_status  FROM magazine_details a left outer join ranking b on b.mag_id=a.sno where a.cat_id=$curr_cate_mag and b.rnkid is not null order by a.sno desc";
		//echo "<div style='display:none' id='sai'>".$sql_currentmag_rank."</div>";
	$res_currentmag_rank = mysqli_query($link,$sql_currentmag_rank);
	$num_count_rank=mysqli_num_rows($res_currentmag_rank);
	while($data_rank = mysqli_fetch_array($res_currentmag_rank))
	{
		
		$image_name_rank[] = $data_rank[0];
		$url_rank[] = $data_rank[1];
		$label1_rank[] = $data_rank[2];
		$label2_rank[] = $data_rank[3];
		$description_rank[] = $data_rank[4];
		$year_rank[] = $data_rank[6];
		$rnkid_rank[] = $data_rank[7];
		$rnktitle_rank[] = $data_rank[8];
		$rankpicname_rank[] = $data_rank[9];
		$rankweburl_rank[] = $data_rank[10];
		$cat_url = get_cat_name($data_rank[11]);
		$rnk_status[] = $data_rank[12];
		$rnkurl_side[]="https://".$cat_url.".fbtechreview.com/vendors/".strtolower($data_rank[10])."-".$data_rank[6].".html";
	}
	$smarty->assign('image_name_rank',$image_name_rank);
	$smarty->assign('url_rank',$url_rank);
	$smarty->assign('label1_rank',$label1_rank);
	$smarty->assign('label2_rank',$label2_rank);
	$smarty->assign('year_rank',$year_rank);
	$smarty->assign('rnkurl_side',$rnkurl_side);
	$smarty->assign('rnkid_rank',$rnkid_rank);
	$smarty->assign('rnktitle_rank',$rnktitle_rank);
	$smarty->assign('rankpicname_rank',$rankpicname_rank); 
	$smarty->assign('num_count_rank',$num_count_rank);  
	$smarty->assign('rnk_status',$rnk_status);  
	}	
	

//magazine slider
if($curr_cate_mag!='')
{
	$sel_c_vert="select sno,year,image_name,url from magazine_details where cat_id=$curr_cate_mag";
	$result_c_vert=mysqli_query($link,$sel_c_vert);
	if(mysqli_num_rows($result_c_vert)>0)
	{
		$sel_curissue_vert="select sno,year,image_name,url,cat_id from magazine_details where cat_id=$curr_cate_mag order by position desc";
		$cur_issueT_vert=cat_name($curr_cate_mag)."   Special";	
	}
	else{
		/*$check_parent_vert="select category_id from category where parent_id=$curr_cate_mag";
		$result_cparent_vert=mysqli_query($link,$check_parent_vert);
		if(mysqli_num_rows($result_cparent_vert)>0)
		{
			$row_cur_cat_vert=mysqli_fetch_row($result_cparent_vert);
			$cat_curr_pa_vert=$row_cur_cat_vert[0];
			$sel_curissue_vert="select sno,year,image_name,url,cat_id from magazine_details where cat_id=$curr_cate_mag order by position desc";
			$cur_issueT_vert="New Editions";
		}
		else
		{*/
			$sel_curissue_vert="select sno,year,image_name,url,cat_id from magazine_details order by position desc limit 2";
			$cur_issueT_vert="On The Deck";
		//}
		
			
	}
}
else{
	$sel_curissue_vert="select sno,year,image_name,url,cat_id from magazine_details order by position desc limit 2";
	$cur_issueT_vert="On The Deck";	
}
//echo "<div style='display:none' id='anton'>".$sel_c_vert." -- ".$cur_issueT_vert."</div>";
$result_curissue_vert=mysqli_query($link,$sel_curissue_vert);
while($row_curissue_vert=mysqli_fetch_array($result_curissue_vert))
{
	$cur_sno_vert[]=$row_curissue_vert[0];
	$cur_issue_vert[]=$domain."/uploaded_images/magazine_img/".$row_curissue_vert[2];
	//$url_cur_issue[] = $row_curissue[3];
	$url_cur_issue_vert[]=str_replace('http://', 'https://', $row_curissue_vert[3]);
} 
//rightsidemagazineinhome&front
$sel_curissue_home="select sno,year,image_name,url,cat_id,label1,label2 from magazine_details where current_issue in (0,1) order by current_issue desc,position desc limit 2";
$result_curissue_home=mysqli_query($link,$sel_curissue_home);
while($row_curissue_home=mysqli_fetch_array($result_curissue_home))
{
	$cur_sno_home[]=$row_curissue_home[0];
	$cur_issue_home[]=$domain."/uploaded_images/magazine_img/".$row_curissue_home[2];
	//$url_cur_issue[] = $row_curissue[3];
	$url_cur_issue_home[]=str_replace('https://', 'https://', $row_curissue_home[3]);
	$catname_spl[]=cat_name($row_curissue_home[4]);
} 

$smarty->assign('cur_sno_home',$cur_sno_home);
$smarty->assign('cur_issue_home',$cur_issue_home);
$smarty->assign('url_cur_issue_home',$url_cur_issue_home);
$smarty->assign('catname_spl',$catname_spl);

/****/
//rightsidevendor(latestcxo/cio)inhome&front
$sel_rightdata="select news_id,headline,news_type,image1,image4,source,source2,category_type from news_data where news_type = 2 order by news_id desc limit 2";
$result_rightdata=mysqli_query($link,$sel_rightdata);
while($row_rightdata=mysqli_fetch_array($result_rightdata))	
		{
			$news_id_rightdata[]=$row_rightdata['news_id'];
			$headline_rightdata[]=stripslashes($row_rightdata['headline']);
			$source_rightdata[]=stripslashes($row_rightdata['source']);
			$image1_rightdata[]=$row_rightdata['image1'];
			$news_type_rightdata[]=$row_rightdata['news_type'];
			$source2_rightdata[]=stripslashes($row_rightdata['source2']); 
			$image4_rightdata[]=$row_rightdata['image4'];
						     		 
			if($row_rightdata['news_type']==2)
			{
				$url_rightdata[]="https://".trim(get_cat_name($row_rightdata['category_type'])).".fbtechreview.com/cioviewpoint/".get_url_with_filter(strtolower($row_rightdata['headline'])).'-nwid-'.$row_rightdata['news_id'].'.html';
			}
		}

$smarty->assign('news_id_rightdata',$news_id_rightdata);
$smarty->assign('headline_rightdata',$headline_rightdata);
$smarty->assign('source_rightdata',$source_rightdata);
$smarty->assign('image1_rightdata',$image1_rightdata);
$smarty->assign('news_type_rightdata',$news_type_rightdata);
$smarty->assign('source2_rightdata',$source2_rightdata);
$smarty->assign('image4_rightdata',$image4_rightdata);
$smarty->assign('url_rightdata',$url_rightdata);

/****/
$smarty->assign('cur_sno_vert',$cur_sno_vert);
$smarty->assign('cur_issueT_vert',$cur_issueT_vert);
$smarty->assign('cur_issue_vert',$cur_issue_vert);
$smarty->assign('url_cur_issue_vert',$url_cur_issue_vert);	

$curr_cate_id=cat_id_from_newsdata($res_exp);
$get_vert_limit_cat=get_vertical_limi_cnt($curr_cate_id);
if($get_vert_limit_cat=='')
{
$get_vert_limit_cat=5;
}
//header vertical
if($res_exp!='www')
{
	
	$sel_cat_top="select category_id,category_name,shorturl from category where status=1 order by shorturl='$res_exp' desc,priority=0,priority limit $get_vert_limit_cat";	
	
}
else
{
	$sel_cat_top="select category_id,category_name,shorturl from category where status=1 order by priority=0,priority limit $get_vert_limit_cat";
}
/*echo "<div style='display:none' id='sai'>".$sel_cat_top."</div>";*/
   $sql_cat_top=mysqli_query($link,$sel_cat_top);
	while($row_top = mysqli_fetch_array($sql_cat_top))
    {
		$cat_id_top[]=$row_top[0];
		$cat_name_top[]=$row_top[1];
		$url_top[]=$row_top[2];
        $shorturl[] = "https://".$row_top[2].".fbtechreview.com/";   
    } 
	
	$chk_exist_vert=implode(",",$cat_id_top);
	
	$smarty->assign('cat_id_top',$cat_id_top);
    $smarty->assign('cat_name_top',$cat_name_top);
    $smarty->assign('shorturl',$shorturl);
	$smarty->assign('url_top',$url_top);
	
	$sub_hed_vert="SELECT category_id,category_name,shorturl FROM category WHERE status=1 And category_id not in ($chk_exist_vert,12,17,21,29) ORDER BY priority ASC";  
	$sub_res_vert=mysqli_query($link,$sub_hed_vert);
		while ($sub_row_vert=mysqli_fetch_array($sub_res_vert))
		{ 
		  $sub_id_vert[]=$sub_row_vert[0];        
		  $sub_name_vert[]=$sub_row_vert[1]; 
		  
			$mnurl_vert[] = "https://".$sub_row_vert[2].".hrtechoutlook.com/";
		  
		}       
		$smarty->assign('sub_id_vert',$sub_id_vert);      
		$smarty->assign('sub_name_vert',$sub_name_vert); 
		$smarty->assign('mnurl_vert',$mnurl_vert);
		
//End header vertical
/*query for without limit Start*/
if($res_exp!='www')
{           
	$sub_foot_vert="SELECT category_id,category_name,shorturl FROM category WHERE status=1  ORDER BY shorturl='$res_exp' desc,priority";  
}
else
{
	$sub_foot_vert="SELECT category_id,category_name,shorturl FROM category WHERE status=1  ORDER BY priority ASC";
}                            
		$sub_foot_vert=mysqli_query($link,$sub_foot_vert);
		while ($res_foot_vert=mysqli_fetch_array($sub_foot_vert))
		{ 
		  $sub_id_foovert[]=$res_foot_vert[0];        
		  $sub_name_foovert[]=$res_foot_vert[1]; 
		  $mnurl_foovert[] = "https://".$res_foot_vert[2].".hrtechoutlook.com/";  
		}        
		$smarty->assign('sub_id_foovert',$sub_id_foovert);      
		$smarty->assign('sub_name_foovert',$sub_name_foovert); 
		$smarty->assign('mnurl_foovert',$mnurl_foovert);               
	/*Footer cod* End*/  

// For TOP Header Menu //
$select_category_head=mysqli_query($link,"SELECT category_id,category_name,shorturl,pcid FROM category where category_id in (22) order by category_id desc")or die(mysqli_error());

//$result_category= mysqli_query($link,$select_category) or die(mysql_error());

while($row_cat_head=mysqli_fetch_array($select_category_head)) 
{
    
     $catid_head[]=$row_cat_head['category_id'];
     $cat_name_head[]=$row_cat_head['category_name'];
     $pcid_head[]=$row_cat_head['pcid'];
     $tab_url_head[]=$row_cat_head['shorturl'];
     $subcat_name[]=subcat($row_cat_head['category_id']); 
     $count[]=subcat1($row_cat_head['category_id']);
     $subtab_url[]= subcat_url($row_cat_head['category_id']);
    // $short_cat_name[]= short_category_name($row_cat_head['category_id']);
    
}


//print_r($subcat_name);
//print_r( $subtab_url);

$smarty->assign('count',$count);
$smarty->assign('subcatid',$subcatid); 
$smarty->assign('subcat_name',$subcat_name);
$smarty->assign('subpcid',$subpcid);
$smarty->assign('catid_head',$catid_head);
$smarty->assign('cat_name_head',$cat_name_head);
$smarty->assign('tab_url_head',$tab_url_head); 
$smarty->assign('subtab_url',$subtab_url);
function get_vertical_limi_cnt($id)
{
	global $link;
	if($id=='')
	{
		$sel_vert_lim="select page_limit from vertical_limit where Page=0";
	}
	else
	{
  		$sel_vert_lim="select page_limit from vertical_limit where Page=$id";
	}
  $res_vert_lim_res=mysqli_query($link,$sel_vert_lim);
  $limit_vert =  mysqli_fetch_row($res_vert_lim_res);    
  $limit_subm=$limit_vert[0];
  return $limit_subm;  
}
function subcat($id)
{
	global $link;
 $select_subcategory="SELECT category_name FROM category WHERE pcid=$id AND pcid not in (0, '', 'NULL') AND status!=0 ORDER BY category_name";
$result_subcategory=mysqli_query($link,$select_subcategory);
while($row_subcat=mysqli_fetch_array($result_subcategory))
{
 $subcat_name[]=str_replace('Europe','',$row_subcat['category_name']);
}
if(isset($select_subcategory)){unset($select_subcategory);}
if(is_resource($result_subcategory)){mysqli_free_result($result_subcategory);}
if(is_resource($row_subcat)){mysqli_free_result($row_subcat);}	
return $subcat_name;
    
}
function subcat1($id)
{
	global $link;
 $select_subcategory1="SELECT category_name FROM category WHERE pcid=$id AND pcid not in (0, '', 'NULL') AND status!=0 ORDER BY  category_name";
$result_subcategory1=mysqli_query($link,$select_subcategory1);
$count=mysqli_num_rows($result_subcategory1);
if(isset($select_subcategory1)){unset($select_subcategory1);}
if(is_resource($result_subcategory1)){mysqli_free_result($result_subcategory1);}
return $count;
}
function subcat_url($id)
{
	global $link;
$select_subcategory2="SELECT shorturl FROM category WHERE pcid=$id AND pcid not in (0, '', 'NULL')  AND status!=0 ORDER BY  category_name";
$result_subcategory2=mysqli_query($link,$select_subcategory2);
while($row_subcat2=mysqli_fetch_array($result_subcategory2))
{
 $subcat_url[]="https://".$row_subcat2['shorturl'].".hrtechoutlook.com/";
}

if(isset($select_subcategory2)){unset($select_subcategory2);}
if(is_resource($result_subcategory2)){mysqli_free_result($result_subcategory2);}	
if(is_resource($row_subcat2)){mysqli_free_result($row_subcat2);}	
return $subcat_url;   
}
function cat_id_from_newsdata($catnews)
 {
	 global $link;
   $sel_cat_news="select category_id from category where shorturl='$catnews'";
   $sql_cat_news=mysqli_query($link,$sel_cat_news);
   if($row_cat_main_news=mysqli_fetch_row($sql_cat_news))
   {
	   $cat_name_main_news=$row_cat_main_news[0];
   }
   return $cat_name_main_news;
 }
?>