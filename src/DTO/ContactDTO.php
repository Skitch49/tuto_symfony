<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactDTO
{

    #[NotBlank]
    #[Length(min:4,minMessage:'Votre nom est trop petit.',max:150,maxMessage:'Votre nom est trop long faut pas abuser non plus !')]
    public string $name = '';

    #[NotBlank]
    #[Email]
    public string $email = '';

    #[NotBlank]
    #[Length(min:6,minMessage:'Votre message est trop court.')]
    public string $message = '';

    #[NotBlank]
    public string $service = '';
}
