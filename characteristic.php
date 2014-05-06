<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
  <meta name="format-detection" content="telephone=no">
  <link media="only screen and (max-device-width:480px)" href="mobile.css" type="text/css" rel="stylesheet" />
  <link media="screen and (min-device-width:481px)" href="design.css" type="text/css" rel="stylesheet" />

<!--▼▼▼▼▼タイトル▼▼▼▼▼-->
  <title>こせい - Pnラボ</title>
<!--▲▲▲▲▲▲▲▲▲▲▲▲▲▲-->
</head>

<!--■タイトル■-->
<?php	include 'title.html';	?>

<!--■メニュー■-->
<?php	include 'menu.html';	?>
<br style="clear:left;">

    <!--■メイン■-->
    <div id="main">
      <!--■文章部■-->
      <div class="text">
<!--▼▼▼▼タイトル・解説▼▼▼▼-->
        <h1>こせい(個性)</h1>
        ポケモンのこせいとは、第四世代(ダイヤモンド・パール)から追加された要素で、<br>そのポケモンの一番高い個体値を示す。<br>
        一番高い個体値のステータスと、その個体値を5で割った余りによって決定する。<br>
<!--▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲-->

        <?php
/*▼▼▼▼▼▼項目配列▼▼▼▼▼▼*/
$tablename='';
          $items=[
'no'=>['ja'=>'No','en'=>['no'],'ex'=>'通し番号','pd'=>0,'tx'=>0,'vl'=>2,'wd'=>50],
'name'=>['ja'=>'名前','en'=>['name'],'ex'=>'個性','pd'=>0,'tx'=>1,'vl'=>0,'wd'=>200],
'name_ka'=>['ja'=>'漢字','en'=>['name_ka'],'ex'=>'漢字で表示した時の表記(BW以降)','pd'=>0,'tx'=>1,'vl'=>0,'wd'=>200],
'name_en'=>['ja'=>'英語','en'=>['name_en'],'ex'=>'英語版での表記','pd'=>0,'tx'=>1,'vl'=>0,'wd'=>200],
'mod'=>['ja'=>'余り','en'=>['mod'],'ex'=>'一番高い個体値を5で割った余り','pd'=>1,'tx'=>0,'vl'=>1,'wd'=>50],
'stat'=>['ja'=>'能力','en'=>['stat'],'ex'=>'一番高いステータス','pd'=>1,'tx'=>0,'vl'=>0,'wd'=>50]
//''=>['ja'=>'','en'=>[''],'ex'=>'','pd'=>0,'tx'=>0,'vl'=>0,'wd'=>0],
];

/*▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲*/

          $input=[];
          foreach($items as $key=>$arr){
            $input[$key]['disp']=$_GET[$key];
            for($i=0;$i<$arr['vl'];$i++){
              if($i==0){$input[$key]['vl']['or']=$_GET[$key.'_vlor'];}
              $input[$key]['vl'][$i+1]['pd']=$_GET[$key.'_rf'.($i+1)];
              $input[$key]['vl'][$i+1]['tx']=$_GET[$key.'_vl'.($i+1)];
            }
            for($i=0;$i<$arr['tx'];$i++){
              if($i==0){
                $input[$key]['tx']['or']=$_GET[$key.'_txor'];
              }
              $input[$key]['tx'][$i+1]['pd']=$_GET[$key.'_lk'.($i+1)];
              $input[$key]['tx'][$i+1]['tx']=$_GET[$key.'_tx'.($i+1)];
            }
            for($i=0;$i<$arr['pd'];$i++){
              if($i==0){
                $input[$key]['pd']['or']=$_GET[$key.'_pdor'];
              }
              $input[$key]['pd'][$i+1]['pd']=$_GET[$key.'_pd'.($i+1)];
            }
          }

          $sort=$_GET['sort'];
          $order=$_GET['order'];
          $issub=$_GET['search'];
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

<!--■表示設定フォーム■-->
        <form name="display" action="characteristic.php" method="GET">
          <input type="button" name="all_c" value="全て選択" id="all_c" onClick="allcheck(1);">
          <input type="button" name="all_d" value="全て解除" id="all_d" onClick="allcheck(0);">
          <br>
          <?php
            foreach($items as $key=>$arr){
              echo('<label><input type="checkbox" class="disp" name="'.$key.'" value="1"');
              if($input[$key]['disp']==1){echo(' checked');}
              echo('>');
              echo($arr['ja'].':'.$arr['ex']."</label><br>\n");

              // onChange="sele(".$i.");"

              for($i=1;$i<=$arr['tx'];$i++){
                echo('<input type="text" name="'.$key.'_tx'.$i.'" value="'.'">');
                echo('<select class="like" name="'.$key.'_lk'.$i.'"></select>');
              }
              //if($arr['tx']&&count($arr['en'])>1){
              if($arr['tx']>1){
                echo('<label><input type="checkbox" name="'.$key.'_txor" value="1">OR検索</label>');
              }

              for($i=1;$i<=$arr['vl'];$i++){
                echo('<input type="text" name="'.$key.'_vl'.$i.'" value="'.'">');
                echo('<select class="refine" name="'.$key.'_rf'.$i.'"></select>');
              }
              //if($arr['vl']&&count($arr['en'])>1){
              if($arr['vl']>1){
                echo('<label><input type="checkbox" name="'.$key.'_vlor" value="1">OR検索</label>');
              }

              for($i=1;$i<=$arr['pd'];$i++){
                echo('<select name="'.$key.'_pd'.$i.'" class="pulldown"></select>');
              }
              if($arr['pd']&&count($arr['en'])>1){
                echo('<label><input type="checkbox" name="'.$key.'_pdor" value="1">OR検索</label>');
              }

              echo("<br>\n");
            }
          ?>
          <input type="hidden" name="search" value="1">

<!--■並べ替え選択■-->
          <select name="sort">
            <?php
              foreach($items as $key=>$arr){
                echo('<option value="'.$key.'"');
                if($sort==$key){echo(' selected');}
                echo('>'.$arr['ja'].'</option>');
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

        <script type="text/javascript">
          <?php
            if(!$issub){
              echo('all_c.click();load.click();');
            }
          ?>

          function allcheck(state){
            var elem = document.getElementsByClassName("disp");
            for(var i=0;i<elem.length;i++){
              elem[i].checked=state;
            }
          }
        </script>
      </div>


      <!--■テーブル部■-->
      <div id="listdiv">
      <table id="list">
        <!--●ヘッダ●-->
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
          </tr>
        </thead>
        <!--●データ●-->
        <tbody id="data">
        <?php

          $data=file(dirname(__FILE__).'/sql.dat',FILE_IGNORE_NEW_LINES);
          $sql=new mysqli($data[0],$data[1],$data[2],$data[3]);

          if(!$sql){die("MySQL error\n");}

          $sql->set_charset('sjis');

          /*■SQL作成■*/
          $qer = 'SELECT * FROM characteristic';
          /*●検索文作成●*/

          $first=true;


          foreach($items as $key=>$arr){
            for($i=0;$i<$arr['vl'];$i++){
              $or=$input[$key]['vl']['or'];
              $tx=$input[$key]['vl'][$i+1]['tx'];
              $pd=$input[$key]['vl'][$i+1]['pd'];
              if($tx!=''&&$pd!=''){
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
                  $qer = $qer.' `'.$names.'`'.$pd."'".$tx."'";
                }
                if(!$or||$i==$arr['vl']-1){
                  $qer = $qer.' )';
                }
              }
            }

            for($i=0;$i<$arr['tx'];$i++){
              $or=$input[$key]['tx']['or'];
              $tx=$input[$key]['tx'][$i+1]['tx'];
              $pd=$input[$key]['tx'][$i+1]['pd'];
              if($pd!=''&&$tx!=''){
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
                }
                if(!$or||$i==$arr['tx']-1){
                  $qer = $qer.' )';
                }
              }
            }

            for($i=0;$i<$arr['pd'];$i++){
              $or=$input[$key]['pd']['or'];
              $pd=$input[$key]['pd'][$i+1]['pd'];
              if($pd!=''){
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
                    $qer = $qer.' OR ';
                  }
                  $qer = $qer.' `'.$names."`='".$pd."'";
                }
                if(!$or||$i==$arr['pd']-1){
                  $qer = $qer.' )';
                }
              }
            }
          }


          $qer = $qer.' ORDER BY `'.$sort.'` '.$order;
          var_dump($qer);
          var_dump($input);
          /*■データ取り出し■*/
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
            if($arr['pd']==0){continue;}
            $itemlist[$i]=array();
            $qer = 'SELECT DISTINCT `'.$arr['en'][0].'` FROM characteristic';
            $res = $sql->query($qer);
            while($row = $res->fetch_array(MYSQLI_BOTH)){
              $itemlist[$i][]=$row[$arr['en'][0]];
            }
            $i++;
          }
          mysqli_free_result($res);

          mysql_close();
        ?>
        </tbody>
      </table>
      </div>

      <script type="text/javascript">
        window.onload=function(){
          refineset();
          likeset();
          pulldownset();
        }

        function refineset(){
          var refine=document.getElementsByClassName("refine");
          var item={"":"絞り込み","=":"等しい","<>":"等しくない",">=":"以上","<=":"以下",">":"より大きい","<":"より小さい"};
          var i=0;
          for(var p=0;p<refine.length;p++){
            i=0;
            for(var key in item){
              refine[p].options[i]=new Option(item[key],key);
              i++;
            }
          }
        }

        function likeset(){
          var like=document.getElementsByClassName("like");
          var item={"":"絞り込み","match":"と一致する","include":"を含む","start":"から始まる","end":"で終わる"};
          var i=0;
          for(var p=0;p<like.length;p++){
            i=0;
            for(var key in item){
              like[p].options[i]=new Option(item[key],key);
              i++;
            }
          }
        }
        function pulldownset(){
          var pulldown=document.getElementsByClassName("pulldown");
          <?php
            for($i=0;$i<count($itemlist);$i++){
              echo('pulldown['.$i.'].options[0]=new Option("全て選択","");');
              echo("\n");
              for($j=0;$j<count($itemlist[$i]);$j++){
                echo('pulldown['.$i.'].options['.($j+1).']=new Option("'.$itemlist[$i][$j].'","'.$itemlist[$i][$j].'");');
                echo("\n");
              }
            }
          ?>
          }

          function beforeset(){
            
          }

      </script>

      <!--■メールフォーム■-->
      <?php include 'mailform/contact.html';	?>
    </div>

    <!--■フッター■-->
    <?php include 'footer.html'; ?>
  </div>
</body>
</html>