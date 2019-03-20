<?php
include 'scalp_db.php';

$connection = connectDatabase();

$addedNewCompany = false;
$updatedCompany = false;
$deletedCompany = false;
if (isset($_POST['new'])) {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);
    $category = mysqli_real_escape_string($connection, $_POST['category']);
    $createQuery = sprintf("INSERT INTO Company(name, address, category) VALUES ('%s', '%s', '%s')",
        $name,
        $address,
        $category
    );
    mysqli_query($connection, $createQuery) or die(mysqli_error($connection));
    $addedNewCompany = true;
}
else if(isset($_POST['update'])) {
    $uid = mysqli_real_escape_string($connection, $_POST['id']);
    $uname = mysqli_real_escape_string($connection, $_POST['name']);
    $uaddress = mysqli_real_escape_string($connection, $_POST['address']);
    $ucategory = mysqli_real_escape_string($connection, $_POST['category']);
    $updateQuery = sprintf("UPDATE Company SET name='%s', address='%s', category='%s' WHERE id='%s'",
        $uname,
        $uaddress,
        $ucategory,
        $uid
    );
    mysqli_query($connection, $updateQuery) or die(mysqli_error($connection));
    $updatedCompany = true;
}
else if(isset($_POST['delete'])) {
    $deleteQueryMxdProjects = sprintf("DELETE FROM CompaniesProjects WHERE Company_id='%s'", 
    mysqli_real_escape_string($connection, $_POST['id']));
    $deleteQueryCompanies = sprintf("DELETE FROM Company WHERE id='%s'", 
    mysqli_real_escape_string($connection, $_POST['id']));
    mysqli_query($connection, $deleteQueryMxdProjects) or die(mysqli_error($connection));
    mysqli_query($connection, $deleteQueryCompanies) or die(mysqli_error($connection));
    $deletedCompany = true;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Scalp - Startups</title>
        <link rel="stylesheet" href="styles.css" />
        <link rel="icon" href="pics/favicon.png" />
        <meta charset="utf-8" />
    </head>
    <body>
        <?php include 'menu.html'; ?>

        <div class="companies-content">
            <header class="title">
                <h1>Startups</h1>
            </header>
            <div>
                <?php
                    if(isset($_GET['companyid'])) {
                        $querySelect = sprintf("SELECT id, name, address, category FROM Company WHERE id='%s'",
                        mysqli_real_escape_string($connection, $_GET['companyid']));
                    }
                    else {
                        $querySelect = "SELECT id, name, address, category FROM Company ORDER BY name";
                    }
                    $result = mysqli_query($connection, $querySelect) or die(mysqli_error($connection));
                ?>
                <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>      
                        <th>Category</th>      
                        <th></th>
                    </tr> 
                </thead>
                <tbody>
                <?php if(!mysqli_num_rows($result)): ?>
                    <tr><td colspan="4">Table is empty.</td></tr>
                <?php endif; ?>
                <?php while ($row = mysqli_fetch_array($result)): ?>
                    <tr>
                        <td><?=$row['name']?></td>
                        <td><?=$row['address']?></td>
                        <td><?=$row['category']?></td>
                        <td>
                            <a class="" href="companies.php?companyid=<?=$row['id']?>">
                                <i class="material-icons">edit</i>
                            </a>
                        </td> 
                    </tr>                
                <?php endwhile; ?> 
                </tbody>
                </table>

                <!-- Create New -->
                <?php if(!isset($_GET['companyid'])): ?>
                <form method="post" action="">
                    <div class="form-container">
                        <h2>New company</h2>
                            <div class="form-field">
                                <label for="name">Name:</label>
                                <input required class="form-control" name="name" id="name" type="text" />
                            </div>
                            <div class="form-field">
                                <label for="address">Address:</label>
                                <input required class="form-control" name="address" id="address" type="text"  />
                            </div>
                            <div class="form-field">
                                <label for="cateegory">Category:</label>
                                <input required class="form-control" name="category" id="category" type="text"  />
                            </div>
                            <input class="form-submit" name="new" type="submit" value="Create" />
                    </div>
                </form>
                <?php endif; ?>

                <!-- Modify, Delete -->
                <?php if(isset($_GET['companyid'])): ?>
                <?php 
                    $result = mysqli_query($connection, $querySelect) or die(mysqli_error($connection));
                    $mdrow = mysqli_fetch_array($result)
                ?>
                <form method="post" action="">
                    <div class="form-container">
                        <h2>Modify/Delete startup</h2>
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
                            <label for="mdcategory">Category:</label>
                            <input name="category" id="mdcategory" type="text" value="<?=$mdrow['category']?>" />
                        </div>
                        <input class="form-submit" name="update" type="submit" value="Save" />
                        <input class="form-submit" name="delete" type="submit" value="Delete" />
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>

        <?php include 'footer.html'; ?>
        <?php disconnectDatabase($connection)?>
    </body>
</html>