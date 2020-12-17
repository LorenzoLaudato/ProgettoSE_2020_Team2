<?php

class MS_Users
{
    
    
    /**This method inserts a new user in the table Account of the DB.
     * Params:
     * $nome: it represents the name of the new user;
     * $username: it represents the username of the new user;
     * $password: it represents the password of the new user;
     * $email: it represents the email of the new user;
     * $role: it represents the role of the new user;
     * $db: it represents the resource Database.
     */
    public static function insert_utente($nome, $username, $password, $email, $role, $db)
    {
        //CONTROLLO SE NOME E USERNAME SONO STATI INSERITI
        if (empty($nome) || empty($username)) {
            return false;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO ACCOUNT(nome,username,pass,ruolo,email) VALUES('$nome', '$username', '$hash','$role' ,'$email');";
        $ret = pg_query($db->database, $sql);
        if (!$ret) {
            return false;
        } else {
            return true;
        }
    }


    /**This method checks if already exists the username inserted. 
     * Params:   
     * $username: it represents the username by checking;
     * $db: it represents the resource Database.
     */
    public static function username_exist($db, $username)
    {
        $sql = "SELECT username FROM account WHERE username='$username';";
        $ret = pg_query($db->database, $sql);
        if (pg_num_rows($ret) == 0) {
            return false; //false significa che non esiste -> quindi può essere registrato
        } else {
            return true; //true significa che esiste già
        }
    }


    /**This method returns the password associated to the username. 
     * Params:   
     * $username: it represents the username by checking;
     * $role: it represents the role of the user;
     * $db: it represents the resource Database.
     */
    public  static function get_pwd($db, $username, $role)
    {
        $sql = "SELECT pass FROM account WHERE username='$username' and ruolo='$role' ;";
        $ret = pg_query($db->database, $sql);
        if (!$ret) {
            echo "ERRORE QUERY: " . pg_last_error($db->database);
            return false;
        } else {
            if ($row = pg_fetch_assoc($ret)) {
                $password1 = $row['pass'];
                return $password1;
            } else {
                return false;
            }
        }
    }


    /**This method returns the Users that are registered at the app, without the current user.
     * Params:
     * $username: it represents the username of the current logged user;
     * $db: it represents the resource Database.
     */
    public static function get_users($db, $username)
    {
        $sql = "SELECT nome, username, email, ruolo FROM account WHERE username!='$username' ORDER BY ruolo;";
        $ret = pg_query($db->database, $sql);
        if (!$ret) {
            echo "ERRORE QUERY: " . pg_last_error($db->database);
            return false;
        } else {
            return $ret;
        }
    }


    /**This method deletes a User from the Account table present in the DB.
     * Params:
     * $username: it represents the username of the user by deleting;
     * $db: it represents the resource Database.
     */
    public static function delete_user($username, $db)
    {
        $sql = "DELETE FROM account WHERE username = '$username';";
        $ret = pg_query($db->database, $sql);
        if (!$ret) {
            echo "ERRORE QUERY";
            exit;
        }
        return $ret;
    }


    /**This method updates some attributes of a User already present in  the Account table of the DB.
     * Params:
     * $username: it represents the username of the user by updating;
     * $db: it represents the resource Database.
     */
    public static function update_user($username, $db)
    {

        if (!empty($_POST['userType']) && !empty($_POST['username-input'])) {
            $new_role = $_POST['userType'];
            $new_username = $_POST['username-input'];
            $sql = "UPDATE account SET (ruolo,username) = ($1,$2) WHERE username='$username';";
            $prep = pg_prepare($db->database, "UpdateAccount", $sql);
            if (!$prep) {
                echo pg_last_error($db->database);
            } else {
                $ret_update = pg_execute($db->database, "UpdateAccount", array($new_role, $new_username));
                if (!$ret_update) {
                    $new_username = "";
                } else {
                    echo "<script>okEdit('username-input','edited','form');</script>";
                }
            }
        }
    }
}
