<?php

declare(strict_types=1);

namespace App\Administration\Domain\Security;

use App\Core\Domain\Model\User;
use App\Editorial\Domain\Repository\ArticleRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AuthorVoter extends Voter
{
    private const ATTRIBUTE = 'author';

    public function __construct(
        private readonly ArticleRepository $articleRepository
    ) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (self::ATTRIBUTE !== $attribute) {
            return false;
        }

        if (0 === (int) $subject) {
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

        $article = $this->articleRepository->get((int) $subject);

        return $article->user()->id() === $user->getId();
    }
}