<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

/**
 * Service chargé de créer et d'envoyer des emails
 */
class EmailSender
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Créer un émail préconfiguré 
     * @param string $subject    Le sujet du mail
     */
    private function creatTemplateEmail(string $subject): TemplatedEmail
    {
        return (new TemplatedEmail())
        
            ->from(new Address('joel.mirca@laposte.net','kritiko'))      #expéditeur
            ->subject("\u{1F3A7} Kritik | $subject")               #objet de l'email 
        ;    
    }

    /**
     * Envoyer un email de confirmation de compte suite à l'inscription
     * @param User $user    m'utilisayeur devant confirmer son compte
     */
    public function sendAccountConfirmationEmail(User $user): void
    {
        $email = $this->creatTemplateEmail('Confirmation du compte')
            ->to(new Address($user->getEmail(), $user->getPseudo()))  #destinataire
            ->htmlTemplate('email/account_confirmation.html.twig')  #template twig du message
            ->context([                                             #variables du tamplate
                'user'=> $user,
            ])
        ;

        // Envoi de l'email
        $this->mailer->send($email);
    }
}
