<?php


if($_POST){
    $newWithId = new WithId();
    $newWithId->entry_string = $_POST['entry_string'];
    $newWithId->entry_int = $_POST['entry_int'];

    try{
        $newWithId->save();
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }

    //Forward back to page to remove POST data
    //Header("Location: /demo2/");
}

//Check if we are missing a required variable
if(isset($_GET['by']) && !isset($_GET['value']) || isset($_GET['value']) && !isset($_GET['by'])){
    Header("Location: /demo2/");
}

//Querying data
$WithId = new WithId();
if(isset($_GET['by']) && isset($_GET['value'])){
    $withIds = $WithId->get(array($_GET['by'] => $_GET['value']));
}else{
    $withIds = $WithId->get();
}

?>
<!doctype html>

<html lang="en">
    <head>
        <meta charset="utf-8">

        <title>PHP MYSQL CRUD HELPER - Auto Incremented ID's Demo</title>
        <meta name="description" content="">
        <meta name="author" content="">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="/assets/plugins/bootstrap/bootstrap.css">
        <link rel="stylesheet" href="/assets/style.css">
    </head>
    <body>
        
        <div class="container">
            <div class="col-sm-12">
                <h1>Auto Incremented ID Demo</h1>
                
                <p>
                    <b>You can add a new entry here.</b>
                </p>
                <p>
                    <form action="" method="POST">
                        <label>Entry String</label>
                        <input type="text" name="entry_string"><br />
                        <label>Entry Int</label>
                        <input type="number" name="entry_int" min="0" max="10" step="1"><br /><br />
                        <input type="submit" value="Submit">
                    </form>
                </p>
            </div>
            
            <div class="col-sm-12">
                <h2>Database: </h2>
                <form class="form-inline" action="" method="GET">
                    <div class="form-group mb-2">
                        <label for="by" class="sr-only">Search By</label>
                        <select class="form-control" name="by" id="by">
                            <option value="id" <?php if(isset($_GET['by']) && $_GET['by'] == "id"){ echo "selected";}elseif(!isset($_GET['by'])){echo "selected";} ?>>ID</option>
                            <option value="entry_string" <?php if(isset($_GET['by']) && $_GET['by'] == "entry_string"){ echo "selected";} ?>>Entry String</option>
                            <option value="entry_int" <?php if(isset($_GET['by']) && $_GET['by'] == "entry_int"){ echo "selected";} ?>>Entry Int</option>
                        </select>
                    </div>
                    <div class="form-group mx-sm-3 mb-2">
                        <label for="inputPassword2" class="sr-only">Value</label>
                        <input type="text" class="form-control" name="value" id="value" value="<?php if(isset($_GET['value'])){ echo $_GET['value'];} ?>">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Search</button>
                    <a href="/demo2/" class="btn btn-primary mb-2 mx-sm-3" style="margin-right: 10px;">Clear</a>
                </form>
                <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">ID</th>
                        <th scope="col">String</th>
                        <th scope="col">Int</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        if($withIds){
                            foreach($withIds as $withid){
                                echo '<tr>';
                                    echo '<td scope="row">' . $withid->id . '</td>';
                                    echo '<td>' . $withid->entry_string . '</td>';
                                    echo '<td>' . $withid->entry_int . '</td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>

            </div>
        </div>

        <script src="/assets/plugins/jquery/jquery-3.3.0.min.js"></script>
        <script src="/assets/plugins/bootstrap/bootstrap.js"></script>
        <script src="/assets/scripts.js"></script>
    </body>
</html>
