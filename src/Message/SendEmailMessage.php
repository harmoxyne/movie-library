<?php

namespace App\Message;

final class SendEmailMessage
{

    private int $userId;

    private int $movieId;

    public function __construct(int $userId, int $movieId)
    {
        $this->userId = $userId;
        $this->movieId = $movieId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getMovieId(): int
    {
        return $this->movieId;
    }
}
