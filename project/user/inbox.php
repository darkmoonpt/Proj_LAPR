<?php
require "../inc/head.php";

if(!isset($_SESSION['id'])) {
    echo "Não tens acesso a esta pagina";
    header("refresh:1;url=../login.php");
    die();
}

$name = access('name');

if(isset($_POST['delete'])) {
    $query = 'delete from message where id ="'.$_POST['delete'].'"';
    $result = mysqli_query($conn, $query);
}

$random_number1 = mt_rand(0, 10);
$random_number2 = mt_rand(0, 10);
$checkTotal = $random_number1 + $random_number2;

?>

<div id="wrapper">
        <?php require "../inc/menu.php"; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Inbox
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <a href="../home.php">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-envelope"> </i> Inbox
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-lg-12">
                      <table class="table">
                        <tbody>
                          <tr>
                            <form method="POST" action="">
                            <button type="submit" name="sendmail" class="btn btn-danger">Compor</button>
                            </form>
                            <?php
                            $pageNumber=5;

                            $offset= (mysqli_real_escape_string($conn, $_GET["page"])-1)*$pageNumber;
                            if($offset<0) {
                                $offset=0;
                            }
                            $offset2= (mysqli_real_escape_string($conn, $_GET["page2"])-1)*$pageNumber;
                            if($offset2<0) {
                                $offset2=0;
                            }

                            $count = mysqli_num_rows(mysqli_query($conn, 'SELECT * FROM message where para = "'.$name.'";'));
                            $count2 = mysqli_num_rows(mysqli_query($conn, 'SELECT * FROM message where de = "'.$name.'";'));

                            //$query = 'SELECT * FROM client WHERE private=0 ORDER BY name ASC LIMIT '.$pageNumber.' OFFSET '.$offset.';';


                            $sql = 'SELECT * FROM message where para = "'.$name.'" ORDER BY id desc LIMIT '.$pageNumber.' OFFSET '.$offset.';';
                            $result = mysqli_query($conn, $sql);
                            echo "<h1>Correio Recebido</h1>";
                            echo '<table align="center" class="table table-striped table-bordered">
                            <tr>
                              <th>FROM</th>
                              <th>TO</th>
                              <th>TITLE</th>
                              <th>DATE</th>
                              <th>ACTIONS</th>
                            </tr>';
                            echo '<form method="POST" action="">';
                            while ($row = mysqli_fetch_assoc($result))
                            {
                                    echo ($row["leu"]==0) ?'<tr style="background-color: rgba(250, 229, 84, 0.83);">': '<tr>';
                                    echo '<td>' . $row["de"] . '</td>';
                                    echo '<td>' . $row["para"] . '</td>';
                                    echo '<td>' . $row["title"] . '</td>';
                                    echo '<td>' . $row["date"] . '</td>';
                                    echo '<td><button type="submit" class="btn btn-default" name="submit" value="' . $row["id"] . '">View</button>
                                    <button type="submit" class="btn btn-default" name="delete" value="' . $row["id"] . '">Delete</button></td>';

                                    echo '</tr>';
                            }
                            echo '</form>';
                            echo '</table>';
                            //mysqli_close($conn);
                                ?>
                                <nav aria-label="...">
                                    <center>
                                        <ul class="pagination pagination-sm">
                                                <?php
                                                for($i=0;$i<($count/$pageNumber);$i++){
                                                    echo '<li class="page-item"><a class="page-link" href="'.current_file().'?page='.($i+1).'">'.($i+1).'</a></li>';
                                                }
                                                ?>
                                        </ul>
                                    </center>
                                </nav>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-12">
                                <?php

                                if(isset($_POST['submit2'])) {
                                    $captchaResult = $_POST["captchaResult"];
                                    $firstNumber = $_POST["firstNumber"];
                                    $secondNumber = $_POST["secondNumber"];
                                    $checkTotal = $firstNumber + $secondNumber;
                                    if ($captchaResult == $checkTotal) {
                                          $query = 'insert into message(de,para,title,message,date) values ("'.$name.'","'.$_POST["para"].'","'.$_POST["title"].'","'.$_POST["message"].'",curdate()) ';
                                          $result = mysqli_query($conn, $query);
                                    } else {
                                        echo '<div class="alert alert-danger">
                                        <strong>Wrong Captcha!</strong> Message not sent.
                                        </div>';
                                    }
                                }
                                    ?>
                                        <?php
                                        if(isset($_POST['submit'])) {
                                            echo '<h1>Mensagem:</h1>';
                                            echo "<br /><br />";
                                            $id = $_POST['submit'];
                                            $query = "select * from message where id=$id";
                                            $result = mysqli_query($conn, $query);
                                            while ($row = mysqli_fetch_assoc($result)){
                                                echo '<h3>Topico:</h3>';
                                                echo '<td>' . $row["title"] . '</td>';
                                                $de = $row["de"];
                                                echo '<h3>Mensagem:</h3>';
                                                echo '<td>' . $row["message"] . '</td>';
                                            }
                                            if ($result) {
                                        ?>

                                            <form method="POST" action="">
                                            <br>
                                            <input type='hidden' name='de' value='<?php echo "$de";?>'/>
                                            <button type="submit" name="responder" class="btn btn-primary">Responder</button>
                                            <br><br>
                                            </form>
                                                <?php
                                                $query = "update message set leu=1 where id=$id";
                                                $result = mysqli_query($conn, $query);
                                            }
                                        }
                                        ?>
                              </div>
                          </div>

                          <div class="row">
                              <div class="col-lg-12">
                                    <?php

                                    if(isset($_POST['sendmail']) || isset($_POST['responder'])) {
                                        ?>
                                        <form class="form-horizontal" role="form" method="POST" action="">
                                        <div class="form-group">
                                        <label for { ="name" class="col-sm-2 control-label">De</label>
                                        <div class="col-sm-10">
                                            <?php
                                            if(isset($_POST['responder'])) {
                                                echo '<input type="text" class="form-control" id="name" name="de" placeholder="First & Last Name"  value="'.$name.'" readonly>';
                                            }else{
                                                echo '<input type="text" class="form-control" id="name" name="de" placeholder="First & Last Name" value="'.$name.'" readonly>';
                                            }
                                                ?>
                                                </div>
                                                </div>
                                                <div class="form-group">
                                                <label for { ="email" class="col-sm-2 control-label">Para</label>
                                                <div class="col-sm-10">
                                                    <?php
                                                    if(isset($_POST['responder'])) {
                                                        echo '<input type="text" class="form-control" id="name" name="para" placeholder="First & Last Name"  value="'.$_POST['de'].'">';
                                                    }else{
                                                        //echo '<input type="text" class="form-control" id="name" name="para" placeholder="First & Last Name" value="">';

                                                        $sql=mysqli_query($conn, "SELECT id_user,name FROM users");
                                                        if(mysqli_num_rows($sql)) {
                                                            $select= '<select name="para">';
                                                            while($rs=mysqli_fetch_array($sql)){
                                                                $select.='<option value="'.$rs['name'].'">'.$rs['name'].'</option>';
                                                            }
                                                        }
                                                        $select.='</select>';
                                                        echo $select;
                                                    }
                                                        ?>
                                                    </div>
                                                    </div>
                                                    <div class="form-group">
                                                    <label for { ="message" class="col-sm-2 control-label">Title</label>
                                                        <div class="col-sm-10">
                                                        <textarea class="form-control" rows="4" name="title"></textarea>
                                                        </div>
                                                        </div>
                                                    <div class="form-group">
                                                    <label for { ="message" class="col-sm-2 control-label">Message</label>
                                                        <div class="col-sm-10">
                                                        <textarea class="form-control" rows="4" name="message"></textarea>
                                                        </div>
                                                        </div>
                                                        <div class="form-group">
                                                        <label for { ="human" class="col-sm-2 control-label"><?php
                                                          echo $random_number1 . ' + ' . $random_number2 . ' = ';
                                                            ?></label>
                                                        <div class="col-sm-10">
                                                        <input name="captchaResult" type="text" size="2" />
                                                        <input name="firstNumber" type="hidden" value="<?php echo $random_number1; ?>" />
                                                            <input name="secondNumber" type="hidden" value="<?php echo $random_number2; ?>" />
                                                        </div>
                                                        </div>
                                                        <div class="form-group col-sm-10 col-sm-offset-2">
                                                        <input name="submit2" type="submit" value="Send" class="btn btn-primary">
                                                        </div>
                                                        <div class="form-group col-sm-10 col-sm-offset-2">
                                                        <! Will be used to display an alert to the user>
                                                        </div>
                                                        </form>

                                    <?php } ?>
                              </div>
                          </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                      <table class="table">
                                <?php
                                $sql = 'SELECT * FROM message where de = "'.$name.'" ORDER BY id desc LIMIT '.$pageNumber.' OFFSET '.$offset2.';';
                                $result = mysqli_query($conn, $sql);
                                echo "<h1>Correio Enviado</h1>";
                                echo '<table align="center" class="table table-striped table-bordered"><tr><th>FROM</th><th>TO</th><th>TITLE
                        </th><th>DATE</th><th>ACTIONS</th></tr>';
                                while ($row = mysqli_fetch_assoc($result))
                                {
                                    echo '<form method="POST" action="">';
                                    echo '<td>' . $row["de"] . '</td>';
                                    echo '<td>' . $row["para"] . '</td>';
                                    //echo '<td>' . $row["title"] . '</td>';
                                    echo '<td><button style="background-color: transparent;" type="submit" name="submit" value="' . $row["id"] . '">' . $row["title"] . '</button></td>';
                                    //echo '<td><button style="background-color: transparent;" type="submit" name="submit" value="' . $row["id"] . '">' . $row["message"] . '</button></td>';
                                    echo '<td>' . $row["date"] . '</td>';
                                    echo '<td><button style="background-color: white;" type="submit" name="delete" value="' . $row["id"] . '">Delete</button></td>';
                                    echo '</tr>';
                                    echo '</form>';
                                }
                                //mysqli_close($conn);
                                    ?>
                        </tbody>
                      </table>
                      <nav aria-label="...">
                          <center>
                              <ul class="pagination pagination-sm">
                                        <?php
                                        for($i=0;$i<($count2/$pageNumber);$i++){
                                            echo '<li class="page-item"><a class="page-link" href="'.current_file().'?page2='.($i+1).'">'.($i+1).'</a></li>';
                                        }
                                        ?>
                              </ul>
                          </center>
                      </nav>
                    </div>
                </div>

              </div>
              <!-- /.container-fluid -->
          </div>
          <!-- /#page-wrapper -->
      </div>
      <!-- /#wrapper -->
    <?php require "../inc/footer.php";
    ?>


</body>
</html>
