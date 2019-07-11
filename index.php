<?php
// Decoment to see //

include 'db.php';
require 'vendor/autoload.php';

use \Firebase\JWT\JWT;
use PHPMailer\PHPMailer\PHPMailer;


\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->post('/patient','addPatient');
$app->post('/medecin','addMedecin');
$app->get('/patient/:email/:password','getPatientConnexion');  
$app->get('/medecin/:email/:password','getMedecinConnexion');
$app->get('/activite','getActivite');
$app->get('/patients/ko','getPatientsKO');  
$app->get('/patients','getPatients');  
$app->get('/medecins','getMedecins');
$app->get('/ActivitePatient/:id_patient','getActivitePatient');
$app->post('/activite','addActivite');




//
header('Access-Control-Allow-Origin: *');  

function getActivite(){
    $sql = "SELECT * FROM activite";
    try{

        $db=getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $activite = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db=null;
        echo json_encode($activite);
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}
function getPatients(){
    $sql = "SELECT * FROM patient";
    try{

        $db=getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $activite = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db=null;
        echo json_encode($activite);
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}

function getPatientsKO(){
?>
    <script src="https://smtpjs.com/v3/smtp.js">
        Email.send({
    Host : "smtp.gmail.com",
    Username : "magenelec@gmail.com",
    Password : "Elmadisene94",
    To : 'magenelec@gmail.com',
    From : "magenelec@gmail.com",
    Subject : "This is the subject",
    Body : "And this is the body"
}).then(
  message => alert(message)
);
</script>
<?php
    $sql = "SELECT * FROM patient ";
    try{

        $db=getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $activite = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db=null;
        echo json_encode($activite);
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}

function getPatientConnexion($email,$password){
    $sql = "SELECT * FROM patient WHERE email =:email And password=:password";
    try{

        //$insert = json_decode($request->getBody());
        $db=getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue("email", $email);
        $stmt->bindValue("password", $password);
        $stmt->execute();
        $patient = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db=null;
        echo json_encode($patient);
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}

function getMedecinConnexion($email,$password){
    $sql = "SELECT * FROM medecin WHERE email =:email And password=:password";
    try{
        $db=getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue("email", $email);
        $stmt->bindValue("password", $password);
        $stmt->execute();
        $medecin = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db=null;
        echo json_encode($medecin);
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}

function addPatient() {

    $request = \Slim\Slim::getInstance()->request();
    $insert = json_decode($request->getBody());

    $sql = "INSERT INTO patient (nom,prenom,email,password,numero_telephone) VALUES (:nom, :prenom ,:email,:password,:numero_telephone)";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("nom", $insert->nom);
        $stmt->bindParam("prenom", $insert->prenom);
        $stmt->bindParam("email", $insert->email);
        $stmt->bindParam("password", $insert->password);
        $stmt->bindParam("numero_telephone", $insert->numero_telephone);

        $status = $stmt->execute();
        $db = null;
        echo '{"status":'.$status.',"message":"Ajout réussi"}';
    } catch(PDOException $e) {
        echo '{"status": "0","message":"Echec ajout  : "'.$e->getMessage().'}';
    }
}

function addMedecin() {

    $request = \Slim\Slim::getInstance()->request();
    $insert = json_decode($request->getBody());

    $sql = "INSERT INTO medecin (nom,prenom,email,password,fontion,matricule) VALUES (:nom, :prenom ,:email,:password,:fontion,:matricule)";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("nom", $insert->nom);
        $stmt->bindParam("prenom", $insert->prenom);
        $stmt->bindParam("email", $insert->email);
        $stmt->bindParam("password", $insert->password);
        $stmt->bindParam("fontion", $insert->fontion);
        $stmt->bindParam("matricule", $insert->matricule);



        $status = $stmt->execute();
        $db = null;
        echo '{"status":'.$status.',"message":"Ajout réussi"}';
    } catch(PDOException $e) {
        echo '{"status": "0","message":"Echec ajout  : "'.$e->getMessage().'}';
    }
}
function getMedecins() {
    $sql = "SELECT * FROM medecin";
    try{
    $db=getDB();
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $medecins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $db=null;
    echo json_encode($medecins);
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}


function getActivitePatient($id_patient){
    $sql = "SELECT * FROM effectuer_activite,patient,activite WHERE  effectuer_activite.id_patient=patient.id  and activite.id=effectuer_activite.id_activite";
    try{

       // $insert = json_decode($request->getBody());
        $db=getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue("id_patient", $id_patient);

        $stmt->execute();
        $patient = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db=null;
        echo json_encode($patient);
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}


function addActivite() {

     $request = \Slim\Slim::getInstance()->request();
    $insert = json_decode($request->getBody());


    $sql = "INSERT INTO effectuer_activite (id_patient, id_activite, duree_activite,jours,position) VALUES (:id_patient, :id_activite, :duree_activite,:jours,:position)";
    try{

        $db=getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id_patient",$insert->id_patient);
        $stmt->bindParam("id_activite",$insert->id_activite);
        $stmt->bindParam("duree_activite",$insert->duree_activite);
                $stmt->bindParam("jours",$insert->jours);
        $stmt->bindParam("position",$insert->position);


        $status = $stmt->execute();
        $db = null;
        echo '{"status":'.$status.',"message":"Ajout réussi"}';
    } 
    catch(PDOException $e) {
        echo '{"status": "0","message":"Echec ajout  : "'.$e->getMessage().'}';
    }
}

$app->run();

?>
