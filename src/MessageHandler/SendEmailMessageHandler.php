<?php

namespace App\MessageHandler;

use App\Message\SendEmailMessage;
use App\Repository\MovieRepository;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SendEmailMessageHandler implements MessageHandlerInterface
{
    private UserRepository $userRepository;
    private MovieRepository $movieRepository;
    private LoggerInterface $logger;

    public function __construct(
        UserRepository $userRepository,
        MovieRepository $movieRepository,
        LoggerInterface $logger
    ) {
        $this->userRepository = $userRepository;
        $this->movieRepository = $movieRepository;
        $this->logger = $logger;
    }

    public function __invoke(SendEmailMessage $message)
    {
        $user = $this->userRepository->find($message->getUserId());
        $movie = $this->movieRepository->find($message->getMovieId());

        if (empty($user) || empty($movie)) {
            $this->logger->error('Got invalid message');
            return;
        }

        $emailRecepient = $user->getEmail();
        $movieName = $movie->getName();

        $emailText = "Successfully added $movieName movie to your database";

        $this->logger->info("Pretending to send email to $emailRecepient with text '$emailText'");
    }
}
