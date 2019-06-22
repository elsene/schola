<?php
// Decoment to see //

include 'db.php';
include 'BDD.php';
require 'vendor/autoload.php';

use \Firebase\JWT\JWT;

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();



//Users 
$app->get('/sorties','getSorties');
$app->get('/themes','getThemes');
$app->get('/themes/:nom','getThemesId');
$app->get('/comment/:idsortie','getComment');
$app->get('/sortieNbPlace/:idsortie','getNbPlaces');
$app->get('/sortieNbCom/:idsortie','getNbCommentaire');

$app->get('/testUserSortie/:id_sortie/:id_user','testUserParticipeSortie');
$app->get('/sorties/:nom/:agemin/:agemax/:date_sortie/:theme/:budget/:portee','getSortiesSearch');

$app->get('/note/:id_sortie','getNoteRate');

$app->get('/login/:email/:mdp','connexion');  
$app->get('/acceuil', 'home');//
$app->get('/listeUser/:email', 'userFriend');
$app->get('/friendRequest/:email', 'userFriendRequest');
$app->get('/verifEmail/:email', 'emailVerif');
$app->post('/recoveryPassword/:email/:mdp', 'passwordRecovery');

$app->post('/sorties','addSorties');
$app->post('/note','addNote');
$app->post('/comment','addCommentaire');
$app->post('/participate','addParticipation');
$app->get('/users','getUsers');
$app->get('/amis/:id','getFriend');
$app->get('/users/:id','getUser');
$app->delete('/delete/:id','deleteUser');
$app->delete('/delAmi/:id','deleteAmi');
$app->post('/accepterAmi/:id','acceptUser');
$app->post('/friend','addFriend');
$app->get('/signaleruser/:id_user_signaler','getUserSignaler');
$app->get('/usersignaler/:id_user/:id_user_signaler','getSignalerUser');
$app->post('/signaler','addSignaler');
$app->get('/a/:nom/:prenom/:date_naissance/:date_inscription/:sexe/:filiere/:ville/:codepostal/:centreinteret/:nationalite/:langue','aa');

/********************************************/

$app->post('/users/add','addUser');
$app->post('/users/edit','editUser');
$app->post('/users/edit/img','editImage');
$app->post('/message/add','addMessage');
$app->get('/message/reception','getMessages');
$app->get('/message/conversation','getConversation');
$app->get('/sortie/creer','getSortiesCreer');
$app->get('/sortie/participer','getSortiesParticiper');
/********************************************/


$app->run();

/******************************************************************/
/**

     * @author  TEYEB Hazem


     * @return  ajoute un nouveau utilisateur dans la table user

     */
function addUser() {
    
    $request = \Slim\Slim::getInstance()->request();
    $insert= json_decode($request->getBody(),true);
    //var_dump($insert);
    $test=$request->getBody();
    //var_dump($test);
        
    try {
        
        $bdd=new BDD('test2','root','mysql','localhost');
        
        $status= $bdd->insert("(?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
        "user",
        array($insert["email"],$insert["nom"],$insert["prenom"],$insert["mdp"],$insert["date_naissance"],$insert["date_inscription"],$insert["sexe"],$insert["filiere"],$insert["photo"],$insert["ville"],$insert["codePostal"],$insert["centreInteret"],$insert["nationalite"],$insert["langue"]));
        
        $bdd = null;
        /*ini_set("SMTP","smtp.gmail.com" );
        ini_set('sendmail_from', 'teyeb.hazem@gmail.com'); 
        ini_set("smtp_port","465");
                mail($insert["email"],"Votre mot de passe",$insert["mdp"]);*/


        echo '{"status":'.$status.'}';
        

        
    } 
    
    catch(PDOException $e) {
        
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function editUser() {
    

    $request = \Slim\Slim::getInstance()->request();
    $update = json_decode($request->getBody(),true);
    
        try {
    
            $bdd= new BDD('test2','root','mysql','localhost');
        
            $status=$bdd->update("user","nom= ? , prenom= ? ,mdp= ?,filiere= ?,ville= ?,codePostal= ? ,centreInteret= ?,nationalite= ?,langue= ?","email=?",array($update["nom"],$update["prenom"],$update["mdp"],$update["filiere"],$update["ville"],$update["codePostal"],$update["centreInteret"],$update["nationalite"],$update["langue"],$update['email']));
        
            $db = null;

            echo '{"status":'.$status.'}';
        } 
    
        catch(PDOException $e) {
    
            echo '{"error":{"text":'. $e->getMessage() .'}}';

        }
    
}



function editImage() {
    

    $request = \Slim\Slim::getInstance()->request();
    $update = json_decode($request->getBody(),true);
    $image = $request->post();
    //$files = $request->UploadedFiles();
    //var_dump($_FILES);
    //var_dump($_FILES['bingo']['name']);
    //if(rename("wtf.png","test.png")){echo"nom changer***";}
    //var_dump($_FILES['bingo']['name']);
    //var_dump($image);
    $emplacement="img/".basename($_FILES["picture"]["name"]);
    
    if($_FILES["picture"]["type"]=="image/png"){
    
        $newName=("img/".$image["email"]."001.png");
    
    }
    else{
        
        $newName=("img/".$image["email"]."001.jpg");
    }
    //var_dump($emplacement);
    //var_dump($_FILES);
    if(isset($image)){
     if(move_uploaded_file($_FILES['picture']['tmp_name'],$emplacement)){
        
        if(rename($emplacement,$newName)){
        
            try {
    
                $bdd= new BDD('test2','root','mysql','localhost');
        
                $status=$bdd->update("user","photo= ? ","email= ?",array($newName,$image["email"]));
        
                $db = null;

                echo '{"status":'.$status.'}';
            } 
    
            catch(PDOException $e) {
    
                echo '{"error":{"text":'. $e->getMessage() .'}}';

            }
        
            
        }
        else{
            
            echo'{"error":{"rename":"erreur"}';
        }
        
      } 
     else{echo'{"error":{"deplacement":"erreur"}';
     }
    
    }
}


function addMessage(){
    
    $request = \Slim\Slim::getInstance()->request();
    
    $insert = json_decode($request->post(),true);
    //  var_dump($insert);
    try {
        
        $bdd=new BDD('test2','root','mysql','localhost');
        
        
        $status_1= $bdd->insert("(?,?,?,?,?,?)","message",array(NULL,$insert["message"],$insert["id_user_envoie"],$insert["vu"],date("Y-m-d H:i:s"),$insert["date_vu"]));
        
            
        $req= $bdd->select("*","message"," id_user_envoie = ? ORDER BY id DESC LIMIT 1 ",array($insert["id_user_envoie"]));
        
        $id_message=$req->fetchAll(PDO::FETCH_ASSOC);
    //  var_dump($id_message);
        
        $status_2= $bdd->insert("(?,?,?)","message_recu",array($insert["id_destinataire"],$id_message[0]["id"],$insert["date_envoie"]));
        
        $bdd = null;

        $status= ($status_1 && $status_2);
        echo '{"status":'.$status.'}';
        
    } 
    
    catch(PDOException $e) {
        
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
    
}
/**

     * @author  TEYEB Hazem


     * @return  tout les messages reçu par l'utilisateur de l'application sous format JSON

     */
function getMessages(){
    
    $requeste= Slim\Slim::getInstance()->request();

    $insert= $requeste->get("id_user");
//  var_dump($insert);
    
    try{
    
    $bdd=new BDD('test2','root','mysql','localhost');
    
    $req=$bdd->select_in_select("*","message","message.id IN","id_message","message_recu","id_user= ?",array($insert));
    
    $res=$req->fetchAll(PDO::FETCH_ASSOC);
    //var_dump($res);
    echo json_encode($res);
    
    }
    catch(PDOException $e){
        
        echo'{"error":{"text":'.$e->getMessage().'}}';
    }
    
}

/**

     * @author TEYEB Hazem


     * @return  un message celon l'id  sous format JSON

     */
function getMessageId(){
    
    
    $requeste= Slim\Slim::getInstance()->request();
    
    $id_message= $requeste->get("id_message");

    
    try{
    $bdd=new BDD('test2','root','mysql','localhost');
    
    $req= $bdd->select(" * ","message","id= ?",array($id_message));
    
    $res=$req->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($res);
    
    }
    catch(PDOException $e){
        
        echo'{"error":{"text":'.$e->getMessage().'}}';
    }
    
}
    
    function triDate($date_1,$date_2){
        
        return $date_1["date_envoie"] >= $date_2["date_envoie"]; 
    }
    
    function getConversation(){
    
    
    $requeste= Slim\Slim::getInstance()->request();
    
    $id_expediteur= $requeste->get("id_expediteur");
    $id_destinataire= $requeste->get("id_destinataire");

    
    try{
    $bdd=new BDD('test2','root','mysql','localhost');
    

    $req_1= $bdd->select_in_select("*","message","id_user_envoie= ? && message.id IN","id_message","message_recu","id_user=?",array($id_expediteur,$id_destinataire));
    $req_2= $bdd->select_in_select("*","message","id_user_envoie= ? && message.id IN","id_message","message_recu","id_user=?",array($id_destinataire,$id_expediteur));
    
    $res_1=$req_1->fetchAll(PDO::FETCH_ASSOC);
    $res_2=$req_2->fetchAll(PDO::FETCH_ASSOC);
    $res=array_merge($res_1,$res_2);
    usort($res,"triDate");
    echo json_encode($res);
    
    }
    catch(PDOException $e){
        
        echo'{"error":{"text":'.$e->getMessage().'}}';
    }
    
    
}

function getSortiesCreer(){
    
    $requeste=Slim\Slim::getInstance()->request();
    
    $id_user=$requeste->get("id_user");
    
    try{
        
        $bdd=new BDD('test2','root','mysql','localhost');
        $req=$bdd->select("*","sortie","id_user=?",array($id_user));
        $res=$req->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($res);
    }
    catch(PDOException $e){
        
        echo '{"error":{"text":'.$e->getMessage().'}}';
    }
    
}

function getSortiesParticiper(){
    
    $requeste=Slim\Slim::getInstance()->request();
    
    $id_user=$requeste->get("id_user");
    //var_dump($id_user);
    
    try{
        
        $bdd=new BDD('test2','root','mysql','localhost');
        $req=$bdd->select_in_select("*","sortie","sortie.id IN","id_sortie","user_participe_sortie","id_user= ?",array($id_user));
        $res=$req->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($res);
    }
    catch(PDOException $e){
        
        echo '{"error":{"text":'.$e->getMessage().'}}';
    }
    
    
}

/**************************************************************************/

/*
$app->get('/users/:id','getUser');
$app->get('/usersSortie/:id','getUserParticipate');
$app->delete('/users/:id','deleteUser');
$app->post('/users/:id','editUser');
$app->post('/users','addUser');
*/
function getSortiesSearch($nom,$agemin,$agemax,$date_sortie,$theme,$budget,$portee) {
    $temoin = 0; 
    $testnom=0;

    $testdate=0;
    $testportee=0;
    $testtheme=0;

    

    $sql = "SELECT sortie.id as id ,sortie.nom as nom, lieux, date_sortie, time, nb_places, portee, agemin, agemax, sortie.description as description, image, id_user, theme_sortie.nom as theme, budget FROM sortie,theme_sortie WHERE theme_sortie.id=sortie.id_theme_sortie";
    if( strcasecmp($theme,"indifferent") !==0 ) {
    $testtheme=1;
    
        
            $sql .= " And theme_sortie.nom = :theme"; 

        
}
    if( strcasecmp($nom,"indifferent") !==0 ) {
        $testnom=1;
        
            $sql .= " And sortie.nom = :nom "; 

        
}

    if( $agemax != null ) {
        

            $sql .= " And agemax <= :agemax "; 

        
}
if(strcasecmp($date_sortie,"indifferent") !== 0 ) {
    
        $testdate=1;
        
            $sql .= " And date_sortie = :date_sortie "; 

    
}
if( $portee != 0) {
    $testportee=1;
        
            $sql .= " And portee = :portee "; 

}
if( $budget != null ) {
        
            $sql .= " And budget <= :budget "; 

        
}
    if( $agemin != null ) {
        
            $sql .= " And agemin >= :agemin "; 

        
}

    try {
        //echo $sql;

        $db = getDB();
        $stmt = $db->prepare($sql);
        if ($testnom==1) {
            # code...   
                                $stmt->bindValue('nom', $nom);

        }
                $stmt->bindValue('agemin', $agemin);
                            $stmt->bindValue('agemax', $agemax);
    $stmt->bindValue('budget', $budget);
    if ($testportee==1) {
        # code...
                                    $stmt->bindValue('portee', $portee);

    }
    if ($testdate==1) {
        # code...
                                        $stmt->bindValue('date_sortie', $date_sortie);

    }
    if ($testtheme) {
        # code...
                                    $stmt->bindValue('theme', $theme);


    }
                                

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;

        echo  json_encode($rows) ;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


// Users Functions 
function getComment($id_sortie) {
    

    $sql = "SELECT date_commentaire,commentaire,user.nom FROM user_commente_sortie ,user ,sortie WHERE user.email=user_commente_sortie.id_user and sortie.id=user_commente_sortie.id_sortie and id_sortie=:id_sortie ";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue('id_sortie', $id_sortie);

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;

        echo  json_encode($rows) ;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


function getNbPlaces($id_sortie) {
    

    $sql = "SELECT count(*) as count FROM sortie,user_participe_sortie,user WHERE sortie.id=user_participe_sortie.id_sortie and user_participe_sortie.id_user=user.email and sortie.id=:id ";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue('id', $id_sortie);

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;

        echo  json_encode($rows) ;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
}

}

function getNbCommentaire($id_sortie) {
    

    $sql = "SELECT count(*) as count FROM user_commente_sortie,sortie,user WHERE sortie.id=user_commente_sortie.id_sortie and user_commente_sortie.id_user=user.email and sortie.id=:id ";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue('id', $id_sortie);

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;

        echo  json_encode($rows) ;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
}

}

function TestUserParticipeSortie($id_sortie,$id_user) {
    $sql = "SELECT * FROM sortie,user_participe_sortie,user WHERE sortie.id=user_participe_sortie.id_sortie and user_participe_sortie.id_user=user.email and sortie.id=:id_sortie and user.email =:id_user";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue('id_sortie', $id_sortie);

        $stmt->bindValue('id_user', $id_user);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;

        echo  json_encode($rows) ;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
}

}

//Shared Preferency 

function addParticipation() {
    

    $request = \Slim\Slim::getInstance()->request();
    $insert = json_decode($request->getBody());

    $sql = "INSERT INTO user_participe_sortie (id_user,id_sortie, date_participation) VALUES (:id_user, :id_sortie, :date_participation)";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        //$stmt->bindParam("id", $insert->id);
        $stmt->bindParam("id_sortie", $insert->id_sortie);
        $stmt->bindParam("id_user", $insert->id_user);
        $stmt->bindParam("date_participation", $insert->date_participation);
        
        $status = $stmt->execute();
        $db = null;

        echo '{"status":'.$status.',"message":"Ajout réussi"}';
    } catch(PDOException $e) {
        echo '{"status": "0","message":"Echec ajout  : "'.$e->getMessage().'}';
    }
    }
    function addCommentaire() {
    

    $request = \Slim\Slim::getInstance()->request();
    $insert = json_decode($request->getBody());

    $sql = "INSERT INTO user_commente_sortie (id_user,id_sortie, date_commentaire,commentaire   ) VALUES (:id_user, :id_sortie, :date_commentaire,:commentaire)";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        //$stmt->bindParam("id", $insert->id);
        $stmt->bindParam("id_user", $insert->id_user);
        $stmt->bindParam("id_sortie", $insert->id_sortie);
        $stmt->bindParam("date_commentaire", $insert->date_commentaire);
                $stmt->bindParam("commentaire", $insert->commentaire);

        
        $status = $stmt->execute();
        $db = null;

        echo '{"status":'.$status.',"message":"Ajout réussi"}';
    } catch(PDOException $e) {
        echo '{"status": "0","message":"Echec ajout  : "'.$e->getMessage().'}';
    }
    }

function addNote() {
    

    $request = \Slim\Slim::getInstance()->request();
    $insert = json_decode($request->getBody());

    $sql = "INSERT INTO noter_sortie (id_user,id_sortie, note,date_note ) VALUES (:id_user, :id_sortie, :note,:date_note)";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        //$stmt->bindParam("id", $insert->id);
        $stmt->bindParam("id_user", $insert->id_user);
        $stmt->bindParam("id_sortie", $insert->id_sortie);
        $stmt->bindParam("note", $insert->note);
                $stmt->bindParam("date_note", $insert->date_note);

        
        $status = $stmt->execute();
        $db = null;

        echo '{"status":'.$status.',"message":"Ajout réussi"}';
    } catch(PDOException $e) {
        echo '{"status": "0","message":"Echec ajout  : "'.$e->getMessage().'}';
    }
    }



function getNoteRate($id_sortie) {
    

    $sql = "SELECT avg(note) as count FROM noter_sortie,sortie,user WHERE sortie.id=noter_sortie.id_sortie and noter_sortie.id_user=user.email and sortie.id=:id ";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue('id', $id_sortie);

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;

        echo  json_encode($rows) ;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
}

}


function deleteSortie($id_sortie) {
    

    $sql = "DELETE FROM sortie WHERE id=:id_sortie";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id_sortie);
        $stmt->execute();
        $db = null;
        echo '{"status": "1", "message": "comment deleted"}';

    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getUsers() {
    

    $sql = "SELECT  * FROM user";
    try {
        $db = getDB();
        $stmt = $db->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo  json_encode($users);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getSorties() {
    

    $sql = "SELECT * FROM sortie";
    try {
        $db = getDB();
        $stmt = $db->query($sql);
        $sorties = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo  json_encode($sorties);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getThemes() {
    

    $sql = "SELECT * FROM theme_sortie";
    try {
        $db = getDB();
        $stmt = $db->query($sql);
        $sorties = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo  json_encode($sorties);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function addSorties() {
    

    $request = \Slim\Slim::getInstance()->request();
    $insert = json_decode($request->getBody());

    $sql = "INSERT INTO sortie (nom, lieux, date_sortie, time, nb_places, portee, agemin, agemax, description, image,id_user,id_theme_sortie,budget) VALUES (:nom, :lieux, :date_sortie, :time, :nb_places, :portee, :agemin, :agemax, :description, :image, :id_user,:id_theme_sortie,:budget)";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        //$stmt->bindParam("id", $insert->id);
        $stmt->bindParam("nom", $insert->nom);
        $stmt->bindParam("lieux", $insert->lieux);
        $stmt->bindParam("date_sortie", $insert->date_sortie);
        $stmt->bindParam("time", $insert->time);
        $stmt->bindParam("nb_places", $insert->nb_places);
        $stmt->bindParam("portee", $insert->portee);
        $stmt->bindParam("agemin", $insert->agemin);
        $stmt->bindParam("agemax", $insert->agemax);
        $stmt->bindParam("description", $insert->description);
        $stmt->bindParam("image", $insert->image);  
        $stmt->bindParam("id_user", $insert->id_user);
        $stmt->bindParam("id_theme_sortie", $insert->id_theme_sortie);  
        $stmt->bindParam("budget", $insert->budget);
        $status = $stmt->execute();
        $db = null;

        echo '{"status":'.$status.',"message":"Ajout réussi"}';
    } catch(PDOException $e) {
        echo '{"status": "0","message":"Echec ajout sorties : "'.$e->getMessage().'}';
    }
    }

function getThemesId($nom) {
    

    $sql = "SELECT theme_sortie.id FROM theme_sortie,sortie WHERE theme_sortie.id=sortie.id_theme_sortie and theme_sortie.nom=:nom";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue('nom', $nom);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;

        echo  json_encode($rows) ;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function connexion($email,$mdp){
    $sql = "SELECT * FROM user WHERE email =:email And mdp=:mdp";
    try{
        
        $db=getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue("email", $email);
        $stmt->bindValue("mdp", $mdp);
        
        $stmt->execute();
        $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db=null;
        echo json_encode($user[0]);
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}

function home(){
    $sql ="SELECT sortie.id, sortie.nom as nom, sortie.lieux, sortie.date_sortie, sortie.nb_places, sortie.portee, sortie.description, sortie.image, sortie.id_user, sortie.id_theme_sortie, sortie.agemin, sortie.agemax, sortie.time, user.nom as username, user.prenom FROM sortie, user WHERE sortie.id_user=user.email ORDER BY date_sortie";
    try{
        $db = getDB();
        $stmt = $db->query($sql);
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db=null;
        echo json_encode($resultat);
    }catch (PDOException $e) {
        echo $e->getMessage();
    }
} 

function userFriend($email){
    $demande="1";//"(SELECT ajouter_user.id_user_ajoute, user.nom, user.prenom, user.date_naissance, user.sexe, user.filiere, user.photo, user.ville, user.codepostal, user.centreinteret, user.nationalite, user.langue FROM ajouter_user, user WHERE ajouter_user.id_user =:email  AND user.email=ajouter_user.id_user_ajoute) UNION (SELECT ajouter_user.id_user, user.nom, user.prenom, user.date_naissance, user.sexe, user.filiere, user.photo, user.ville, user.codepostal, user.centreinteret, user.nationalite, user.langue FROM ajouter_user, user WHERE ajouter_user.id_user_ajoute =:email  AND user.email=ajouter_user.id_user) UNION (SELECT ajouter_user.id_user_ajoute, user.nom, user.prenom, user.date_naissance, user.sexe, user.filiere, user.photo, user.ville, user.codepostal, user.centreinteret, user.nationalite, user.langue FROM ajouter_user, user WHERE ajouter_user.id_user =:email  AND ajouter_user.statut=:demande) UNION (SELECT ajouter_user.id_user, user.nom, user.prenom, user.date_naissance, user.sexe, user.filiere, user.photo, user.ville, user.codepostal, user.centreinteret, user.nationalite, user.langue FROM ajouter_user, user WHERE ajouter_user.id_user_ajoute =:email  AND ajouter_user.statut=:demande)";
    $sql = "(SELECT ajouter_user.id_user_ajoute, user.nom, user.prenom, user.date_naissance, user.sexe, user.filiere, user.photo, user.ville, user.codepostal, user.centreinteret, user.nationalite, user.langue FROM ajouter_user, user WHERE ajouter_user.id_user =:email  AND user.email=ajouter_user.id_user_ajoute AND ajouter_user.statut=:demande) UNION (SELECT ajouter_user.id_user, user.nom, user.prenom, user.date_naissance, user.sexe, user.filiere, user.photo, user.ville, user.codepostal, user.centreinteret, user.nationalite, user.langue FROM ajouter_user, user WHERE ajouter_user.id_user_ajoute =:email  AND user.email=ajouter_user.id_user AND ajouter_user.statut=:demande)";//
    try{//, user.filiere, user.photo, user.ville, user.codepostal, user.centreinteret, user.nationalite, user.langue // OR ajouter_user.id_user_ajoute =:email
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue("email", $email);
        $stmt->bindValue("demande", $demande);
        $stmt->execute();
        $liste = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        echo json_encode($liste);
    }catch (PDOException $e){
        echo $e->getMessage();
    }
}

function userFriendRequest($email){
    $demande="0";//"(SELECT ajouter_user.id_user_ajoute, user.nom, user.prenom, user.date_naissance, user.sexe, user.filiere, user.photo, user.ville, user.codepostal, user.centreinteret, user.nationalite, user.langue FROM ajouter_user, user WHERE ajouter_user.id_user =:email  AND user.email=ajouter_user.id_user_ajoute) UNION (SELECT ajouter_user.id_user, user.nom, user.prenom, user.date_naissance, user.sexe, user.filiere, user.photo, user.ville, user.codepostal, user.centreinteret, user.nationalite, user.langue FROM ajouter_user, user WHERE ajouter_user.id_user_ajoute =:email  AND user.email=ajouter_user.id_user) UNION (SELECT ajouter_user.id_user_ajoute, user.nom, user.prenom, user.date_naissance, user.sexe, user.filiere, user.photo, user.ville, user.codepostal, user.centreinteret, user.nationalite, user.langue FROM ajouter_user, user WHERE ajouter_user.id_user =:email  AND ajouter_user.statut=:demande) UNION (SELECT ajouter_user.id_user, user.nom, user.prenom, user.date_naissance, user.sexe, user.filiere, user.photo, user.ville, user.codepostal, user.centreinteret, user.nationalite, user.langue FROM ajouter_user, user WHERE ajouter_user.id_user_ajoute =:email  AND ajouter_user.statut=:demande)";
    $sql = "(SELECT ajouter_user.id_user_ajoute, user.nom, user.prenom, user.date_naissance, user.sexe, user.filiere, user.photo, user.ville, user.codepostal, user.centreinteret, user.nationalite, user.langue FROM ajouter_user, user WHERE ajouter_user.id_user =:email  AND user.email=ajouter_user.id_user_ajoute AND ajouter_user.statut=:demande) UNION (SELECT ajouter_user.id_user, user.nom, user.prenom, user.date_naissance, user.sexe, user.filiere, user.photo, user.ville, user.codepostal, user.centreinteret, user.nationalite, user.langue FROM ajouter_user, user WHERE ajouter_user.id_user_ajoute =:email  AND user.email=ajouter_user.id_user AND ajouter_user.statut=:demande)";//
    try{//, user.filiere, user.photo, user.ville, user.codepostal, user.centreinteret, user.nationalite, user.langue // OR ajouter_user.id_user_ajoute =:email
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue("email", $email);
        $stmt->bindValue("demande", $demande);
        $stmt->execute();
        $liste = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        echo json_encode($liste);
    }catch (PDOException $e){
        echo $e->getMessage();
    }
}

function emailVerif($email){
    $sql = "SELECT email FROM user WHERE email=:email";
    try{
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue("email", $email);
        $stmt->execute();
        $liste = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($liste[0]);

        $db = null;
    }catch (PDOException $e){
        echo $e->getMessage();
    }
}

function passwordRecovery($email,$mdp){//
        $request = \Slim\Slim::getInstance()->request();
    $body = json_decode($request->getBody());
    //var_dump($body);
    $sql = "UPDATE user SET mdp=:mdp WHERE email=:email";
    try{
    $db = getDB();
        $stmt = $db->prepare($sql);
        //$stmt->bindParam("id", $insert->id);
        $stmt->bindParam("mdp",$mdp);
        $stmt->bindParam("email", $email);
        $status = $stmt->execute();
        $db = null;
        echo '{"status":'.$status.',"message":"Ajout réussi"}';
    }catch (PDOException $e){
        echo $e->getMessage();
    }
    //$message = "Coucou slt Franck";
    //mail($email, "Test localhost", $message);//, $headers
}
/*
function getUserParticipate($id) {
    

    $sql = "SELECT * FROM utilisateur,sorties,participersortie WHERE sorties.id = participersortie.idSortie and  participersortie.idUtilisateur=utilisateur.id and sorties.id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;

        echo  json_encode($rows) ;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


function deleteUser($id) {
    

    $sql = "DELETE FROM utilisateur WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
        echo true;

    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function addUser() {
    

    $request = \Slim\Slim::getInstance()->request();
    $insert = json_decode($request->getBody());

    $sql = "INSERT INTO user (name,surname,email,password,pseudo) VALUES (:name,:surname,:email,:password,:pseudo)";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("name", $insert->name);
        $stmt->bindParam("surname", $insert->surname);
        $stmt->bindParam("email", $insert->email);
        $stmt->bindParam("password", $insert->password);
        $stmt->bindParam("pseudo", $insert->pseudo);
        $status = $stmt->execute();
        $db = null;

        echo '{"status":'.$status.'}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function editUser($id) {
    

    $request = \Slim\Slim::getInstance()->request();
    $update = json_decode($request->getBody());

    $sql = "UPDATE utilisateur SET name=:name,email=:email,surname=:surname,password=:password,pseudo=:pseudo WHERE id = :id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("name", $update->name);
        $stmt->bindParam("surname", $update->surname);
        $stmt->bindParam("email", $update->email);
        $stmt->bindParam("password", $update->password);
        $stmt->bindParam("pseudo", $update->pseudo);
        $stmt->bindParam("id", $id);

        $status = $stmt->execute();
        $db = null;

        echo '{"status":'.$status.'}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
*/

function aa($nom,$prenom,$date_naissance,$date_inscription,$sexe,$filiere,$ville,$codepostal,$centreinteret,$nationalite,$langue){
$sql="SELECT * FROM user WHERE email LIKE '%@%'";

    $name=0;
    $firstname=0;
    $birth_date=0;
    $registration_date=0;
    $sex=0;
    $industry=0;
    $town=0;
    $postal_code=0;
    $hobby=0;
    $nationality=0;
    $language=0;
    if(strcasecmp($nom,"all")!==0){
        $name=1;
        $sql .=" AND nom LIKE '%{$nom}%'";
    }if(strcasecmp($prenom,"all")!==0){
        $fistname=1;
        $sql .=" AND prenom LIKE '%{$prenom}%'";
    }
    if($sexe == 0){
        $sex=1;
        $sql .=" AND sexe=$sexe";
    }
    if($sexe == 1){
        $sex=1;
        $sql .=" AND sexe=$sexe";
    }
    if(strcasecmp($date_naissance,"all")!==0){
        $birth_date=1;
        $sql .=" AND date_naissance LIKE '%{$date_naissance}%'";
    }
    if(strcasecmp($date_inscription,"all")!==0){
        $registration_date=1;
        $sql .=" AND date_inscription LIKE '%{$date_inscription}%'";
    }

    if(strcasecmp($filiere,"all")!==0){
        $industry=1;
        $sql .=" AND filiere LIKE '%{$filiere}%'";
    }if(strcasecmp($ville,"all")!==0){
        $town=1;
        $sql .=" AND ville LIKE '%{$ville}%'";
    }
    if($codepostal != 0){
        $postal_code=1;
        $sql .=" AND codepostal=$codepostal";
    }
    if(strcasecmp($centreinteret,"all")!==0){
        $hobby=1;
        $sql .=" AND centreinteret LIKE '%{$centreinteret}%'";
    }if(strcasecmp($nationalite,"all")!==0){
        $nationality=1;
        $sql .=" AND nationalite LIKE '%{$nationalite}%'";
    }
    if(strcasecmp($langue,"all")!==0){
        $language=1;
        $sql .=" AND langue LIKE '%{$langue}%'";
    }
    
try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        if($name==1){
            $stmt->bindValue('nom', $nom);
        }if($firstname==1){
            $stmt->bindValue('prenom', $prenom);
        }if($birth_date==1){
            $stmt->bindValue('date_naissance', $date_naissance);
        }if($registration_date==1){
            $stmt->bindValue('date_inscription', $date_inscription);
        }if($sex==1){
            $stmt->bindValue('sexe', $sexe);
        }if($industry==1){
            $stmt->bindValue('filiere', $filiere);
        }if($town==1){
            $stmt->bindValue('ville', $ville);
        }if($postal_code==1){
            $stmt->bindValue('codepostal', $codepostal);
        }if($hobby==1){
            $stmt->bindValue('centreinteret', $centreinteret);
        }if($nationality==1){
            $stmt->bindValue('nationalite', $nationalite);
        }if($language==1){
            $stmt->bindValue('langue', $langue);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;

        echo  json_encode($rows) ;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
    
    
    
}




function getUser($id) {
    

    $sql = "SELECT * FROM user WHERE email=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;

        echo  json_encode($rows) ;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}



function getUserSignaler($id_user_signaler) {
    

    $sql = "SELECT * FROM signaler_user WHERE id_user_signaler=:id_user_signaler";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue('id_user_signaler', $id_user_signaler);
        
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;

        echo  json_encode($rows) ;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getSignalerUser($id_user,$id_user_signaler) {
    

    $sql = "SELECT * FROM signaler_user WHERE id_user=:id_user AND id_user_signaler=:id_user_signaler";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue('id_user', $id_user);
        $stmt->bindValue('id_user_signaler', $id_user_signaler);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;

        echo  json_encode($rows) ;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


function addSignaler() {
    

    $request = \Slim\Slim::getInstance()->request();
    $insert = json_decode($request->getBody(),TRUE);
    //var_dump($insert);

    $sql = "INSERT INTO signaler_user VALUES (?,?)";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        
        $status = $stmt->execute(array($insert["id_user"],$insert["id_user_signaler"]));
        $db = null;

        echo '{"status":'.$status.'}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}











function getFriend($id) {
    

    $sql = "SELECT * FROM ajouter_user WHERE id_user=:id OR id_user_ajoute=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;

        echo  json_encode($rows) ;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function deleteAmi($id) {
    

    $sql = "DELETE FROM ajouter_user WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
        echo true;

    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}



function deleteUser($id) {
    

    $sql = "DELETE FROM user WHERE email=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
        echo true;

    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}



function acceptUser($id) {
    $sql = "UPDATE ajouter_user SET etat=1 WHERE ajouter_user.id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
        echo true;

    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function addFriend() {
    

    $request = \Slim\Slim::getInstance()->request();
    $insert = json_decode($request->getBody(),TRUE);
    //var_dump($insert);

    $sql = "INSERT INTO ajouter_user VALUES (?,?,?,?,?)";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        
        $status = $stmt->execute(array(NULL,$insert["id_user"],$insert["id_user_ajoute"],$insert["date_ajout"],$insert["etat"]));
        $db = null;

        echo '{"status":'.$status.'}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}




?>
