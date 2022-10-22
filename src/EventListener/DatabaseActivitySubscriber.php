<?php
namespace App\EventListener;

use App\Entity\Product;
use Doctrine\ORM\Events;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Symfony\Component\Mailer\MailerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;


class DatabaseActivitySubscriber implements EventSubscriberInterface
{
    // this method can only return the event names; you cannot define a
    // custom method name to execute when each event triggers

    private MailerInterface $mailer;
    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
    }

    public function getSubscribedEvents(): array
    {
        return [
            //Events::postFlush,
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate,
        ];
    }

    

    // callback methods must be called exactly like the events they listen to;
    // they receive an argument of type LifecycleEventArgs, which gives you access
    // to both the entity object of the event and the entity manager itself


    // public function postFlush(PostFlushEventArgs $args)
    // {
    //     $entity = $args->getObject()->getTitle();
    //     echo "<pre>";
    //     print_r($args);die;
        
    // }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $productTitle = $args->getObject()->getTitle();
        $email = (new Email())
            ->from('luckyshy@gmail.com')
            ->to('thangit1607@gmail.com')
            ->subject('New Product have been added!')
            ->text('New Product have been added!')
            ->html('<p>New Product '.$productTitle.' have been added!</p>');
               
        $this->logActivity('persist', $args);
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        
        $this->logActivity('remove', $args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $productTitle = $args->getObject()->getTitle();
        $email = (new Email())
            ->from('luckyshy@gmail.com')
            ->to('thangit1607@gmail.com')
            ->subject('New Product have been UPDATED!')
            ->text('New Product have been UPDATED!')
            ->html('<p>New Product '.$productTitle.' have been UPDATED!</p>');
        $this->logActivity('update', $args);
    }

    private function logActivity(string $action, LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof Product) {
            return;
        }

        // ... get the entity information and log it somehow
    }
}