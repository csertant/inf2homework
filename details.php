<?php
include 'scalp_db.php';

$connection = connectDatabase();

if(isset($_POST['newCompany'])){
    $projectId = mysqli_real_escape_string($connection, $_POST['projectId']);
    $companyId = mysqli_real_escape_string($connection, $_POST['companyId']);
    $createQuery = sprintf("INSERT INTO CompaniesProjects(Company_id, Project_id) VALUES ('%s', '%s')",
        $companyId,
        $projectId
    );
    mysqli_query($connection, $createQuery) or die(mysqli_error($connection));
}
else if(isset($_POST['deleteCompany'])) {
    $companyId = mysqli_real_escape_string($connection, $_POST['companyId']);
    $projectId = mysqli_real_escape_string($connection, $_POST['projectId']);
    $deleteQuery = sprintf("DELETE FROM CompaniesProjects WHERE Company_id='%s' AND Project_id='%s'", 
        $companyId,
        $projectId
    );
    mysqli_query($connection, $deleteQuery) or die(mysqli_error($connection));
}
else if(isset($_POST['newPeople'])){
    $peopleId = mysqli_real_escape_string($connection, $_POST['peopleId']);
    $projectId = mysqli_real_escape_string($connection, $_POST['projectId']);
    $createQuery = sprintf("INSERT INTO ProjectsContributors(Project_id, People_id) VALUES ('%s', '%s')",
        $projectId,
        $peopleId
    );
    mysqli_query($connection, $createQuery) or die(mysqli_error($connection));
}
else if(isset($_POST['deletePeople'])) {
    $peopleId = mysqli_real_escape_string($connection, $_POST['peopleId']);
    $projectId = mysqli_real_escape_string($connection, $_POST['projectId']);
    $deleteQuery = sprintf("DELETE FROM ProjectsContributors WHERE Project_id='%s' AND People_id='%s'", 
        $projectId,
        $peopleId
    );
    mysqli_query($connection, $deleteQuery) or die(mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Scalp - Projects</title>
        <link rel="stylesheet" href="styles.css" />
        <link rel="icon" href="pics/favicon.png" />
        <meta charset="utf-8" />
    </head>
    <body>
        <?php include 'menu.html'; ?>

        <div class="project-details-content">
            <header class="title">
                <h1>Project details</h1>
            </header>
                <?php
                    $querySelect = sprintf("SELECT id, name, description, startDate FROM Project WHERE id='%s'",
                    mysqli_real_escape_string($connection, $_GET['projectid']));
                    $result = mysqli_query($connection, $querySelect) or die(mysqli_error($connection));
                    $row = mysqli_fetch_array($result);

                    $querySelectCompany = sprintf("SELECT Company.name name, Project_id, Company_id FROM Project 
                    LEFT OUTER JOIN CompaniesProjects ON Project.id=CompaniesProjects.Project_id 
                    LEFT OUTER JOIN Company ON CompaniesProjects.Company_id=Company.id WHERE Project.id='%s'",
                    mysqli_real_escape_string($connection, $_GET['projectid']));
                    $resultCompany = mysqli_query($connection, $querySelectCompany) or die(mysqli_error($connection));
                    
                    $querySelectPeople = sprintf("SELECT People.name name, Project_id, People_id FROM Project 
                    LEFT OUTER JOIN ProjectsContributors ON Project.id=ProjectsContributors.Project_id 
                    LEFT OUTER JOIN People ON ProjectsContributors.People_id=People.id WHERE Project.id='%s'",
                    mysqli_real_escape_string($connection, $_GET['projectid']));
                    $resultPeople = mysqli_query($connection, $querySelectPeople) or die(mysqli_error($connection));

                ?>
                <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>      
                        <th>Project started</th>      
                        <th></th>
                    </tr> 
                </thead>
                <tbody>
                    <?php if(!mysqli_num_rows($result)): ?>
                        <tr><td colspan="4">Table is empty.</td></tr>
                    <?php endif; ?>
                    <tr>
                        <td><?=$row['name']?></td>
                        <td><?=$row['description']?></td>
                        <td><?=$row['startDate']?></td>
                        <td>
                            <a href="projects.php?projectid=<?=$row['id']?>">
                                <i class="material-icons">edit</i>
                            </a>
                        </td> 
                    </tr>
                    <tr>
                        <td colspan="4">
                        <?php if(!mysqli_num_rows($resultCompany)): ?>
                            <span>This project has no companies added.</span>
                        <?php else:
                            while($rowC = mysqli_fetch_array($resultCompany)): ?>
                            <span><?=$rowC['name']?><a title="You can add/delete a Startup from the project by using the forms below." href="javascript:alert('You can add/delete a Startup from the project by using the forms below.');">
                            <i class="material-icons">edit</i></a>,</span>
                        <?php endwhile; ?>
                         run(s) this project.</td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <td colspan="4">Contributors: 
                        <?php if(!mysqli_num_rows($resultCompany)): ?>
                            <span>This project has no contributors added.</span>
                        <?php else:
                            while($rowP = mysqli_fetch_array($resultPeople)): ?>
                            <span><?=$rowP['name']?><a title="You can add/delete People from the project by using the forms below." href="javascript:alert('You can add/delete People from the project by using the forms below.');">
                            <i class="material-icons">edit</i></a>,</span>
                        <?php endwhile; endif; ?>
                        </td>
                    </tr>
                </tbody>
                </table>
                
                <div class="twocolumns">
                <!-- Add new Company -->
                <div class="form-container">
                    <form action="" method="post">
                        <h2>Add Company to the project</h2>
                        <div class="form-field">
                            <label for="projectId">Project:</label>
                            <input readonly name="projectId" id="projectId" type="text" value="<?=$row['id']?>" />
                        </div>
                        <div class="form-field">
                            <label for="companyId">Startup:</label>
                            <select name="companyId" id="companyId">
                                <?php
                                    $queryOptions = "SELECT name, id FROM Company";
                                    $companyOptions = mysqli_query($connection, $queryOptions) or die(mysqli_error($connection));
                                    while($resultCompanyOptions = mysqli_fetch_array($companyOptions)):
                                ?>
                                    <option value="<?=$resultCompanyOptions['id']?>"><?=$resultCompanyOptions['name']?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <input class="form-submit" name="newCompany" type="submit" value="Add" />
                    </form>
                </div>
                <!-- Delete Company -->
                <div class="form-container">
                    <form action="" method="post">
                        <h2>Delete Company from the project</h2>
                        <div class="form-field">
                            <label for="projectId">Project:</label>
                            <input readonly name="projectId" id="projectId" type="text" value="<?=$row['id']?>" />
                        </div>
                        <div class="form-field">
                            <label for="companyId">Startup:</label>
                            <select name="companyId" id="companyId">
                                <?php
                                    $queryOptions = "SELECT name, id FROM Company";
                                    $companyOptions = mysqli_query($connection, $queryOptions) or die(mysqli_error($connection));
                                    while($resultCompanyOptions = mysqli_fetch_array($companyOptions)):
                                ?>
                                    <option value="<?=$resultCompanyOptions['id']?>"><?=$resultCompanyOptions['name']?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <input class="form-submit" name="deleteCompany" type="submit" value="Delete" />
                    </form>
                </div>
                </div>

                <div class="twocolumns">
                <!-- Add new Contributor -->
                <div class="form-container">
                    <form action="" method="post">
                        <h2>Add Contributor to the project</h2>
                        <div class="form-field">
                            <label for="projectId">Project:</label>
                            <input readonly name="projectId" id="projectId" type="text" value="<?=$row['id']?>" />
                        </div>
                        <div class="form-field">
                            <label for="peopleId">Person:</label>
                            <select name="peopleId" id="peopleId">
                                <?php
                                    $queryOptions = "SELECT name, id FROM People";
                                    $peopleOptions = mysqli_query($connection, $queryOptions) or die(mysqli_error($connection));
                                    while($resultPeopleOptions = mysqli_fetch_array($peopleOptions)):
                                ?>
                                    <option value="<?=$resultPeopleOptions['id']?>"><?=$resultPeopleOptions['name']?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <input class="form-submit" name="newPeople" type="submit" value="Add" />
                    </form>
                </div>
                <!-- Delete Contributor -->
                <div class="form-container">
                    <form action="" method="post">
                        <h2>Delete Contributor from the project</h2>
                        <div class="form-field">
                            <label for="projectId">Project:</label>
                            <input readonly name="projectId" id="projectId" type="text" value="<?=$row['id']?>" />
                        </div>
                        <div class="form-field">
                            <label for="peopleId">Person:</label>
                            <select name="peopleId" id="peopleId">
                                <?php
                                    $queryOptions = "SELECT name, id FROM People";
                                    $peopleOptions = mysqli_query($connection, $queryOptions) or die(mysqli_error($connection));
                                    while($resultPeopleOptions = mysqli_fetch_array($peopleOptions)):
                                ?>
                                    <option value="<?=$resultPeopleOptions['id']?>"><?=$resultPeopleOptions['name']?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <input class="form-submit" name="deletePeople" type="submit" value="Delete" />
                    </form>
                </div>
                </div>
        </div>

        <?php include 'footer.html'; ?>
        <?php disconnectDatabase($connection)?>
    </body>
</html>