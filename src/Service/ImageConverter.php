<?php

namespace App\Service;

use App\Entity\Flash;
use App\Entity\Tattoo;

class ConvertImageFormat
{
    // Methode permettant de modifier la taille d'une image, la convertir en webP et ajouter le nom de celle ci dans l'entité fournie
	public function convertImageToWebp(array $files, ?Flash $flash, ?Tattoo $tattoo): bool
    {
        $image = $files['name']['image'];
        $tmpName = $files['tmp_name']['image'];
        $error = $files['error']['image'];
        list($width, $height) = getimagesize($tmpName);
        $extensions = array ('webp', 'png', 'jpeg', 'avif', 'jpg');
        $tabExtension = explode('.', $image);
        $mime = mime_content_type($tmpName);
        // On sépare le nom du flash en plusieurs partie en fonction dès que l'on tombe sur le caractère rentré en paramètres, donc le "."

        if(!in_array($mime, ['image/webp', 'image/jpeg', 'image/png', 'image/avif', 'image/jpg'])) {
        // On vérifie également que le mime du fichier correspond bien à l'extension.
        // Cela empêche les fichiers non-images (ou scripts) de passer, même si l’extension est correcte.
        
            return false;
        }

        $imageString = file_get_contents($tmpName);
        // Lit l'image et la transforme en chaine de caractères

        $image = imagecreatefromstring($imageString);
        // Transforme la chaine de caractères en une image
        
        // Dans le cas ou l'image est au format paysage
        if($width > $height)
        {
            // Si la largeur dépasse 350 pixels
            if($width > 350)
            {
                // On veut une image relativement petite, alors tant qu'elle fait plus de 350 pixels, on divise sa hauteur et sa largeur
                while($width > 350)
                {
                    // Largeur divisée par 2
                    $width /= 2;
                    // Hauteur divisée par 2
                    $height /= 2;
                }
            }
            else
            {
                // Si l'image est très petite, on vient aggrandir ses dimensions
                while($width < 150)
                {
                    // Multiplication de sa largeur par 2
                    $width *= 2;
                    // Multiplication de sa hauteur par 2
                    $height *= 2;
                }
            }
        }
        // Dans le cas ou l'image est au format portrait
        if($width < $height)
        {
            if($height > 350)
            {
                while($height > 350)
                {
                    $width /= 2;
                    $height /= 2;
                } 
            }
            else
            {
                while($height < 150)
                {
                    $width *= 2;
                    $height *= 2;
                }
            }
            
        }
        // Dans le cas ou l'image est un carré
        elseif($height > 350 || $width > 350)
        {
            if($height > 350 || $width > 350)
            {
                while($height > 350 || $width > 350)
                {
                    $width /= 2;
                    $height /= 2;
                }
            }
            else
            {
                while($height < 150 || $width < 150)
                {
                    $width *= 2;
                    $height *= 2;
                }
            }
        }

        $newImg = imagescale($image, $width, $height);
        // Redimensionne l'image avec la taille et largeur calculée

        $extension = strtolower(end($tabExtension));
        // On met en minuscule le nom de l'extension pour pouvoir la comparer à l'extension du dessus, plus tard

        if(in_array($extension, $extensions))
        // Si le fichier séléctionné est un fichier webp, png, jpeg ou avif
        {
            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $idName = $uniqueName . ".webp";
            //$affiche = 5f586bf96dcd38.73540086.webp

            // Si un objet flash a été rentré en paramètres
            if($flash)
            {
                // On ajoute l'unique ID dans le flash
                $flash->setImage($idName);
                
                // Puis convertit et stocke l'imgage dans un dossier séparé des images du site
                imagewebp($newImg, '../public/img/flashs/'. $idName);
            }
            // Si un objet tattoo a été rentré en paramètres
            elseif($tattoo)
            {
                // On ajoute l'unique ID dans le tattoo
                $tattoo->setImage($idName);

                // Puis on stocke l'imgage dans un dossier séparé des images du site ou des flashs
                imagewebp($newImg, '../public/img/simuImages/'. $idName);
            }
            // Déplace le fichier contenu dans le tableau file, au dossier public/img/flashs

            // Puis on renvoie true pour s'assurer que tout à bien été exécuté
            return true;
        }

        else{
        // Si l'image ajouté ne possède pas une extension correspondante à Webp, png etc
            return false;
        }
    }
}

?>

