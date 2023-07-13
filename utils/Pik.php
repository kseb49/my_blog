<?php
 namespace utils;

use Exception;
use core\Controller;

 class Pik extends Controller {

 	public $_name;

 	public $_image;


 	/**
 	 *
 	 * @param      array  $pik    $_FILES 
 	 * 
 	 */
 	public function __construct(array $pik = []) {
 		$this->_image = $pik['image'];
        parent::__construct();
 	}

 	/**
 	 * Check type is authorized
 	 *
 	 */
 	public function check() :bool 
	{
		$type  = strtolower(basename($this->_image['type']));
		if(in_array($type,$this->type_auth)) {
			if ($this->_image['size'] > $this->size) {
				throw new Exception($this->uploadErrors(UPLOAD_ERR_FORM_SIZE));
			}
			$name = preg_replace("#[()¨^°*‘«»<>\"°`\#{\}[\]<>|/@=~\\+*%\$€?:&\#;,éè]+#","",str_replace(' ','_',strtolower(trim(htmlspecialchars($this->_image['name'])))));
			strlen($name)<=40 ? $this->_name = $name : $this->_name = substr($name,-40);
			return true;
		}
		throw new Exception($this->uploadErrors(UPLOAD_ERR_CANT_WRITE));
	}
 
 	/**
 	 * move file from php tmp directory
 	 * 
 	 */
 	public function parker() :bool
	{
		if (move_uploaded_file($this->_image['tmp_name'],IMAGE.$this->_name) === true) {
			return true;
		}
		throw new Exception("Une erreur est survenue, merci de réessayer - SAVE IMG");
	}
 	
	public function uploadErrors(string $error) :string {
		switch ($error) {

			case UPLOAD_ERR_INI_SIZE:

				$message = "La taille du fichier téléchargé est trop importante";

				break;

			case UPLOAD_ERR_FORM_SIZE:

				$message = "La taille du fichier téléchargé est trop importante";

				break;

			case UPLOAD_ERR_PARTIAL:

				$message = " Le fichier n'a été que partiellement téléchargé.";

				break;

			case UPLOAD_ERR_NO_FILE:

				$message = "Aucun fichier n'a été téléchargé.";

				break;

			case UPLOAD_ERR_NO_TMP_DIR:

				$message = "Un dossier temporaire est manquant.";

				break;

			case UPLOAD_ERR_CANT_WRITE:

				$message = " Image : Ce type de fichier n'est pas accepté";

				break;

			case UPLOAD_ERR_EXTENSION:

				$message = "Erreur interne";

				break;

			default:

				$message = "Unknown upload error";

				break;

		}
		return $message;
	}
	
 }
