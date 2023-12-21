<?php

namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * JWTResponseListener.
 *
 * @author Antoine Bluchet <abluchet@ds-restauration.com>
 */
class JWTResponseListener
{


    /**
     * Add public data to the authentication response.
     *
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();
        if (!$user instanceof UserInterface) {
            return;
        }

        $payload['user'] = array(
            'id' => $user->getId(),
            'username' => $user->getUsername(),
        );
        //$userRepository->invalidate($user->getUsername());

        $event->setData(array_merge($data, $payload));
    }
}
