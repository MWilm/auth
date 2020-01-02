<?php

declare(strict_types=1);

namespace Jasny\Auth\Session;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Use OOP abstracted sessions to store auth session info.
 */
class SessionObject implements SessionInterface
{
    protected string $key;

    /** @var \ArrayAccess<string,mixed>|null */
    protected \ArrayAccess $session;

    /**
     * SessionObject constructor.
     *
     * @param \ArrayAccess<string,mixed>|null $session
     * @param string $key
     */
    public function __construct(\ArrayAccess $session, string $key = 'auth')
    {
        $this->session = $session;
        $this->key = $key;
    }

    /**
     * Use the `session` attribute if it's an object that implements ArrayAccess.
     *
     * @return self
     */
    public function forRequest(ServerRequestInterface $request): self
    {
        $session = $request->getAttribute('session');

        if (!$session instanceof \ArrayAccess) {
            return $this;
        }

        $copy = clone $this;
        $copy->session = $session;

        return $copy;
    }


    /**
     * Get auth information from session.
     *
     * @return array{uid:string|int|null,context:mixed,checksum:string|null}
     */
    public function getInfo(): array
    {
        $data = $this->session[$this->key] ?? [];

        return [
            'uid' => $data['uid'] ?? null,
            'context' => $data['context'] ?? null,
            'checksum' => $data['checksum'] ?? null,
        ];
    }

    /**
     * Persist auth information to session.
     *
     * @param string|int  $uid
     * @param mixed       $context
     * @param string|null $checksum
     */
    public function persist($uid, $context, ?string $checksum): void
    {
        $this->session[$this->key] = compact('uid', 'context', 'checksum');
    }

    /**
     * Remove auth information from session.
     */
    public function clear(): void
    {
        if (isset($this->session[$this->key])) {
            unset($this->session[$this->key]);
        }
    }
}
