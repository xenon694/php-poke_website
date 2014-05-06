<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
  <meta name="format-detection" content="telephone=no">
  <link media="only screen and (max-device-width:480px)" href="mobile.css" type="text/css" rel="stylesheet" />
  <link media="screen and (min-device-width:481px)" href="design.css" type="text/css" rel="stylesheet" />

<!--�����������^�C�g������������-->
  <title>������ - Pn���{</title>
<!--����������������������������-->
</head>

<!--���^�C�g����-->
<?php	include 'title.html';	?>

<!--�����j���[��-->
<?php	include 'menu.html';	?>
<br style="clear:left;">

    <!--�����C����-->
    <div id="main">
      <!--�����͕���-->
      <div class="text">
<!--���������^�C�g���E�����������-->
        <h1>������(��)</h1>
        �|�P�����̂������Ƃ́A��l����(�_�C�������h�E�p�[��)����ǉ����ꂽ�v�f�ŁA<br>���̃|�P�����̈�ԍ����̒l�������B<br>
        ��ԍ����̒l�̃X�e�[�^�X�ƁA���̌̒l��5�Ŋ������]��ɂ���Č��肷��B<br>
<!--������������������������������-->

        <?php
/*���������������ڔz�񁥁���������*/
$tablename='';
          $items=[
'no'=>['ja'=>'No','en'=>['no'],'ex'=>'�ʂ��ԍ�','pd'=>0,'tx'=>0,'vl'=>2,'wd'=>50],
'name'=>['ja'=>'���O','en'=>['name'],'ex'=>'��','pd'=>0,'tx'=>1,'vl'=>0,'wd'=>200],
'name_ka'=>['ja'=>'����','en'=>['name_ka'],'ex'=>'�����ŕ\���������̕\�L(BW�ȍ~)','pd'=>0,'tx'=>1,'vl'=>0,'wd'=>200],
'name_en'=>['ja'=>'�p��','en'=>['name_en'],'ex'=>'�p��łł̕\�L','pd'=>0,'tx'=>1,'vl'=>0,'wd'=>200],
'mod'=>['ja'=>'�]��','en'=>['mod'],'ex'=>'��ԍ����̒l��5�Ŋ������]��','pd'=>1,'tx'=>0,'vl'=>1,'wd'=>50],
'stat'=>['ja'=>'�\��','en'=>['stat'],'ex'=>'��ԍ����X�e�[�^�X','pd'=>1,'tx'=>0,'vl'=>0,'wd'=>50]
//''=>['ja'=>'','en'=>[''],'ex'=>'','pd'=>0,'tx'=>0,'vl'=>0,'wd'=>0],
];

/*����������������������������������*/

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

<!--���\���ݒ�t�H�[����-->
        <form name="display" action="characteristic.php" method="GET">
          <input type="button" name="all_c" value="�S�đI��" id="all_c" onClick="allcheck(1);">
          <input type="button" name="all_d" value="�S�ĉ���" id="all_d" onClick="allcheck(0);">
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
                echo('<label><input type="checkbox" name="'.$key.'_txor" value="1">OR����</label>');
              }

              for($i=1;$i<=$arr['vl'];$i++){
                echo('<input type="text" name="'.$key.'_vl'.$i.'" value="'.'">');
                echo('<select class="refine" name="'.$key.'_rf'.$i.'"></select>');
              }
              //if($arr['vl']&&count($arr['en'])>1){
              if($arr['vl']>1){
                echo('<label><input type="checkbox" name="'.$key.'_vlor" value="1">OR����</label>');
              }

              for($i=1;$i<=$arr['pd'];$i++){
                echo('<select name="'.$key.'_pd'.$i.'" class="pulldown"></select>');
              }
              if($arr['pd']&&count($arr['en'])>1){
                echo('<label><input type="checkbox" name="'.$key.'_pdor" value="1">OR����</label>');
              }

              echo("<br>\n");
            }
          ?>
          <input type="hidden" name="search" value="1">

<!--�����בւ��I����-->
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
            <option value="ASC" <?php if($order=="ASC"){echo(" selected");} ?>>����</option>
            <option value="DESC" <?php if($order=="DESC"){echo(" selected");} ?>>�~��</option>
          </select>
          <input type="submit" id="load" value="�X�V"><br>
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


      <!--���e�[�u������-->
      <div id="listdiv">
      <table id="list">
        <!--���w�b�_��-->
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
        <!--���f�[�^��-->
        <tbody id="data">
        <?php

          $data=file(dirname(__FILE__).'/sql.dat',FILE_IGNORE_NEW_LINES);
          $sql=new mysqli($data[0],$data[1],$data[2],$data[3]);

          if(!$sql){die("MySQL error\n");}

          $sql->set_charset('sjis');

          /*��SQL�쐬��*/
          $qer = 'SELECT * FROM characteristic';
          /*���������쐬��*/

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
          /*���f�[�^���o����*/
          $res = $sql->query($qer);
          if(!$res){die('�G���[');}

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

          /*�v���_�E���I���쐬*/
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
          var item={"":"�i�荞��","=":"������","<>":"�������Ȃ�",">=":"�ȏ�","<=":"�ȉ�",">":"���傫��","<":"��菬����"};
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
          var item={"":"�i�荞��","match":"�ƈ�v����","include":"���܂�","start":"����n�܂�","end":"�ŏI���"};
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
              echo('pulldown['.$i.'].options[0]=new Option("�S�đI��","");');
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

      <!--�����[���t�H�[����-->
      <?php include 'mailform/contact.html';	?>
    </div>

    <!--���t�b�^�[��-->
    <?php include 'footer.html'; ?>
  </div>
</body>
</html>