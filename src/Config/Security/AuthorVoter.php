<?php

declare(strict_types=1);

namespace App\Config\Security;

use App\Config\Model\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AuthorVoter extends Voter
{
    private const ATTRIBUTE = 'author';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (self::ATTRIBUTE !== $attribute) {
            return false;
        }

        if (!is_int($subject)) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return $subject === $user->id();
    }
}