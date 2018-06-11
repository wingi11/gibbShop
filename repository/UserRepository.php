<?php

require_once '../lib/Repository.php';

class UserRepository extends Repository {

	protected $tableName = 'users';

	/**
	 * Einen Benutzer regsitrieren
	 *
	 * @param $username
	 * @param $firstName
	 * @param $lastName
	 * @param $email
	 * @param $date
	 * @param $password
	 * @param $teamId
	 * @return bool
	 * @throws Exception
	 */
	public function create($username, $firstName, $lastName, $email, $date, $password, $teamId) {
		$password = sha1($password);
		$query = "SELECT username,email FROM users WHERE username = ? OR email = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param("ss", $username, $email);

		$statement->execute();
		$result = $statement->get_result();

		$userExist = false;

		while ($row = $result->fetch_object()) {
			$_SESSION["messages"] = array();
			if ($row->email == $email) {
				array_push($_SESSION["messages"], "Es existiert bereits ein Benutzer mit dieser E-Mail.");
			}
			if ($row->username == $username) {
				array_push($_SESSION["messages"], "Dieser Benutzer exisitiert bereits.");
			}
			$userExist = true;
		}
		if (!$userExist) {
			$active = 1;
			$query = "INSERT INTO $this->tableName (username, prename, lastname, email, birthdate, password, team_id, activated) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
			$statement = ConnectionHandler::getConnection()->prepare($query);
			$statement->bind_param('ssssssii', $username, $firstName, $lastName, $email, $date, $password, $teamId, $active);
			if (!$statement->execute()) {
				$_SESSION["messages"] = array("Fehler beim erstellen des Benutzers!");
				return false;
			}
			$id = $statement->insert_id;
			$_SESSION["user"]["email"] = $email;
			$_SESSION["user"]["id"] = $id;
			$_SESSION["user"]["username"] = $username;
			$_SESSION["user"]["firstname"] = $firstName;
			$_SESSION["user"]["lastname"] = $lastName;
			$_SESSION["user"]["pb"] = "";
			$_SESSION["loggedin"] = true;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Einen Benutzer einloggen
	 *
	 * @param $username
	 * @param $password
	 * @return bool
	 * @throws Exception
	 */
	public function loginUser($username, $password) {
		$password = sha1($password);
		$query = "SELECT * FROM users WHERE username = ? AND password = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param("ss", $username, $password);

		$statement->execute();
		$result = $statement->get_result();

		if ($result->num_rows == 1) {
			$row = $result->fetch_object();
			if ($row->activated != 0) {
				$_SESSION["user"]["email"] = $row->email;
				$_SESSION["user"]["id"] = $row->id;
				$_SESSION["user"]["username"] = $username;
				$_SESSION["user"]["pb"] = $row->profilepicture;
				$_SESSION["user"]["firstname"] = $row->prename;
				$_SESSION["user"]["lastname"] = $row->lastname;
				$_SESSION["loggedin"] = true;
				return true;
			} else {
				$_SESSION["messages"] = array("Dein Benutzerkonto wurde gesperrt!");
				return false;
			}
		}
		$_SESSION["messages"] = array("Falsche Logindaten");
		return false;
	}

	public function loginAdmin($username, $password) {
		$password = sha1($password);
		$query = "SELECT * FROM users WHERE username = ? AND password = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param("ss", $username, $password);

		$statement->execute();
		$result = $statement->get_result();

		if ($result->num_rows == 1) {
			$row = $result->fetch_object();
			$_SESSION["user"]["email"] = $row->email;
			$_SESSION["user"]["id"] = $row->id;
			$_SESSION["user"]["username"] = $username;
			$_SESSION["user"]["pb"] = $row->profilepicture;
			$_SESSION["user"]["firstname"] = $row->prename;
			$_SESSION["user"]["lastname"] = $row->lastname;

			$query = "SELECT * FROM admins WHERE user_id = ?";
			$statement = ConnectionHandler::getConnection()->prepare($query);
			$statement->bind_param("i", $row->id);

			$statement->execute();
			$result = $statement->get_result();

			if ($result->num_rows == 1) {
				$_SESSION["adminloggedin"] = true;
				return true;
			}
		}
		return false;
	}

	/**
	 * Der Benutzer nach ID zurückgeben
	 *
	 * @param $id
	 * @return array|bool
	 * @throws Exception
	 */
	public function getUserById($id) {
		$query = "SELECT * FROM users WHERE id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param("i", $id);

		$statement->execute();
		$result = $statement->get_result();

		$user = array();

		if ($result->num_rows == 1) {
			$row = $result->fetch_object();
			array_push($user, $row->email);
			array_push($user, $row->username);
			array_push($user, $row->prename);
			array_push($user, $row->lastname);
			array_push($user, $row->id);
			return $user;
		}
		return false;
	}

	/**
	 * Alle Benutzer zurückgeben
	 *
	 * @return array|bool
	 * @throws Exception
	 */
	public function getAllUsers() {
		$query = "SELECT * FROM users";
		$statement = ConnectionHandler::getConnection()->prepare($query);

		$statement->execute();
		$result = $statement->get_result();

		$users = array();

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_object()) {
				$user = array();
				array_push($user, $row->email);
				array_push($user, $row->username);
				array_push($user, $row->prename);
				array_push($user, $row->lastname);
				array_push($user, $row->activated);
				array_push($user, $row->id);
				array_push($users, $user);
			}
			return $users;
		}
		return false;
	}

	/**
	 * Einige einstellungen neu Setzen falls das Profil bearbeitet wurde
	 *
	 * @param $isImage
	 * @param $fileName
	 * @param $password
	 * @throws Exception
	 */
	public function updateSettings($isImage, $fileName, $password) {
		if (isset($password)) {
			$password = sha1($password);
			$query = "UPDATE $this->tableName SET password = ? WHERE id = ?";
			$statement = ConnectionHandler::getConnection()->prepare($query);
			$statement->bind_param('si', $password, $_SESSION["user"]["id"]);
			if (!$statement->execute()) {
				throw new Exception($statement->error);
			}
		}

		if ($isImage) {
			$query = "UPDATE $this->tableName SET profilepicture = ? WHERE id = ?";
			$statement = ConnectionHandler::getConnection()->prepare($query);
			$statement->bind_param('si', $fileName, $_SESSION["user"]["id"]);
			if (!$statement->execute()) {
				throw new Exception($statement->error);
			}
			$_SESSION["user"]["pb"] = $fileName;
		}

	}

	/**
	 * Das Profilbild eines Benutzers zurückgeben
	 *
	 * @param $id
	 * @return mixed
	 * @throws Exception
	 */
	public function getImg($id) {
		$query = "SELECT profilepicture FROM users WHERE id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param("i", $id);

		$statement->execute();
		$result = $statement->get_result();

		if ($result->num_rows == 1) {
			$row = $result->fetch_object();
			$_SESSION["user"]["pb"] = $row->profilepicture;
			return $row->profilepicture;
		}
	}

	/**
	 * Alle Nachrichten eines Benutzers zurückgeben
	 *
	 * @param $userId
	 * @return array
	 * @throws Exception
	 */
	public function getMessagesFromUser($userId) {
		$query = "select id, title, description, state, gelesen from mitteilungen where users_id = ? order by id desc";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('i', $userId);
		$statement->execute();
		$result = $statement->get_result();

		$messages = array();

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_object()) {
				$message = array();
				array_push($message, $row->id);
				array_push($message, $row->title);
				array_push($message, $row->description);
				array_push($message, $row->state);
				array_push($message, $row->gelesen);
				array_push($messages, $message);
			}

		}
		return $messages;
	}

	/**
	 * Eine bestimmte Nachricht löschen
	 *
	 * @param $id
	 * @throws Exception
	 */
	public function deleteMessageById($id) {
		$query = "DELETE FROM mitteilungen WHERE id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param("i", $id);

		$statement->execute();
	}

	/**
	 * Schauen ob die Nachricht diesem Benutzer gehört
	 *
	 * @param $msgId
	 * @param $userId
	 * @return bool
	 * @throws Exception
	 */
	public function isMessageFrom($msgId, $userId) {
		$query = "SELECT id FROM mitteilungen WHERE id = ? AND users_id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param("ii", $msgId, $userId);

		$statement->execute();
		$result = $statement->get_result();

		if ($result->num_rows == 1) {
			return true;
		}
		return false;
	}

	/**
	 * Neue Nachricht für einen Benutzer erstellen
	 *
	 * @param $userId
	 * @param $title
	 * @param $des
	 * @param $state
	 * @throws Exception
	 */
	public function setMessageForUser($userId, $title, $des, $state) {
		$query = "INSERT INTO mitteilungen (users_id, title, description, state) VALUES (?, ?, ?, ?)";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('issi', $userId, $title, $des, $state);
		if (!$statement->execute()) {
			throw new Exception($statement->error);
		}
	}

	/**
	 * Eine Nachricht als gelesen markieren
	 *
	 * @param $id
	 * @throws Exception
	 */
	public function setMessageAsRead($id) {
		$isRead = 1;
		$query = "UPDATE mitteilungen SET gelesen = ? WHERE id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('ii', $isRead, $id);
		$statement->execute();
	}

	/**
	 * Benutzer sperren
	 *
	 * @param $id
	 * @param $action
	 * @throws Exception
	 */
	public function lockUser($id, $action) {
		$query = "UPDATE users SET activated = ? WHERE id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('ii', $action, $id);
		$statement->execute();
	}

	/**
	 * Schauen ob der Benutzer gesperrt ist
	 *
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	public function isUserLocked($id) {
		$query = "SELECT activated FROM users WHERE id = ? AND activated = 0";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param("i", $id);

		$statement->execute();
		$result = $statement->get_result();

		if ($result->num_rows == 1) {
			return true;
		}
		return false;
	}

	public function deleteUser($id){
		//Verknüpfung von auction löschen
		$query = "DELETE FROM auction WHERE user_id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param("i", $id);

		if (!$statement->execute()) {
			throw new Exception($statement->error);
		}

		//Verknüpfung von click löschen
		$query = "DELETE FROM clicks WHERE users_id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param("i", $id);

		if (!$statement->execute()) {
			throw new Exception($statement->error);
		};

		$query = "select id from product where user_id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('i', $userId);
		$statement->execute();
		$result = $statement->get_result();

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_object()) {
				$query = "DELETE FROM product_tags WHERE product_id = ?";
				$statement = ConnectionHandler::getConnection()->prepare($query);
				$statement->bind_param("i", $row->id);

				if (!$statement->execute()) {
					throw new Exception($statement->error);
				}
			}

		}

		//Produkt Löschen
		$query = "DELETE FROM product WHERE user_id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param("i", $id);

		if (!$statement->execute()) {
			throw new Exception($statement->error);
		}

		//Benutzer Löschen
		$query = "DELETE FROM users WHERE id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param("i", $id);

		if (!$statement->execute()) {
			throw new Exception($statement->error);
		}
	}
}