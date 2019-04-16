<?php
include 'scalp_db.php';

$connection = connectDatabase();

$addedNewPerson = false;
$updatedPerson = false;
$deletedPerson = false;
if (isset($_POST['new'])) {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $cellphone = mysqli_real_escape_string($connection, $_POST['cellphone']);
    $createQuery = sprintf("INSERT INTO People(name, address, email, cellphone) VALUES ('%s', '%s', '%s', '%s')",
        $name,
        $address,
        $email,
        $cellphone
    );
    mysqli_query($connection, $createQuery) or die(mysqli_error($connection));
    $addedNewPerson = true;
}
else if(isset($_POST['update'])) {
    $uid = mysqli_real_escape_string($connection, $_POST['id']);
    $uname = mysqli_real_escape_string($connection, $_POST['name']);
    $uaddress = mysqli_real_escape_string($connection, $_POST['address']);
    $uemail = mysqli_real_escape_string($connection, $_POST['email']);
    $ucellphone = mysqli_real_escape_string($connection, $_POST['cellphone']);
    $updateQuery = sprintf("UPDATE People SET name='%s', address='%s', email='%s', cellphone='%s' WHERE id='%s'",
        $uname,
        $uaddress,
        $uemail,
        $ucellphone,
        $uid
    );
    mysqli_query($connection, $updateQuery) or die(mysqli_error($connection));
    $updatedPerson = true;
}
else if(isset($_POST['delete'])) {
    $deleteQueryMxdProjects = sprintf("DELETE FROM ProjectsContributors WHERE People_id='%s'", 
    mysqli_real_escape_string($connection, $_POST['id']));
    $deleteQueryPeople = sprintf("DELETE FROM People WHERE id='%s'", 
    mysqli_real_escape_string($connection, $_POST['id']));
    mysqli_query($connection, $deleteQueryMxdProjects) or die(mysqli_error($connection));
    mysqli_query($connection, $deleteQueryPeople) or die(mysqli_error($connection));
    $deletedPerson = true;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Scalp - People</title>
        <link rel="stylesheet" href="styles.css" />
        <link rel="icon" href="pics/favicon.png" />
        <meta charset="utf-8" />
    </head>
    <body>
        <?php include 'menu.html'; ?>

        <div class="people-content">
            <header class="title">
                <h1>People</h1>
            </header>
            <div class="message-box">
                <?php if($addedNewPerson):?><div class="new-box">New person added.</div><?php endif; ?>
                <?php if($updatedPerson):?><div class="modified-box">The data of the person modified.</div><?php endif; ?>
                <?php if($deletedPerson):?><div class="deleted-box">Person removed from database.</div><?php endif; ?>
            </div>
            <div>
                <?php
                    if(isset($_GET['personid'])){
                        $querySelect = sprintf("SELECT id, name, address, email, cellphone FROM People WHERE id='%s'",
                        mysqli_real_escape_string($connection, $_GET['personid']));
                    }
                    else {
                        $querySelect = "SELECT id, name, address, email, cellphone FROM People ORDER BY name";
                    }
                    $result = mysqli_query($connection, $querySelect) or die(mysqli_error($connection));
                ?>
                <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>      
                        <th>Email</th>      
                        <th>Cellphone</th>
                        <th></th>
                    </tr> 
                </thead>
                <tbody>
                <?php if(!mysqli_num_rows($result)): ?>
                    <tr><td colspan="5">Table is empty/There is no such person.</td></tr>
                <?php endif; ?>
                <?php while ($row = mysqli_fetch_array($result)): ?>
                    <tr>
                        <td><?=$row['name']?></td>
                        <td><?=$row['address']?></td>
                        <td><?=$row['email']?></td>
                        <td><?=$row['cellphone']?></td>
                        <td>
                            <a class="" href="people.php?personid=<?=$row['id']?>">
                                <i class="material-icons">edit</i>
                            </a>
                        </td> 
                    </tr>                
                <?php endwhile; ?> 
                </tbody>
                </table>

                <!-- Create New -->
                <?php if(!isset($_GET['personid'])): ?>
                <div class="form-container">
                    <form method="post" action="">
                        <h2>New person</h2>
                            <div class="form-field">
                                <label for="name">Name:</label>
                                <input required class="form-control" name="name" id="name" type="text" />
                            </div>
                            <div class="form-field">
                                <label for="address">Address:</label>
                                <input required class="form-control" name="address" id="address" type="text"  />
                            </div>
                            <div class="form-field">
                                <label for="email">Email:</label>
                                <input required class="form-control" name="email" id="email" type="email" />
                            </div>
                            <div class="form-field">
                                <label for="cellphone">Cellphone:</label>
                                <input required class="form-control" name="cellphone" id="cellphone" type="text"  />
                            </div>
                            <input class="form-submit" name="new" type="submit" value="Create" />
                    </form>
                </div>
                <?php endif; ?>

                <!-- Modify, Delete -->
                <?php if(isset($_GET['personid'])): ?>
                <?php 
                    $result = mysqli_query($connection, $querySelect) or die(mysqli_error($connection));
                    $mdrow = mysqli_fetch_array($result)
                ?>
                <div class="form-container">
                    <form method="post" action="">
                        <h2>Modify/Delete person</h2>
                        <input type="hidden" name="id" id="mdid" value="<?=$mdrow['id']?>" />
                        <div class="form-field">
                            <label for="mdname">Name:</label>
                            <input required name="name" id="mdname" type="text" value="<?=$mdrow['name']?>" />
                        </div>
                        <div class="form-field">
                            <label for="mdaddress">Address:</label>
                            <input name="address" id="mdaddress" type="text" value="<?=$mdrow['address']?>" />
                        </div>
                        <div class="form-field">
                            <label for="mdemail">Email:</label>
                            <input name="email" id="mdemail" type="email" value="<?=$mdrow['email']?>"/>
                        </div>
                        <div class="form-field">
                            <label for="mdcellphone">Cellphone:</label>
                            <input name="cellphone" id="mdcellphone" type="text" value="<?=$mdrow['cellphone']?>"/>
                        </div>
                        <input class="form-submit" name="update" type="submit" value="Save" />
                        <input class="form-submit" name="delete" type="submit" value="Delete" />
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php include 'footer.html'; ?>
        <?php disconnectDatabase($connection)?>
    </body>
</html>