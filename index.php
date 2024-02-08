<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Link css style-->
    <link rel="stylesheet" href="css\style.css">
 
    <title>conncet_db</title>
    <?php

        //creo connessione con DB

        define("DB_SERVERNAME","localhost");
        define("DB_USERNAME","root");
        define("DB_PASSWORD","root");
        define("DB_NAME","db_university");


        //connect to database
        $con = new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);

        //controllo connessione
        if ($conn && $con->connect_error) {
            echo "Connessione fallita: " . $conn->connect_error;
        }
    ?>

</head>


<body>

<ul>
    <h1>Interrogazioni al db</h1>

    <br><br>
    <!-- Campo form -->
    <form>
        <select name="value">
            <?php
                    for($i=0; $i<5; $i++){
            ?>
                <option value="<?php echo $i; ?>">
                        <?php echo $i; ?>
                </option>
            <?php
                }
            ?>
        </select>
        <input type="submit" value="FILTER">
    </form>

    <br><br>

    <?php 

        $val=$_GET["value"];

        switch($val){

            case 0:

                echo "<h2>Selezionare tutti i corsi del primo semestre del primo anno di un qualsiasi corso di laurea</h2>";

                //preparo la mia interrogazione al Db
                $sql = "SELECT name 'nome_corso' FROM `courses` WHERE period ='I semestre' and year = 1;";
                
                break;
            case 1:
                echo "<h2>Da quanti dipartimenti è composta l'università?</h2>";

                //preparo la mia interrogazione al Db
                $sql = "SELECT COUNT(*) 'num_dip'  FROM departments;";

                break;
            case 2:
                echo "<h2>Selezionare tutti gli studenti iscritti al Corso di Laurea in Economia</h2>";

                //preparo la mia interrogazione al Db
                $sql = "SELECT DISTINCT students.name,students.surname FROM degrees JOIN students ON degrees.id = students.degree_id WHERE degrees.name LIKE 'Corso di Laurea in Economia';";

                break;
            case 3:
                echo "<h2>Selezionare tutti i docenti che insegnano nel Dipartimento di Matematica: nome e cognome</h2>";

                //preparo la mia interrogazione al Db
                $sql = "SELECT DISTINCT teachers.name 'nome_doc',teachers.surname 'cognome_doc' FROM degrees JOIN courses ON degrees.id = courses.degree_id JOIN course_teacher ON courses.id = course_teacher.course_id JOIN teachers ON course_teacher.teacher_id = teachers.id JOIN departments ON degrees.department_id = departments.id WHERE departments.name LIKE 'Dipartimento di Matematica';";

                break;
            case 4:
                echo "<h2>Calcolare la media dei voti di ogni appello d'esame (dell'esame vogliamo solo l'`id`)</h2>";

                //preparo la mia interrogazione al Db
                $sql = "SELECT FLOOR(AVG(vote)) 'media', exam_id FROM `exam_student` GROUP BY exam_id;";

                break;

        }
    ?>
    
    <li>
        <?php
         
            //Acquisisco il risultato della mia interrogazione
            $result= $con->query($sql);

            if($result && $result->num_rows > 0) {

                while($row = $result->fetch_assoc()) {

                    if($row["nome_corso"] != "")echo"<br>"."Nome: ". $row["nome_corso"] ."<br>";

                    if($row["num_dip"] != "") echo"<br>"."Numero Dipartimenti: ". $row["num_dip"] ."<br>";

                    if($row["name"] != "" && $row["surname"]) echo"<br>"."<b>Nome</b>: ". $row["name"] ." - <b>Cognome</b>: ". $row["surname"]."<br>";

                    if($row["nome_doc"] != "" && $row["cognome_doc"]) echo"<br>"."<b>Nome</b>: ". $row["nome_doc"] ." - <b>Cognome</b>: ". $row["cognome_doc"]."<br>";

                    if($row["media"] != "") echo"<br>"."<b>Id esame</b>: ". $row["exam_id"] ." - <b>Media</b>: ". $row["media"]."<br>";
                }
            }elseif($result){
                echo "0 results";
            }else{
                echo "query error";
            }

            $con ->close();

            echo "The end";

        ?>
    </li>
</ul>



    
</body>
</html>