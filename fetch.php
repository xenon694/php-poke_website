<?php
$time_start = microtime(true);
/*========指定項目========
$tablename='';
$items=[
  ''=>[
  m'ja'=>'',
  'cl'=>[''=>''],'ex'=>'','rf'=>text/number,'in'=>input/select],
];
========================*/
$refine=["text","number","range","select"];
$select['text']=["2"=>"と一致する","1"=>"を含む","0"=>"から始まる","3"=>"で終わる","4"=>"正規表現"];
$select['number']=["="=>"と等しい","<>"=>"と等しくない",">="=>"以上","<="=>"以下",">"=>"より大きい","<"=>"より小さい"];
$select['range']=["BETWEEN"=>"の範囲内","NOT BETWEEN"=>"の範囲外"];
$input;
$index=["ASC"=>"昇順","DESC"=>"降順"];
foreach($items as $name=>$arr){
  $index[$name]=$arr['ja'];
  foreach($arr['cl'] as $colname=>$column){
    $index[$column]=$colname;
  }
}

/*======SQL接続======*/
$q=file(dirname(__FILE__).'../../../sql.dat',FILE_IGNORE_NEW_LINES);
$sql=new mysqli($q[0],$q[1],$q[2],$q[3]);
if(!$sql){echo("SQL接続エラー\n");}
$sql->set_charset('utf8');

/*=================================
    検索データを変数に代入
=================================*/
function takeInput($name,$rf,$cnt=1){
  global $tablename,$sql,$input;
  for($i=0;$i<$cnt;$i++){
    if($cnt==1){
      $target=$name;
    }else{
      $target=$name.$i;
    }
    if(!is_null($_GET[$target.'rg'])){//range
      if(($_GET[$target.'tx']!='')&&($_GET[$target.'tx2']!='')){//2値ある
        if($_GET[$target.'tx']>$_GET[$target.'tx2']){//大小が逆の時入れ替え
          $input[$name]['range'][]=['tx1'=>$_GET[$target.'tx2'],'tx2'=>$_GET[$target.'tx'],'pd'=>$_GET[$target.'pd']];
        }else{
          $input[$name]['range'][]=['tx1'=>$_GET[$target.'tx'],'tx2'=>$_GET[$target.'tx2'],'pd'=>$_GET[$target.'pd']];
        }
      }
      //片方しかないとき
      if(empty($_GET[$target.'tx'])&&$_GET[$target.'tx2']!=''){
        $input[$name]['range'][]=['tx2'=>$_GET[$target.'tx2'],'pd'=>$_GET[$target.'pd']];
      }elseif($_GET[$target.'tx']!=''&&empty($_GET[$target.'tx2'])){
       $input[$name]['range'][]=['tx1'=>$_GET[$target.'tx'],'pd'=>$_GET[$target.'pd']];
      }
    }elseif($_GET[$target.'tx']!=''){//text/number
      $input[$name][$rf][]=['tx'=>$_GET[$target.'tx'],'pd'=>$_GET[$target.'pd']];
    }elseif($_GET[$target.'sc']!=''){//select
      $input[$name]['select'][]=$_GET[$target.'sc'];
    }
  }
}//end function
$time_1 = microtime(true);

foreach($items as $name=>$arr){
  if(!array_key_exists('cl',$arr)){//行数指定がない場合
    $items[$name]['cl']=[$arr['ja']=>$name];
  }
  $input[$name]['disp']=(is_null($_GET[$name])?FALSE:TRUE);//表示
  $input[$name]['or']=(is_null($_GET[$name.'or'])?FALSE:TRUE);
  takeInput($name,$arr['rf'],count($items[$name]['cl']));
  foreach($arr['cl'] as $column){//各行
    $input[$column]['disp']=(is_null($_GET[$column])?FALSE:TRUE);//表示
    takeInput($column,$arr['rf']);
  }//exit for
}//$items as $name=>$arr
if(!is_null($_GET['sort'])){//並べ替え
  $input['sort']['column']=$_GET['sort'];
  $input['sort']['order']=$_GET['order'];
}
$enter=(is_null($_GET['enter'])?FALSE:TRUE);//検索画面
  
/*=====すべて非表示なら全表示======*/
$flg=TRUE;
foreach($input as $arr){
  if($arr['disp']){$flg=FALSE;break;}
}
if($flg){
  foreach($items as $name=>$arr){$input[$name]['disp']=TRUE;}
}
/*=================================
    テーブル表示画面の処理
=================================*/
/*======クエリ文作成======*/
function writeQuery(&$qer,&$qtx,$i,$type,$column,$value){
  global $select;
  switch($type){
    case 'text':
      $qer.=" `{$column}`";
      if($value['pd']<4){$qer.=" LIKE '";}
      else{$qer.=" REGEXP '";}
      if($value['pd']%2==1){$qer.="%";}
      $qer.=$value['tx'];
      if($value['pd']<2){$qer.="%";}
      $qer.="'";
      if($i==0){$qtx.="\"{$value['tx']}\"{$select['text'][$value['pd']]}";}
      break;
    case 'number':
      $qer.=" `{$column}`{$value['pd']}{$value['tx']}";
      if($i==0){$qtx.="{$value['tx']}{$select['number'][$value['pd']]}";}
      break;
    case 'range':
      if(empty($value['tx1'])){
        if($value['pd']=='BETWEEN'){
          $qer.=" `{$column}` <= {$value['tx2']}";
        }elseif($value['pd']=='NOT BETWEEN'){
          $qer.=" `{$column}` > {$value['tx2']}";
        }
      }elseif(empty($value['tx2'])){
        if($value['pd']=='BETWEEN'){
          $qer.=" `{$column}` >= {$value['tx1']}";
        }elseif($value['pd']=='NOT BETWEEN'){
          $qer.=" `{$column}` < {$value['tx1']}";
        }
      }else{
        $qer.=" `{$column}` {$value['pd']} '{$value['tx1']}' AND '{$value['tx2']}'";
      }
      if($value['pd']=='BETWEEN'){
        if($i==0){$qtx.="{$value['tx1']}～{$value['tx2']}";}
      }elseif($value['pd']=='NOT BETWEEN'){
        if($i==0){$qtx.="～{$value['tx1']},{$value['tx2']}～";}
      }
      break;
    case 'select':
      //foreach($value as $key=>$sc){
        //if($key>0){$qer=$qer.' OR';$qtx=$qtx.',';}
        $qer.=" `{$column}`='{$value}'";
        if($i>0){$qtx.=" ";}
        if($i==0){$qtx.=$value;}
      //}
      break;
  }
}

if(!$enter){
  $time_2 = microtime(true);
  $qtx="";//表示用検索条件
  $qer="FROM `{$tablename}`";//検索用クエリ文
  $first=true;
  foreach($input as $name=>$arr){
    $or=false;
    foreach($arr as $type=>$refs){
      if($type=='or'){$or=$refs;}
      if(!in_array($type,$refine)){continue;}//検索条件以外はスルー
      if($qtx){
        $qtx.='　';
      }
      $qtx.="{$index[$name]}：";
      foreach($refs as $value){
        if($first){
          $qer.=' WHERE';
          $first=false;
        }elseif($or){
          $qer.=' OR';
        }else{
          $qer.=' AND';
        }
        $i=0;
        if(array_key_exists('cl',$items[$name])){
          foreach($items[$name]['cl'] as $column){
            if($i>0){
              $qer.=' OR';
            }
            if($i==0){$qer.=' (';}
            writeQuery($qer,$qtx,$i,$type,$column,$value);
            if($i==count($items[$name]['cl'])-1){$qer.=' )';}
            $i++;
          }
        }else{
          writeQuery($qer,$qtx,$i,$type,$name,$value);
        }
      }
    }
  }
  if(array_key_exists('sort',$input)){
    $qer.=" ORDER BY `{$input['sort']['column']}` {$input['sort']['order']}";
    $qtx.="　並べ替え：{$index[$input['sort']['column']]}{$index[$input['sort']['order']]}";
  }
  
  echo('<!--');
  var_dump($index);
  var_dump($input);
  var_dump($qer);
  echo('-->');
  
/*=============================
    データ表示HTML書き出し
=============================*/
  //----テーブル----
$time_3 = microtime(true);

  echo('
  <section>
  <div>');
  if($qtx){echo("<p>{$qtx}</p>");}
  echo("<a class=\"button\" href=\"".basename($_SERVER['PHP_SELF'])."?{$_SERVER['QUERY_STRING']}&enter\">＞＞検索条件を入力</a>
  </section>
  <div id=\"list\">
  <table>
    <thead class=\"scrollHead\">
      <tr>");
  foreach($items as $name=>$arr){
    if($input[$name]['disp']){
      foreach($arr['cl'] as $key=>$column){
        echo("<th class=\"{$column}\">{$key}</th>");
      }
    }else{
      foreach($arr['cl'] as $key=>$column){
        if($input[$column]['disp']){
          echo("<th class=\"{$column}\">{$key}</th>");
        }
      }
    }
  }
  echo('
      </tr><!--#head-->
    </thead>
    <tbody class="scrollBody">');
  //----データ----
  $res=$sql->query("SELECT * {$qer}");
  if(!$res){
    echo('SQLエラー');
  }else{
    $cnt=0;
    while($row=$res->fetch_array(MYSQLI_BOTH)){
      echo('        <tr>');
      foreach($items as $name=>$arr){
        if($input[$name]['disp']){
          foreach($arr['cl'] as $column){
            if(array_key_exists('fm',$arr)){
              echo("<td class=\"{$column}\">".number_format($row[$column],$arr['fm'],'.','')."</td>");
            }else{
              echo("<td class=\"{$column}\">{$row[$column]}</td>");
            }
          }
        }else{
          foreach($arr['cl'] as $key=>$column){
            if($input[$column]['disp']){
              if(array_key_exists('fm',$arr)){
                echo("<td class=\"{$column}\">".number_format($row[$column],$arr['fm'],'.','')."</td>");
              }else{
                echo("<td class=\"{$column}\">{$row[$column]}</td>");
              }
            }
          }
        }
      }
      echo("</tr>\n");
      $cnt++;
    }
  }
  
  echo('      </tbody>
    </table>
    </div><!--#list-->
    <section style="text-align:right">
      <p>');
  echo('
    <button onClick="openNew()">
    <img src="./img/Twitter.png" height="12px"><b>ツイート</b>
    </button>');
  $twtx="";
  if($qtx){
    $twtx=$qtx;
    $i=strlen($twtx);
    while(mb_strlen($twtx,'UTF-8')>85&&$i>0){
      $twtx=mb_strcut($qtx,0,$i,"UTF-8").'…';
      $i--;
    }
    $twtx.=" の検索結果 / ";
  }
  echo("
<script type=\"text/javascript\">
  function openNew(){
    window.open('https://twitter.com/intent/tweet?hashtags=PnLab&related=Pn_Lab&text={$twtx}'+document.title+'&url='+encodeURIComponent(location.href),'','width=500,height=300');
  }
</script>
");
  echo("
      マッチ件数：{$cnt}件
      </p>
    </section>
  </div>
    ");

  $res->free;
$time_4 = microtime(true);

//-----幅調整CSS-----
  echo('
    <style type="text/css">');
  $whole=0;
  $width=0;
  $pad=5;//左右のパディング幅
  $size=6;//フォントサイズ(半分)
  foreach($items as $name=>$arr){
    foreach($arr['cl'] as $colname=>$column){
      $res=$sql->query("SELECT MAX(LENGTH(`{$column}`))  {$qer}");
      $row=$res->fetch_array(MYSQLI_BOTH);
      $len=$row[0];
      $res=$sql->query("SELECT MAX(CHAR_LENGTH(`{$column}`))  {$qer}");
      $row=$res->fetch_array(MYSQLI_BOTH);
      $mblen=$row[0];
      //$headlen=mb_strlen($colname)+(strlen($colname)-mb_strlen($colname))/2;
      $headlen=2;
      $datalen=$mblen+($len-$mblen)/2;
      $width=max($headlen,$datalen);
      $width=($width*$size)+($pad*2)+2+1;//border幅+1
      
      if($input[$name]['disp']||$input[$column]['disp']){
        echo("
      .{$column}{
        width:{$width}px;");
        if(array_key_exists('al',$arr)){
          echo("
        text-align:{$arr['al']};");
        }
        echo('      }');
        $whole+=$width;
      }
    }
  }
  echo("
    #list table{
      width:".($whole+18)."px;
      font-size:".($size*2)."px;
    }
    #list table th,td{
      padding:1px {$pad}px;
    }
    </style>");
}//!$enter

$res->free;

/*================================
    検索フォーム
================================*/
if($enter){
$time_5 = microtime(true);
  /*======データリスト作成======*/
  foreach($items as $name=>$arr){
    foreach($arr['cl'] as $column){
      $res=$sql->query("SELECT DISTINCT `{$column}` FROM {$tablename}");
      while($row=$res->fetch_array(MYSQLI_BOTH)){
        if($row[$column]!=''){
          $itemlist[$name][]=$row[$column];
          $itemlist[$column][]=$row[$column];
        }
      }
    }
    $itemlist[$name]=array_unique($itemlist[$name]);
  }
  $res->free;

  /*=========検索フォーム=========*/
  function makeInput($name,$arr,$cnt=1){
    global $itemlist;
    global $select;
    global $refine;
    $rf=$arr['rf'];
    $in=$arr['in'];
    $cnt=min($cnt,3);

    for($i=0;$i<$cnt;$i++){ //clの数分
      if($cnt>1){
        $column=$name.$i;
      }else{
        $column=$name;
      }
      echo('<div class="inputRow">');
      if($in=='input'){ //text/number
        echo("<input type=\"{$rf}\" name=\"{$column}tx\"");
        if($rf=='text'){ //文字検索ならdatalistを設定
          echo(" list=\"{$column}dl\">");
          echo("<datalist id=\"{$column}dl\">");
          foreach($itemlist[$name] as $data){
            echo("<option>{$data}</option>");
          }
          echo('</datalist>');
        }elseif($rf=='number'){ //数値なら最小最大を設定
          echo(' min='.min($itemlist[$column]).' max='.max($itemlist[$column]));
          if(array_key_exists('fm',$arr)){
            echo(' step='.pow(10,-$arr['fm']));
          }
          echo('>');
          echo('<span id="'.$column.'nm" style="display:none">～<input type="number" name="'.$column.'tx2" min='.min($itemlist[$column]).' max='.max($itemlist[$column]));
          if(array_key_exists('fm',$arr)){
            echo(' step='.pow(10,-$arr['fm']));
          }
          echo('></span>');
        }
        //検索方法の指定
        echo("<select name=\"{$column}pd\" id=\"{$column}pd\">");
        foreach($select[$rf] as $val=>$text){
          echo("<option value=\"{$val}\">{$text}</option>");
        }
        echo('</select>');
        if($rf=='number'){
          echo("<input type=\"checkbox\" value=1 name=\"{$column}rg\" id=\"{$column}rg\" onClick=\"changeRange('{$column}')\"");
          if(0){echo(' checked');}
          echo("><label for=\"{$column}rg\">範囲</label>");
        
