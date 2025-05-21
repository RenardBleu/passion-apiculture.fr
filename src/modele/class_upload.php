<?php
class Upload{
    private $extensions; 
    private $chemin; 
    private $taille;
    /*
    $extensions array comprenant toutes les extensions
    $chemin vers le répertoire de stockage des fichiers
    $taille taille max du fichier en ko retourne le nom de la photo ou null
    */
    public function __construct($extensions, $chemin, $taille){
        $this->extensions = $extensions;
        $this->chemin = $chemin;
        $this->taille = $taille;
    }
    public function enregistrer($data){
        $fichier =array();
        $fichier['nom'] = null;
        $fichier['erreur'] = null;
        $msg = null;
        if(isset($_FILES[$data])){
            if(!empty($_FILES[$data]['name'])){
                if($_FILES[$data]['error'] == 0){
                    if( !in_array(substr(strrchr($_FILES[$data]['name'], '.'), 1), $this->extensions)){
                        $msg = 'Veuillez sélectionner un fichier de type : ';
    
                        foreach($this->extensions as $uneExtension){
                            $msg .= $uneExtension.' ';
                        }
                    }else{
                        if( file_exists($_FILES[$data]['tmp_name'])&&(filesize($_FILES[$data]['tmp_name'])) > $this->taille){
                            echo 'Votre fichier doit faire moins de '.$this->taille.'Ko !';
                        }else{
                            $fichier['nom'] = basename($_FILES[$data]['name']);
                            // enlever les accents
                            $fichier['nom']=strtr($fichier['nom'],
                            'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
                            'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                            // remplacer les caractères autres que lettres, chiffres et point par _
                            if(!file_exists($this->chemin.'/'.$_FILES[$data]['name'])){
                            $fichier['nom'] = preg_replace('/([^.a-z0-9]+)/i', '_', $fichier['nom']);
                            // copie du fichier
                            $i = 0;
                            $nomFichier = substr($fichier['nom'], 0, -strlen(strrchr($fichier['nom'], '.')));
                            $extension = strrchr($fichier['nom'], '.');
                            $cheminComplet = $this->chemin.'/'.$nomFichier.$extension;
                            
                            while(file_exists($cheminComplet)) {
                                $i++;
                                $cheminComplet = $this->chemin.'/'.$nomFichier.'_'.$i.$extension;
                            }
                            
                            move_uploaded_file($_FILES[$data]['tmp_name'], $cheminComplet);
                            $fichier['nom'] = '/'.$this->chemin.'/'.$nomFichier.($i > 0 ? '_'.$i : '').$extension;
                            }
                        }
                    }
                }
            }
            $fichier['erreur'] = $msg; 
            return $fichier;
        }
    } 
    public function delete($name){
        $file = substr($name, 1);
        $fichier = array();
        $fichier['nom'] = null;
        $fichier['erreur'] = null;
        if (file_exists($file)){
            unlink($file);
            $fichier['nom'] = $name;
        }else{
            $fichier['erreur'] = 'Le fichier n\'existe pas';
        }
        return $fichier;
    }
 }