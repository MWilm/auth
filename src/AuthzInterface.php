<?php

declare(strict_types=1);

namespace Jasny\Auth;

use Jasny\Auth\UserInterface as User;
use Jasny\Auth\ContextInterface as Context;

/**
 * Authorization service.
 */
interface AuthzInterface
{
    /**
     * Get all available authorization roles (for the current context).
     *
     * @return string[]
     */
    public function getAvailableRoles(): array;


    /**
     * Get an authz service for the given user.
     *
     * @param User|null    $user
     * @return AuthzInterface
     */
    public function forUser(?User $user): self;

    /**
     * Get an authz service for the given context.
     *
     * @param Context|null $context
     * @return AuthzInterface
     */
    public function inContextOf(?Context $context): self;

    /**
     * Get a copy, recalculating the authz role of the user.
     *
     * @return AuthzInterface
     */
    public function recalc(): self;


    /**
     * Get current authenticated user.
     */
    public function user(): User;

    /**
     * Get the current context.
     */
    public function context(): ?Context;


    /**
     * Check if the current user is logged in.
     */
    public function isLoggedIn(): bool;

    /**
     * Check if the current user is partially logged in.
     * Typically this means MFA verification is required.
     */
    public function isPartiallyLoggedIn(): bool;

    /**
     * Check if current user is not logged in or partially logged in.
     */
    public function isLoggedOut(): bool;

    /**
     * Check if the current user is logged in and has specified role.
     */
    public function is(string $role): bool;
}
