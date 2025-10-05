<?php

namespace App\Service;

use App\Entity\Flash;
use App\Entity\Tattoo;

class ConvertImageFormat
{
	public function convertImageToWebp(array $files, ?Flash $flash, ?Tattoo $tattoo): bool
    {
        $image = $files['name']['image'];
        $tmpName = $files['tmp_name']['image'];
        $error = $files['error']['image'];
        list($width, $height) = getimagesize($tmpName);
        $extensions = array ('webp', 'png', 'jpeg', 'avif', 'jpg');
        $tabExtension = explode('.', $image);
        // On sépare le nom du flash en plusieurs partie en fonction dès que l'on tombe sur le caractère rentré en paramètres, donc le "."

        $imageString = file_get_contents($tmpName);
        $image = imagecreatefromstring($imageString);
        
        if($width > $height)
        {
            if($width > 350)
            {
                while($width > 350)
                {
                    $width /= 2;
                    $height /= 2;
                }
            }
            else
            {
                while($width < 150)
                {
                    $width *= 2;
                    $height *= 2;
                }
            }
        }
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

        $extension = strtolower(end($tabExtension));
        // On met en minuscule le nom de l'extension pour pouvoir la comparer à l'extension du dessus, plus tard
        if(in_array($extension, $extensions))
        // Si le fichier séléctionné est un fichier webp, png, jpeg ou avif
        {
            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $IdFlash = $uniqueName . ".webp";
            //$affiche = 5f586bf96dcd38.73540086.webp
            if($flash)
            {
                $flash->setImage($IdFlash);
                
                imagewebp($newImg, '../public/img/flashs/'. $IdFlash);
            }
            elseif($tattoo)
            {
                $tattoo->setImage($IdFlash);

                imagewebp($newImg, '../public/img/simuImages/'. $IdFlash);
            }
            // Déplace le fichier contenu dans le tableau file, au dossier public/img/flashs

            return true;
        }
        else{
            return false;
        }
    }
}

?>

