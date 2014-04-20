<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
  <meta name="format-detection" content="telephone=no">
  <link media="only screen and (max-device-width:480px)" href="mobile.css" type="text/css" rel="stylesheet" />
  <link media="screen and (min-device-width:481px)" href="design.css" type="text/css" rel="stylesheet" />

<!--�����^�C�g������-->
  <title>������ - �|�P�����f�[�^����</title>
<!--����������������-->
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
          $items=array(
"no"=>array("ja"=>"No","en"=>array("no","mod"),"ex"=>"�ʂ��ԍ�","pd"=>0,"tx"=>0,"vl"=>1,"wd"=>50),
"name"=>array("ja"=>"���O","en"=>"name","ex"=>"��","pd"=>0,"tx"=>1,"vl"=>0,"wd"=>200),
"name_ka"=>array("ja"=>"����","en"=>"name_ka","ex"=>"�����ŕ\���������̕\�L(BW�ȍ~)","pd"=>0,"tx"=>1,"vl"=>0,"wd"=>200),
"name_en"=>array("ja"=>"�p��","en"=>"name_en","ex"=>"�p��łł̕\�L","pd"=>0,"tx"=>1,"vl"=>0,"wd"=>200),
//"mod"=>array("ja"=>"�]��","en"=>"mod","ex"=>"��ԍ����̒l��5�Ŋ������]��","pd"=>1,"tx"=>0,"vl"=>1,"wd"=>50),
"stat"=>array("ja"=>"�\��","en"=>"stat","ex"=>"��ԍ����X�e�[�^�X","pd"=>1,"tx"=>0,"vl"=>0,"wd"=>50)
);
/*����������������������������������*/
          $disp=array();
          foreach($items as $key=>$arr){
            $disp[$key]=$_GET[$key];
          }

          $sort=$_GET['sort'];
          $order=$_GET['order'];
          $issub=$_GET['info'];
        ?>
<style type="text/css">
  <?php
    $whole=0;
    foreach($items as $key=>$arr){
      if($disp[$key]){
        echo(".".$key."{width:".$arr["wd"]."px;}");
        for($i=0;$i<count($arr["en"]);$i++){
          $whole+=$arr["wd"];
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
              echo("<label><input type=\"checkbox\" class=\"disp\" name=\"".$key."\" value=\"1\"");
              if($disp[$key]==1){echo(" checked");}
              echo(">");
              echo($arr["ja"].":".$arr["ex"]."</label><br>");

              // onChange=\"sele(".$i.");\"

              if($arr["tx"]){
                echo("<input type=\"text\" name=\"".$key."_tx\">");
                echo("<select class=\"like\" name=\"".$key."_lk\"></select>");
              }

              if($arr["vl"]){
                echo("<input type=\"text\" name=\"".$key."_vl\">");
                echo("<select class=\"refine\" name=\"".$key."_rf\"></select>");
              }

              if($arr["pd"]){
                echo("<select name=\"".$key."_pd\" class=\"pulldown\"></select>");
              }

              echo("<br>");
            }
          ?>

          <input type="hidden" name="info" value="send">

<!--�����בւ��I����-->
          <select name="sort">
            <?php
              foreach($items as $key=>$arr){
                echo("<option value=\"".$key."\"");
                if($sort==$key){echo(" selected");}
                echo(">".$arr["ja"]."</option>");
              }
            ?>
          </select>
          <select name="order">
            <option value="ASC" selected>����</option>
            <option value="DESC">�~��</option>
          </select>
          <input type="submit" id="load" value="�X�V"><br>
          <input type="reset" id="load"><br>
        </form>

        <script type="text/javascript">
          <?php
            if(!$issub){
              //echo("all_c.click();load.click();");
            }
          ?>

          function allcheck(state){
            var elem = document.getElementsByClassName("disp");
            for(var i=0;i<elem.length;i++){
              elem[i].checked=state;
            }
          }
/*
          function sele(index){
            var refine=document.getElementsByName("refine");
            var pulldown=document.getElementsByClassName("pulldown");
            var sindex=refine[index].selectedIndex;
            switch(sindex){
              case 10:
                pulldown[index].disabled=false;
                break;
              default:
                pulldown[index].disabled=true;
                break;
                
            }
          }
*/
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
              if($disp[$key]){
                if(is_array($arr["en"])){
                  foreach($arr["en"] as $names){
                    echo"<th class=\"".$key."\">".$arr["ja"]."</th>";
                  }
                }else{
                  echo"<th class=\"".$key."\">".$arr["ja"]."</th>";
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

          $sql->set_charset("sjis");

          /*��SQL�쐬��*/
          $qer = "SELECT * FROM characteristic";
          /*���������쐬��*/
/*
          $first=true;
          foreach($items as $key=>$arr){
            if($arr["vl"]){
              $refine=$_GET[$arr["en"]."_rf"];
              $vl=$_GET[$arr["en"]."_vl"];
              if($refine!=""&&$vl!=""){
                if($first){
                  $qer = $qer." WHERE";
                  $first=false;
                }else{
                  $qer = $qer." AND";
                }
                $qer = $qer." `".$arr["en"]."`".$refine."'".$vl."'";
              }
            }
            if($arr["tx"]){
              $like=$_GET[$arr["en"]."_lk"];
              $tx=$_GET[$arr["en"]."_tx"];
              if($like!=""&&$tx!=""){
                if($first){
                  $qer = $qer." WHERE";
                  $first=false;
                }else{
                  $qer = $qer." AND";
                }
                $qer = $qer." `".$arr["en"]."` LIKE";
                switch($like){
                  case "match":
                    $qer = $qer." '".$tx."'";
                    break;
                  case "include":
                    $qer = $qer." '%".$tx."%'";
                    break;
                  case "start":
                    $qer = $qer." '".$tx."%'";
                    break;
                  case "end":
                    $qer = $qer." '%".$tx."'";
                    break;
                }
              }
            }
            if($arr["pd"]){
              $pd=$_GET[$arr["en"]."_pd"];
              if($pd!=""){
                if($first){
                  $qer = $qer." WHERE";
                  $first=false;
                }else{
                  $qer = $qer." AND";
                }
                $qer = $qer." `".$arr["en"]."`='".$pd."'";
              }
            }
          }

          $qer = $qer." ORDER BY `".$sort."` ".$order;
*/
          //var_dump($qer);
          /*���f�[�^���o����*/
          $res = $sql->query($qer);
          if(!$res){die("�G���[");}

          while($row = $res->fetch_array(MYSQLI_BOTH)){
            echo("<tr>");
            foreach($items as $key=>$arr){
              if($disp[$key]){
                if(is_array($arr["en"])){
                  foreach($arr["en"] as $names){
                    echo("<td class=\"".$key."\">".$row[$names]."</td>");
                  }
                }else{
                  echo("<td class=\"".$key."\">".$row[$arr["en"]]."</td>");
                }
              }
            }
            echo("</tr>\n");
          }
          mysqli_free_result($res);

          /*�v���_�E���I���쐬*/
          $i=0;
          foreach($items as $arr){
            if($arr["pd"]==0){continue;}
            $itemlist[$i]=array();
            $qer = "SELECT DISTINCT `".$arr["en"]."` FROM characteristic";
            $res = $sql->query($qer);
            while($row = $res->fetch_array(MYSQLI_BOTH)){
              $itemlist[$i][]=$row[$arr["en"]];
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
        window.onload=itemset;
        function itemset(){
          refineset();
          likeset();
          pulldownset();
        }

        function refineset(){
          var refine=document.getElementsByClassName("refine");
          var item={"":"�i�荞��","=":"������","<>":"�������Ȃ�",">=":"�ȏ�","<=":"�ȉ�",">":"���傫��","<":"��菬����"};
          var i=0;
          if(refine.length>1){
            for(var p=0;p<refine.length;p++){
              i=0;
              for(var key in item){
                refine[p].options[i]=new Option(item[key],key);
                i++;
              }
            }
          }else if(refine.length==1){
              for(var key in item){
                refine.options[i]=new Option(item[key],key);
                i++;
              }
          }
        }

        function likeset(){
          var like=document.getElementsByClassName("like");
          var item={"":"�i�荞��","match":"�ƈ�v����","include":"���܂�","start":"����n�܂�","end":"�ŏI���"}
         var i=0;
          if(like.length>1){
            for(var p=0;p<like.length;p++){
              i=0;
              for(var key in item){
                like[p].options[i]=new Option(item[key],key);
                i++;
              }
            }
          }else if(like.length==1){
              for(var key in item){
                like.options[i]=new Option(item[key],key);
                i++;
              }
          }
        }

        function pulldownset(){
          var pulldown=document.getElementsByClassName("pulldown");
          if(pulldown.length>1){
            <?php
              for($i=0;$i<count($itemlist);$i++){
                echo("pulldown[$i].options[0]=new Option(\"�S�đI��\",\"\");\n");
                for($j=0;$j<count($itemlist[$i]);$j++){
                  echo("pulldown[$i].options[".($j+1)."]=new Option(\"".$itemlist[$i][$j]."\",\"".$itemlist[$i][$j]."\");\n");
                }
              }
            ?>
          }else if(pulldown.length==1){
            <?php
                echo("pulldown.options[0]=new Option(\"�S�đI��\",\"\");\n");
                for($j=0;$j<count($itemlist[0]);$j++){
                  echo("pulldown.options[".$j."]=new Option(\"".$itemlist[0][$j]."\",\"".$itemlist[0][$j]."\");\n");
                }
            ?>
          }
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