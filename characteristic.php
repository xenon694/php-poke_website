<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
  <meta name="format-detection" content="telephone=no">
  <link media="only screen and (max-device-width:480px)" href="design-sp.css" type="text/css" rel="stylesheet" />
  <link media="screen and (min-device-width:481px)" href="design-pc.css" type="text/css" rel="stylesheet" />

  <!--▼▼▼タイトル▼▼▼-->
  <title>こせい - Pnラボ</title>
  <!--▲▲▲▲▲▲▲▲▲▲-->
</head>

<!--タイトル-->
<?php	include 'title.html';	?>

<!--メニュー-->
<?php	include 'menu.html';	?>

    <br style="clear:left;">

    <!--メイン-->
    <div id="main">
      <!--文章部-->
      <div class="text">
        <!--▼▼▼タイトル・解説▼▼▼-->
        <h1>こせい(個性)</h1>
        ポケモンのこせいとは、第四世代(ダイヤモンド・パール)から追加された要素で、<br>そのポケモンの一番高い個体値を示す。<br>
        一番高い個体値のステータスと、その個体値を5で割った余りによって決定する。<br>
        <!--▲▲▲▲▲▲▲▲▲▲▲▲▲-->

        <?php
/*▼▼▼項目配列▼▼▼*/
          $tablename='characteristic';
          $items=[
'no'=>['ja'=>'No','en'=>['no'],'ex'=>'通し番号','sc'=>0,'lk'=>0,'vl'=>2,'wd'=>50],
'name'=>['ja'=>'名前','en'=>['name'],'ex'=>'個性','sc'=>0,'lk'=>1,'vl'=>0,'wd'=>200],
'name_ka'=>['ja'=>'漢字','en'=>['name_ka'],'ex'=>'漢字で表示した時の表記(BW以降)','sc'=>0,'lk'=>1,'vl'=>0,'wd'=>200],
'name_en'=>['ja'=>'英語','en'=>['name_en'],'ex'=>'英語版での表記','sc'=>0,'lk'=>1,'vl'=>0,'wd'=>200],
'mod'=>['ja'=>'余り','en'=>['mod'],'ex'=>'一番高い個体値を5で割った余り','sc'=>1,'lk'=>0,'vl'=>1,'wd'=>50],
'stat'=>['ja'=>'能力','en'=>['stat'],'ex'=>'一番高いステータス','sc'=>1,'lk'=>0,'vl'=>0,'wd'=>50]
//''=>['ja'=>'','en'=>[''],'ex'=>'','sc'=>0,'lk'=>0,'vl'=>0,'wd'=>0],
];

/*▲▲▲▲▲▲▲▲▲▲*/

          $ref=['vl'=>['pd','tx'],'lk'=>['pd','tx'],'sc'=>['pd']];

          //検索内容を変数へ
          $input=[];
          foreach($items as $key=>$arr){
            $input[$key]['disp']=$_GET[$key];
            foreach($ref as $type=>$inns){
              if($arr[$type]>1){
                $input[$key][$type]['or']=$_GET[$key.'_'.$type.'or'];
              }
              foreach($inns as $in){
                for($i=0;$i<$arr[$type];$i++){
                  $input[$key][$type][$i+1][$in]=$_GET[$key.'_'.$type.$in.($i+1)];
                }
              }
            }
          }

          $sort=$_GET['sort'];
          $order=$_GET['order'];
          $search=$_GET['search'];

          if(is_null($search)){
            foreach($items as $key=>$arr){
              $input[$key]['disp']=1;
            }
          }
        ?>

<style type="text/css">
  <?php
    $whole=0;
    foreach($items as $key=>$arr){
      if($input[$key]['disp']){
        echo('.'.$key.'{width:'.$arr['wd']."px;}\n");
        foreach($arr['en'] as $c){
          $whole+=$arr['wd'];
        }
      }
    }
    echo("table#list{\nwidth:".($whole+40)."px;\n}");
  ?>
</style>

<!--表示設定フォーム-->
        <?php if(!$search){echo('<!--');} ?>
        <?php echo('<form name="display" action="'.$tablename.'.php" method="GET">'); ?>
          <input type="button" name="all_c" value="全て選択" id="all_c" onClick="allcheck(1);">
          <input type="button" name="all_d" value="全て解除" id="all_d" onClick="allcheck(0);">
          <br>
          <?php
            foreach($items as $key=>$arr){
              echo('<label><input type="checkbox" class="disp" name="'.$key.'" value="1"');
              if($input[$key]['disp']==1){echo(' checked');}
              echo('>');
              echo($arr['ja'].':'.$arr['ex']."</label><br>\n");
               foreach($ref as $type=>$inns){
                for($i=0;$i<$arr[$type];$i++){
                  if(in_array('tx',$inns)){
                    echo('<input type="text" class="reftx" name="'.$key.'_'.$type.'tx'.($i+1).'">');
                    }
                  if(in_array('pd',$inns)){
                    echo('<select class="refpd '.$type.'" name="'.$key.'_'.$type.'pd'.($i+1).'"></select>');
                  }
                }
                if($arr[$type]>1){
                  echo('<label><input type="checkbox" class="refor" name="'.$key.'_'.$type.'or" value="1">OR検索</label>');
                }
              }
              echo("<br>\n");
            }
          ?>

          <select name="sort">
            <?php
              foreach($items as $key=>$arr){
                $i=1;
                foreach($arr['en'] as $en){
                  echo('<option value="'.$en.'"');
                  if($sort==$key){echo(' selected');}
                  echo('>'.$arr['ja']);
                  if(count($arr['en'])>1){echo($i);$i++;}
                  echo('</option>');
                }
              }
            ?>
          </select>
          <select name="order">
            <option value="ASC" <?php if($order=="ASC"){echo(" selected");} ?>>昇順</option>
            <option value="DESC" <?php if($order=="DESC"){echo(" selected");} ?>>降順</option>
          </select>
          <input type="submit" id="load" value="更新"><br>
          <input type="reset"><br>
        </form>
        <?php if(!$search){echo('-->');} ?>

        <script type="text/javascript">
          function allcheck(state){
            var elem = document.getElementsByClassName("disp");
            for(var i=0;i<elem.length;i++){
              elem[i].checked=state;
            }
          }
        </script>
      </div><!--.text-->

      <?php if($search){echo('<!--');} ?>

      <?php echo('<a href="'.$tablename.'.php?'.$_SERVER['QUERY_STRING'].'&search=1">検索条件を入力</a>'); ?>

      <div id="listdiv">
      <table id="list">
        <thead>
          <tr id="head">
          <?php
            foreach($items as $key=>$arr){
              if($input[$key]['disp']){
                foreach($arr['en'] as $names){
                  echo('<th class="'.$key.'">'.$arr['ja'].'</th>');
                }
              }
            }
          ?>
          </tr><!--#head-->
        </thead>
        <tbody id="data">
        <?php

          $data=file(dirname(__FILE__).'/sql.dat',FILE_IGNORE_NEW_LINES);
          $sql=new mysqli($data[0],$data[1],$data[2],$data[3]);

          if(!$sql){die("MySQL error\n");}

          $sql->set_charset('utf8');

          /*SQL作成*/
          $qer = 'SELECT * FROM '.$tablename;
          /*検索文作成*/
          $first=true;
          foreach($items as $key=>$arr){
            foreach($ref as $type=>$inns){
              for($i=0;$i<$arr[$type];$i++){
                if(array_key_exists('or',$input[$key][$type])){
                  $or=$input[$key][$type]['or'];
                }else{
                  $or=0;
                }

                $pass=0;
                if(array_key_exists('tx',$input[$key][$type][$i+1])){
                  $tx=$input[$key][$type][$i+1]['tx'];
                  if($tx==''){$pass=1;}
                }
                if(array_key_exists('pd',$input[$key][$type][$i+1])){
                  $pd=$input[$key][$type][$i+1]['pd'];
                  if($pd==''){$pass=1;}
                }
                if($pass){continue;}

                if($first){
                  $qer = $qer.' WHERE';
                  $first=false;
                }elseif($i>0&&$or){
                  $qer = $qer.' OR';
                }else{
                  $qer = $qer.' AND';
                }

                if(!$or||$i==0){
                  $qer = $qer.' (';
                }
                foreach($arr['en'] as $index=>$names){
                  if($index>0){
                    $qer = $qer.' OR';
                  }
                  switch($type){
                    case 'vl':
                      $qer = $qer.' `'.$names.'`'.$pd."'".$tx."'";
                      break;
                    case 'lk':
                      $qer = $qer.' `'.$names.'` LIKE';
                      switch($pd){
                        case 'match':
                          $qer = $qer." '".$tx."'";
                          break;
                        case 'include':
                          $qer = $qer." '%".$tx."%'";
                          break;
                        case 'start':
                          $qer = $qer." '".$tx."%'";
                          break;
                        case 'end':
                          $qer = $qer." '%".$tx."'";
                          break;
                      }
                      break;
                    case 'sc':
                      $qer = $qer.' `'.$names."`='".$pd."'";
                      break;
                  }
                }
                if(!$or||$i==$arr[$type]-1){
                  $qer = $qer.' )';
                }
              }
            }
          }
          if(!is_null($sort)){
            $qer = $qer.' ORDER BY `'.$sort.'` '.$order;
          }
          //var_dump($qer);
          //var_dump($_SERVER['QUERY_STRING']);

          /*データ取り出し*/
          $res = $sql->query($qer);
          if(!$res){die('エラー');}

          while($row = $res->fetch_array(MYSQLI_BOTH)){
            echo('<tr>');
            foreach($items as $key=>$arr){
              if($input[$key]['disp']){
                foreach($arr['en'] as $names){
                  echo('<td class="'.$key.'">'.$row[$names].'</td>');
                }
              }
            }
            echo("</tr>\n");
          }
          mysqli_free_result($res);

          /*プルダウン選択作成*/
          $i=0;
          foreach($items as $key=>$arr){
            if($arr['sc']==0){continue;}
            $itemlist[$i]=array();
            $qer = 'SELECT DISTINCT `'.$arr['en'][0].'` FROM '.$tablename;
            $res = $sql->query($qer);
            while($row = $res->fetch_array(MYSQLI_BOTH)){
              $itemlist[$i][]=$row[$arr['en'][0]];
            }
            $i++;
          }
          mysqli_free_result($res);

          mysql_close();

        ?>
        </tbody><!--#data-->
      </table><!--list-->
      </div><!--listdiv-->
      <?php if($search){echo('-->');} ?>

      <script type="text/javascript">
        window.onload=function(){
          refineset();
          beforeset();
        }

        function refineset(){
          /******** vl **********/
          var vl=document.getElementsByClassName("vl");
          var item={"":"絞り込み","=":"等しい","<>":"等しくない",">=":"以上","<=":"以下",">":"より大きい","<":"より小さい"};
          var i=0;
          for(var p=0;p<vl.length;p++){
            i=0;
            for(var key in item){
              vl[p].options[i]=new Option(item[key],key);
              i++;
            }
          }
          /********* lk *********/
          var lk=document.getElementsByClassName("lk");
          var item={"":"絞り込み","match":"と一致する","include":"を含む","start":"から始まる","end":"で終わる"};
          var i=0;
          for(var p=0;p<lk.length;p++){
            i=0;
            for(var key in item){
              lk[p].options[i]=new Option(item[key],key);
              i++;
            }
          }
          /********* sc **********/
          var sc=document.getElementsByClassName("sc");
          <?php
            for($i=0;$i<count($itemlist);$i++){
              echo('sc['.$i.'].options[0]=new Option("全て選択","");');
              echo("\n");
              for($j=0;$j<count($itemlist[$i]);$j++){
                echo('sc['.$i.'].options['.($j+1).']=new Option("'.$itemlist[$i][$j].'","'.$itemlist[$i][$j].'");');
                echo("\n");
              }
            }
          ?>
        }

        function beforeset(){
          var tx=document.getElementsByClassName("reftx");
          var pd=document.getElementsByClassName("refpd");
          var or=document.getElementsByClassName("refor");
          <?php

            $t=0;
            $p=0;
            $o=0;
            foreach($items as $key=>$arr){
              foreach($ref as $type=>$inns){
                for($i=0;$i<$arr[$type];$i++){
                  if(in_array('pd',$inns)){
                    echo("for(i=0;i<7;i++){\n");
                    echo('if(pd['.$p.'].options[i].value=="'.$input[$key][$type][$i+1]['pd']."\"){\n");
                    echo("pd[".$p."].options[i].selected=true;\n");
                    echo("break;\n}\n}\n");
                    $p++;
                  }
                  if(in_array('tx',$inns)){
                    echo('tx['.$t.'].value="'.$input[$key][$type][$i+1]['tx'].'";');
                    $t++;
                  }
                }
                if($arr[$type]>1){
                  if($input[$key][$type]['or']==1){
                    echo('or['.$o.'].checked=true;');
                  }
                  $o++;
                }
              }
            }
          ?>
        }

      </script>

      <!--メールフォーム-->
      <?php include 'mailform/contact.html';	?>
    </div><!--#main-->

    <!--フッター-->
    <?php include 'footer.html'; ?>
  </div><!--contents-->
</body>
</html>