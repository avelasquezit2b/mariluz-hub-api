<?php

namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Entity\MediaExperiencesVideos; // Reemplaza "TuEntidad" con el nombre de tu entidad

class EntityListener
{
    public function preRemove(LifecycleEventArgs $event) 
    {

        $entity = $event->getEntity();
        

        //api_media_experiences_videos_delete_item

        if (!$entity instanceof MediaExperiencesVideos) {
            return;
        }


        $this->tuFuncion($entity);

    }

    public function tuFuncion(MediaExperiencesVideos $entity)
    {

        try {
            
            $itemContentUrl = $entity->getContentUrl();


            $json = '';

            //curl delete of itemContentUrl
            $url = $itemContentUrl;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            $result = json_decode($result);
            curl_close($ch);




        } catch (\Exception $e) {
            // Maneja cualquier error que ocurra durante la petición POST
            throw new \Exception('Error al hacer la petición POST: ' . $e->getMessage());
        }


    }
}

