<?php
include 'scalp_db.php';

$connection = connectDatabase();

$addedNewCompany = false;
if (isset($_POST['new'])) {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);
    $ceo = mysqli_real_escape_string($connection, $_POST['ceo']);
    $createQuery = sprintf("INSERT INTO Company(name, address, ceo) VALUES ('%s', '%s', '%s')",
        $name,
        $address,
        $ceo
    );
    mysqli_query($connection, $createQuery) or die(mysqli_error($connection));
    $addedNewCompany = true;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Scalp - Companies</title>
        <link rel="stylesheet" href="styles.css" />
        <link rel="icon" href="pics/favicon.png" />
        <meta charset="utf-8" />
    </head>
    <body>
        <?php include 'menu.html'; ?>

        <div class="companies-content">
            <header class="title">
                <h1>Companies</h1>
            </header>
            <div>
                <?php
                    $querySelect = "SELECT id, name, address, ceo FROM Company ORDER BY name";
                    $result = mysqli_query($connection, $querySelect) or die(mysqli_error($connection));
                ?>
                <table class="">
                <thead class="">
                    <tr>
                        <th>Name</th>
                        <th>Address</th>      
                        <th>Ceo</th>      
                        <th></th>
                    </tr> 
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_array($result)): ?>
                    <tr>
                        <td><?=$row['name']?></td>
                        <td><?=$row['address']?></td>
                        <td><?=$row['ceo']?></td>
                        <td>
                            <a class="" href="editCompany.php?companyid=<?=$row['id']?>">
                                <i class=""></i>
                            </a>
                        </td> 
                    </tr>                
                <?php endwhile; ?> 
                </tbody>
                </table>

                <form method="post" action="">
                    <div class="form-container">
                        <h2>New company</h2>
                            <div class="form-field">
                                <label for="name">Name</label>
                                <input required class="form-control" name="name" id="name" type="text" />
                            </div>
                            <div class="form-field">
                                <label for="address">Address</label>
                                <input required class="form-control" name="address" id="address" type="text"  />
                            </div>
                            <div class="form-field">
                                <label for="ceo">Ceo</label>
                                <!-- Select existing people-->
                                
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