<?php
include 'scalp_db.php';

$connection = connectDatabase();

$addedNewPerson = false;
if (isset($_POST['new'])) {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $workplace = mysqli_real_escape_string($connection, $_POST['workplace']);
    $createQuery = sprintf("INSERT INTO People(name, address, email, workplace) VALUES ('%s', '%s', '%s', '%s')",
        $name,
        $address,
        $email,
        $workplace
    );
    mysqli_query($connection, $createQuery) or die(mysqli_error($connection));
    $addedNewPerson = true;
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
            <div>
                <?php
                    $querySelect = "SELECT id, name, address, email, workplace FROM People ORDER BY name";
                    $result = mysqli_query($connection, $querySelect) or die(mysqli_error($connection));
                ?>
                <table class="">
                <thead class="">
                    <tr>
                        <th>Name</th>
                        <th>Address</th>      
                        <th>Email</th>      
                        <th>Workplace</th>
                        <th></th>
                    </tr> 
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_array($result)): ?>
                    <tr>
                        <td><?=$row['name']?></td>
                        <td><?=$row['address']?></td>
                        <td><?=$row['email']?></td>
                        <td><?=$row['workplace']?></td>
                        <td>
                            <a class="" href="editPeople.php?personid=<?=$row['id']?>">
                                <i class=""></i>
                            </a>
                        </td> 
                    </tr>                
                <?php endwhile; ?> 
                </tbody>
                </table>

                <form method="post" action="">
                    <div class="form-container">
                        <h2>New person</h2>
                            <div class="form-field">
                                <label for="name">Name</label>
                                <input required class="form-control" name="name" id="name" type="text" />
                            </div>
                            <div class="form-field">
                                <label for="address">Address</label>
                                <input required class="form-control" name="address" id="address" type="text"  />
                            </div>
                            <div class="form-field">
                                <label for="email">Email</label>
                                <input required class="form-control" name="email" id="email" type="email" />
                            </div>
                            <div class="form-field">
                                <label for="workplace">Workplace</label>
                                <!-- Select existing firms-->
                                
                            </div>
                            <input class="form-submit" name="new" type="submit" value="Create" />
                    </div>
                </form>
            </div>
        </div>

        <?php include 'footer.html'; ?>
        <?php disconnectDatabase($connection)?>
    </body>
</html>